<?php
	class iDNich_Translate {
		public function __construct() {			
			add_action('admin_menu', array($this, 'add_admin_menu'), 20);
			
			add_action('wp_ajax_save_options_and_keep_list', array($this, 'save_options_and_keep_list'));
			add_action('wp_ajax_generate_translation', array($this, 'generate_translation'));
			add_action('wp_ajax_get_article_content', array($this, 'get_article_content'));
			add_action('wp_ajax_get_translation_result_from_get', array($this, 'get_translation_result_from_get'));
		}

		public function add_admin_menu() {
			add_submenu_page('iDNich_Plugin', 'Translation', 'Translation', 'manage_options', 'idnich_translate', array($this, 'translate_admin_menu'));
		}

		public function save_options_and_keep_list() {
			$keep_list 	= (isset($_POST['keep_list'])) 		? sanitize_textarea_field($_POST['keep_list']) 				: '';
			$options	= (isset($_POST['options'])) 		? sanitize_textarea_field($_POST['options']) 				: '';
			
			update_option('dnich_keep_list', $keep_list);
			update_option('dnich_options', $options);
			
			$result				= array();
			$result['status'] 	= 'success';
			$result['result'] 	= '';
			
			echo json_encode($result);
			exit();
		}
		
		public function generate_translation() {
			global $wpdb;
			
			$article_id 						= (isset($_GET['article_id'])) 		? intval($_GET['article_id']) 		: 0;
			
			$fields 							= array();

			$fields['source_id'] 				= get_bloginfo('name');
			$fields['post_token'] 				= $article_id;
			$fields['keep_list'] 				= (isset($_POST['keep_list'])) 		? sanitize_textarea_field($_POST['keep_list']) 			: '';
			$fields['options'] 					= (isset($_POST['options'])) 		? sanitize_textarea_field($_POST['options']) 			: '';
			$fields['category_id'] 				= (isset($_POST['category_id']))	? intval($_POST['category_id']) 						: 0;
			$fields['category_name'] 			= (isset($_POST['category_name']))	? sanitize_text_field($_POST['category_name']) 			: '';
			$fields['from'] 					= (isset($_POST['from'])) 			? sanitize_text_field($_POST['from']) 					: '';
			$fields['to'] 	 					= (isset($_POST['to'])) 			? sanitize_text_field($_POST['to']) 					: '';
			$fields['title'] 	 				= (isset($_POST['title'])) 			? sanitize_text_field(wp_unslash($_POST['title']))		: '';
			$fields['meta'] 	 				= (isset($_POST['meta'])) 			? sanitize_text_field(wp_unslash($_POST['meta'])) 		: '';
			$fields['text'] 	 				= (isset($_POST['text'])) 			? wp_unslash($_POST['text']) 							: '';

			$doc = idnich_str_get_html($fields['text']);
			foreach($doc->find("a") as $inside_link) {
				$link_href 				= $inside_link->href;
				$post_id 				= url_to_postid($link_href);
				if($post_id !== 0) {
					$inside_link->{'data-token'} = $post_id;
				}
			}
			$fields['text'] = trim($doc->save());
			
			$headers   = array(
				'Authorization' => 'Bearer ' . get_option("dnich_api_token")
			);
			
			$args = array(
				'body'        => $fields,
				'timeout'     => '20000',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $headers,
				'cookies'     => array(),
			);

			$translation_api_answer  = wp_remote_post(get_option("dnich_api_url") . 'translation', $args);
			$translation_api_answer	= $translation_api_answer['body'];
			
			if($article_id != 0) {
				$translation_api_insert = json_decode($translation_api_answer);
				
				$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}idnich_translation WHERE post_id = '" . $article_id . "' AND translation_from_to = '" . $fields['from'] . '/' . $fields['to'] . "'");
				$data = array	(
									'post_id' 					=> $article_id, 
									'translation_category_id' 	=> $translation_api_insert->translation_category_id,
									'translation_category_name' => $translation_api_insert->translation_category_name,
									'translation_from_to' 		=> $fields['from'] . '/' . $fields['to'],
									'translation_title' 		=> $translation_api_insert->translation_title,
									'translation_meta' 			=> $translation_api_insert->translation_meta,
									'translation' 				=> $translation_api_insert->translation
								);
				if(is_null($row)) {
					$wpdb->insert("{$wpdb->prefix}idnich_translation", $data);
				}
				else {
					$where =	[
									'post_id' => $article_id,
									'translation_from_to' => ($fields['from'] . '/' . $fields['to']),
								];
					$wpdb->update("{$wpdb->prefix}idnich_translation", $data, $where);
				}
			}
			
			echo $translation_api_answer;
			exit();
		}
		
		public function get_article_content() {
			global $wpdb;
			
			$article_content_result = array();
			
			$article_id 	= (isset($_GET['article_id'])) 				? intval($_GET['article_id']) 					: 0;
			$from 			= (isset($_GET['from'])) 					? sanitize_text_field($_GET['from']) 			: '';
			$to 			= (isset($_GET['to'])) 						? sanitize_text_field($_GET['to']) 				: '';
			if($article_id != 0) {
				$categories 											= wp_get_post_categories($article_id);
				$category_id 											= 0;
				$category_name 											= '';
				foreach($categories as $category){
					$category_object 	= get_category($category);
					$category_id     	= $category_object->term_id;
					$category_name    	= $category_object->cat_name;
					break;
				}
				
				$article_content_result['has_translation'] 				= 0;
				$article_content_result['original_category_id'] 		= $category_id;
				$article_content_result['original_category_name'] 		= $category_name;
				$article_content_result['original_title'] 				= get_post_field('post_title', $article_id);
				$article_content_result['original_meta'] 				= get_post_meta($article_id, '_yoast_wpseo_metadesc');
				$article_content_result['original_content'] 			= get_post_field('post_content', $article_id);

				$article_content_result['translation_category_id'] 		= 0;
				$article_content_result['translation_category_name'] 	= '';
				$article_content_result['translation_title'] 			= '';
				$article_content_result['translation_meta'] 				= '';
				$article_content_result['translation_content'] 			= '';
				$translation_result = $this->get_translation_result($article_id, $from, $to);
				if(is_array($translation_result)) {
					$article_content_result['has_translation'] 			= 1;
					$article_content_result['translation_category_id'] 	= $translation_result['category_id'];
					$article_content_result['translation_category_name'] = $translation_result['category_name'];
					$article_content_result['translation_title'] 		= $translation_result['title'];
					$article_content_result['translation_meta'] 			= $translation_result['meta'];
					$article_content_result['translation_content'] 		= $translation_result['text'];
				}
			}
			
			echo json_encode($article_content_result);
			exit();
		}
		
		public function get_translation_result_from_get() {
			$article_content_result = array();
			$article_content_result['has_translation'] 						= 0;
			$article_content_result['translation_category_id'] 				= 0;
			$article_content_result['translation_category_name'] 			= '';
			$article_content_result['translation_title'] 					= '';
			$article_content_result['translation_meta'] 						= '';
			$article_content_result['translation_content'] 					= '';
			
			$article_id 	= (isset($_GET['article_id'])) 					? intval($_GET['article_id']) 					: 0;
			$from 			= (isset($_GET['from'])) 						? sanitize_text_field($_GET['from']) 			: '';
			$to 			= (isset($_GET['to'])) 							? sanitize_text_field($_GET['to']) 				: '';
			if($article_id != 0) {
				$translation_result = $this->get_translation_result($article_id, $from, $to);
				if(is_array($translation_result)) {
					$article_content_result['has_translation'] 				= 1;
					$article_content_result['translation_category_id'] 		= $translation_result['category_id'];
					$article_content_result['translation_category_name'] 	= $translation_result['category_name'];
					$article_content_result['translation_title'] 			= $translation_result['title'];
					$article_content_result['translation_meta'] 				= $translation_result['meta'];
					$article_content_result['translation_content'] 			= $translation_result['text'];
				}
			}
			
			echo json_encode($article_content_result);
			exit();
		}
			
		public function get_translation_result($article_id, $from, $to) {
			global $wpdb;
			
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}idnich_translation WHERE post_id = '$article_id' AND translation_from_to = '$from/$to'");
			if(!is_null($row)) {
				
				$row = stripslashes_deep($row);
				
				$translation_result = array();
				$translation_result['category_id'] 	= $row->translation_category_id;
				$translation_result['category_name'] = $row->translation_category_name;
				$translation_result['title'] 		= $row->translation_title;
				$translation_result['meta'] 			= $row->translation_meta;
				$translation_result['text'] 			= $row->translation;
				
				return $translation_result;
			}
			
			return false;
		}
		
		public function translate_admin_menu() {
?>
			<div id="application_window">
				<div id="application_layout">
					<div id="idnich_translate_wrapper" style="padding:25px;">
						<div align="right">
							<div class="inline_box" id="auto_translation_loader_spin_loader" style="color:black;font-size:0.8rem;vertical-align:middle;padding:3px 20px;margin:15px 0px;visibility:hidden;">
								<div class="inline_box" style="vertical-align:middle;">
									<button id="generate_idnich_stop_auto_translation_button" class="button button-secondary" style="min-width:30px;height:15px;" title="<?php echo __('Stop Auto-Traduction', 'idnich_translate'); ?>"><i class="far fa-stop-circle"></i></button>
								</div>
								<div class="inline_box default_font inline_box .white_selector_box" style="vertical-align:middle;margin-left:10px;">
									<i id="auto_translation_loader_spin" class="fas fa-spinner fa-1x"></i>&nbsp;&nbsp;<?php echo __('Traduction en cours', 'idnich_translate'); ?>
								</div>
							</div>
							<div class="default_font inline_box white_informations_box" style="cursor:pointer;" title="Votre Slug">
<?php
								echo get_bloginfo('name');
?>
							</div>
							<div class="inline_box" id="translations_api_limit">
<?php
								if(get_option("dnich_api_token") !== '') {
									$body = array(
										'api_type' => 'translation'
									);
									
									$headers   = array(
										'Authorization' => 'Bearer ' . get_option("dnich_api_token")
									);
									
									$args = array(
										'body'        => $body,
										'timeout'     => '500',
										'redirection' => '5',
										'httpversion' => '1.0',
										'blocking'    => true,
										'headers'     => $headers,
										'cookies'     => array(),
									);

									$api_limits = wp_remote_post(get_option("dnich_api_url") . 'api_limits', $args);
									$api_limits	= json_decode($api_limits['body']);

									if($api_limits->status === 'success') {
										echo 	'<div class="default_font inline_box white_informations_box">
													Nombre de requêtes API Traduction
												</div>
												<div class="default_font inline_box white_informations_box">
													' . $api_limits->current_count . '/' . $api_limits->api_limit . '
												</div>';
									}
									else if($api_limits->status === 'pending') {
										echo 	'<div class="default_font inline_box white_informations_box">
													<em>
														Nombre de crédits API insuffisants : ' . $api_limits->result . '.<br/>
														Veuillez en commander afin de poursuivre l\'utilisation du plugin.
													</em>
												</div>';
									}
									else {
										echo 	'<div class="default_font inline_box white_informations_box">
													<em>
														Une erreur s\'est produite : ' . $api_limits->result . '.
													</em>
												</div>';
									}
								}
								else {
									echo 	'<div class="default_font inline_box white_informations_box">
												<em>
													Aucun accès à l\'api de translation n\'a encore été configuré.
												</em>
											</div>';
								}
?>
							</div>
						</div>
						
						<style>
							@keyframes rotate360spinner {
							to { transform: rotate(360deg); }
							}
							#auto_translation_loader_spin { animation: 2s rotate360spinner infinite linear; }
						</style>
						<div class="white_space_no_wrap" align="center" style="width:100%;position:relative;height:40px;">
							<div class="inline_box" style="position:absolute;left:0;top:-80px;">
								<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/iDNich_logo.png'; ?>" width="180"/><br/>
								<h2 style="color:black;"><?php echo get_admin_page_title(); ?></h2>
							</div>
							<div class="inline_box" style="vertical-align:bottom;position:absolute;left:200px;">
								<select id="generate_idnich_translation_article" title="Sélection du texte à traduire">
									<option value="0"><?php echo __('Texte personnalisé', 'idnich_translate'); ?></option>
<?php
									echo '<optgroup label="' . __('Articles', 'idnich_translate') . '">';
									$args = array('order'=> 'ASC', 'orderby' => 'date');
								
									$args 					= array(
																'post_type' => 'post',
																'orderby' => 'date',
																'order' => 'DESC',
																'posts_per_page' => -1
															);

									$preview_posts 		= new WP_Query($args);
									while($preview_posts->have_posts()) {
										$preview_posts->the_post();
										echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
									}
									echo '</optgroup>';
									
									echo '<optgroup label="' . __('Pages', 'idnich_translate') . '">';
									$args = array('order'=> 'ASC', 'orderby' => 'date');
								
									$args 					= array(
																'post_type' => 'page',
																'orderby' => 'date',
																'order' => 'DESC',
																'posts_per_page' => -1
															);

									$preview_posts 		= new WP_Query($args);
									while($preview_posts->have_posts()) {
										$preview_posts->the_post();
										echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
									}
									echo '</optgroup>';
?>
								</select>
								<div class="inline_box" style="background-color:white !important;border:none !important;border-radius:10px !important;">
									<select id="generate_idnich_translation_from" style="width:100px;" title="Langue à traduire">
										<option value="af">Afrikaans</option>
										<option value="ga">Irish</option>
										<option value="sq">Albanian</option>
										<option value="it">Italian</option>
										<option value="ar">Arabic</option>
										<option value="ja">Japanese</option>
										<option value="az">Azerbaijani</option>
										<option value="kn">Kannada</option>
										<option value="eu">Basque</option>
										<option value="ko">Korean</option>
										<option value="bn">Bengali</option>
										<option value="la">Latin</option>
										<option value="be">Belarusian</option>
										<option value="lv">Latvian</option>
										<option value="bg">Bulgarian</option>
										<option value="lt">Lithuanian</option>
										<option value="ca">Catalan</option>
										<option value="mk">Macedonian</option>
										<option value="zh-CN">Chinese Simplified</option>
										<option value="ms">Malay</option>
										<option value="zh-TW">Chinese Traditional</option>
										<option value="mt">Maltese</option>
										<option value="hr">Croatian</option>
										<option value="no">Norwegian</option>
										<option value="cs">Czech</option>
										<option value="fa">Persian</option>
										<option value="da">Danish</option>
										<option value="pl">Polish</option>
										<option value="nl">Dutch</option>
										<option value="pt">Portuguese</option>
										<option value="en">English</option>
										<option value="ro">Romanian</option>
										<option value="eo">Esperanto</option>
										<option value="ru">Russian</option>
										<option value="et">Estonian</option>
										<option value="sr">Serbian</option>
										<option value="tl">Filipino</option>
										<option value="sk">Slovak</option>
										<option value="fi">Finnish</option>
										<option value="sl">Slovenian</option>
										<option value="fr" selected>French</option>
										<option value="es">Spanish</option>
										<option value="gl">Galician</option>
										<option value="sw">Swahili</option>
										<option value="ka">Georgian</option>
										<option value="sv">Swedish</option>
										<option value="de">German</option>
										<option value="ta">Tamil</option>
										<option value="el">Greek</option>
										<option value="te">Telugu</option>
										<option value="gu">Gujarati</option>
										<option value="th">Thai</option>
										<option value="ht">Haitian Creole</option>
										<option value="tr">Turkish</option>
										<option value="iw">Hebrew</option>
										<option value="uk">Ukrainian</option>
										<option value="hi">Hindi</option>
										<option value="ur">Urdu</option>
										<option value="hu">Hungarian</option>
										<option value="vi">Vietnamese</option>
										<option value="is">Icelandic</option>
										<option value="cy">Welsh</option>
										<option value="id">Indonesian</option>
										<option value="yi">Yiddish</option>
									</select>
									&nbsp;<i class="fas fa-angle-double-right" style="color:rgb(251,115,39);"></i>&nbsp;
									<select id="generate_idnich_translation_to" style="width:100px;">
										<option value="af">Afrikaans</option>
										<option value="ga">Irish</option>
										<option value="sq">Albanian</option>
										<option value="it">Italian</option>
										<option value="ar">Arabic</option>
										<option value="ja">Japanese</option>
										<option value="az">Azerbaijani</option>
										<option value="kn">Kannada</option>
										<option value="eu">Basque</option>
										<option value="ko">Korean</option>
										<option value="bn">Bengali</option>
										<option value="la">Latin</option>
										<option value="be">Belarusian</option>
										<option value="lv">Latvian</option>
										<option value="bg">Bulgarian</option>
										<option value="lt">Lithuanian</option>
										<option value="ca">Catalan</option>
										<option value="mk">Macedonian</option>
										<option value="zh-CN">Chinese Simplified</option>
										<option value="ms">Malay</option>
										<option value="zh-TW">Chinese Traditional</option>
										<option value="mt">Maltese</option>
										<option value="hr">Croatian</option>
										<option value="no">Norwegian</option>
										<option value="cs">Czech</option>
										<option value="fa">Persian</option>
										<option value="da">Danish</option>
										<option value="pl">Polish</option>
										<option value="nl">Dutch</option>
										<option value="pt">Portuguese</option>
										<option value="en" selected>English</option>
										<option value="ro">Romanian</option>
										<option value="eo">Esperanto</option>
										<option value="ru">Russian</option>
										<option value="et">Estonian</option>
										<option value="sr">Serbian</option>
										<option value="tl">Filipino</option>
										<option value="sk">Slovak</option>
										<option value="fi">Finnish</option>
										<option value="sl">Slovenian</option>
										<option value="fr">French</option>
										<option value="es">Spanish</option>
										<option value="gl">Galician</option>
										<option value="sw">Swahili</option>
										<option value="ka">Georgian</option>
										<option value="sv">Swedish</option>
										<option value="de">German</option>
										<option value="ta">Tamil</option>
										<option value="el">Greek</option>
										<option value="te">Telugu</option>
										<option value="gu">Gujarati</option>
										<option value="th">Thai</option>
										<option value="ht">Haitian Creole</option>
										<option value="tr">Turkish</option>
										<option value="iw">Hebrew</option>
										<option value="uk">Ukrainian</option>
										<option value="hi">Hindi</option>
										<option value="ur">Urdu</option>
										<option value="hu">Hungarian</option>
										<option value="vi">Vietnamese</option>
										<option value="is">Icelandic</option>
										<option value="cy">Welsh</option>
										<option value="id">Indonesian</option>
										<option value="yi">Yiddish</option>
									</select>
								</div>
								&nbsp;&nbsp;
								<button id="generate_idnich_translation_button" class="idnich-button"><?php echo __('Traduire', 'idnich_translate'); ?></button>
								<button id="generate_idnich_auto_translation_button" class="idnich-button" title="<?php echo __('Auto-Traduction<br/>Une coupure ? Aucun problème, tous vos articles traduits sont en base, il vous suffit de relancer (CTRL + Shift + R) !', 'idnich_translate'); ?>"><i class="fas fa-retweet"></i></button>
							</div>
						</div>
						<div class="default_font white_space_no_wrap" style="margin:20px 0px;height:100%;padding:10px;">
							<div class="idnich_translation_box">
								<div class="white_space_no_wrap">
									<div class="inline_box" style="position:relative;vertical-align:middle;width:30%;padding-left:20px;" align="left">
										<div class="inline_box" style="vertical-align:bottom;margin-bottom:7px;"><b>Texte à traduire</b></div><!--
										--><div class="inline_box" style="vertical-align:bottom;margin-left:10px;"><!--
												--><button id="generate_idnich_translation_options_button" class="idnich-button" style="min-width:25px;padding:0px 10px;" title="<?php echo __('Options de ShortCodes à conserver au moment de la translation.<br/>Si rien n\'est renseigné, les ShortCodes seront automatiquement retirés.<br/>Mettez le début du ShortCode, sans les options, à raison de un par ligne.<br/>Exemple :<br/>[Debut_du_shortcode_1<br/>[Debut_du_shortcode_2', 'idnich_translate'); ?>"><i class="fas fa-code"></i></button><!--
												--><textarea id="generate_idnich_translation_options" style="display:none;position:absolute;top:30px;width:300px;border:1px solid black !important;background-color:white !important;height:150px;resize:none;"><?php echo get_option("dnich_options"); ?></textarea><!--
										--></div><!--
										--><div class="inline_box" style="vertical-align:bottom;margin-left:3px;"><!--
												--><button id="generate_idnich_translation_keep_list_button" class="idnich-button" style="min-width:25px;padding:0px 10px;" title="<?php echo __('Mots à conserver ou à changer tel quel dans la traduction.<br/>Exemple avec un mot par ligne comme ceci :<br/>E-commerce<br/>Déjà-vu<br/>Cigarette électronique=E-cigarette<br/>Dans ce dernier cas, la traduction sera figée pour cette expression, et \'Cigarette électronique\' sera remplacé par \'E-cigarette\' dans le résultat.', 'idnich_translate'); ?>"><i class="fab fa-wikipedia-w"></i></button><!--
												--><textarea id="generate_idnich_translation_keep_list" placeholder="<?php echo __('/Exemple mots à garder/&#10;Déjà-vu&#10;etc.&#10;&#10;/Exemple de mots à figer ou à traduire de force/&#10;Cigarette électronique=E-liquide&#10;Avocat=Lawyer&#10;Avocat=Avocado', 'idnich_translate'); ?>" style="display:none;position:absolute;top:30px;width:300px;border:1px solid black !important;background-color:white !important;height:150px;resize:none;"><?php echo get_option("dnich_keep_list"); ?></textarea><!--
										--></div><!--
									--></div><!--
									--><div class="inline_box" style="vertical-align:middle;width:45%;" align="center">
										<div class="white_space_no_wrap" style="position:relative;"><!--
											--><input id="generate_idnich_translation_category_id" type="hidden" value=""/><!--
											--><input id="generate_idnich_translation_category_name" type="hidden" value=""/><!--
											--><input class="generate_idnich_translation_title" id="generate_idnich_translation_title" type="text" value=""/><!--
											--><button id="generate_idnich_translation_meta_button" class="idnich-button dniche_translation_meta_button" title="<?php echo __('Meta Description', 'idnich_translate'); ?>">Meta</button><!--
											--><textarea id="generate_idnich_translation_meta" style="display:none;position:absolute;right:15px;top:30px;width:400px;background-color:rgb(241,241,241) !important;height:150px;resize:none;"></textarea><!--
										--></div>
									</div><!--
									--><div class="inline_box" style="vertical-align:middle;width:25%;" align="right">
										<div id="text_to_translation_format_text" class="inline_box white_selector_box white_selector_box_selected" style="border-right:1px solid transparent;">
											TEXT
										</div><!--
										--><div id="text_to_translation_format_html" class="inline_box white_selector_box">
											HTML
										</div>
									</div>
								</div>
								<div id="generate_idnich_translation_text" style="height:calc(100% - 30px);border: 1px solid darkgray;border-left:3px solid rgb(251,115,39);padding:10px;text-align:left;overflow:auto;white-space:pre-wrap;" contenteditable="true"></div>
							</div><!--
							--><div class="idnich_translation_box" style="margin-left:2px;">
								<div class="white_space_no_wrap">
									<div class="inline_box" style="vertical-align:middle;width:25%;padding-left:20px;" align="left">
										<b>Texte traduit</b>
									</div><!--
									--><div class="inline_box" style="vertical-align:middle;width:50%;" align="center">
										<div class="white_space_no_wrap" style="position:relative;"><!--
											--><input id="generate_idnich_translation_category_id_result" type="hidden" value=""/><!--
											--><input id="generate_idnich_translation_category_name_result" type="hidden" value=""/><!--
											--><input class="generate_idnich_translation_title" id="generate_idnich_translation_title_result" type="text" value=""/><!--
											--><button id="generate_idnich_translation_meta_result_button" class="idnich-button dniche_translation_meta_button" title="<?php echo __('Meta Description', 'idnich_translate'); ?>">Meta</button><!--
											--><textarea id="generate_idnich_translation_meta_result" style="display:none;position:absolute;right:15px;top:30px;width:400px;background-color:rgb(241,241,241) !important;height:150px;resize:none;"></textarea><!--
										--></div>
									</div><!--
									--><div class="inline_box" style="vertical-align:middle;width:25%;" align="right">
										<div id="text_translated_format_text" class="inline_box white_selector_box" style="border-right:1px solid transparent;">
											TEXT
										</div><!--
										--><div id="text_translated_format_html" class="inline_box white_selector_box white_selector_box_selected">
											HTML
										</div>
									</div>
								</div>
								<div id="generate_idnich_translation_result" style="height:calc(100% - 30px);border: 1px solid darkgray;padding:10px;text-align:left;overflow:auto;white-space:pre-wrap;" contenteditable="true"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php
		}
	}
?>