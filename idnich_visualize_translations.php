<?php
	class iDNich_Visualize_Translations {
		public function __construct() {			
			add_action('admin_menu', array($this, 'add_admin_menu'), 20);
			
			add_action('wp_ajax_consult_traduction', array($this, 'consult_traduction'));
			add_action('wp_ajax_publish_traduction', array($this, 'publish_traduction'));
		}

		public function add_admin_menu() {
			add_submenu_page('iDNich_Plugin', 'Visualize Traductions', 'Visualize', 'manage_options', 'idnich_plugin_visualize_translations', array($this, 'visualize_translate_admin_menu'));
		}
			
		public function consult_traduction() {
			global $wpdb;
			
			$article_id 	= (isset($_GET['article_id'])) 				? intval($_GET['article_id']) 					: 0;
			$from_to 		= (isset($_GET['from_to'])) 				? sanitize_text_field($_GET['from_to']) 		: '';
			
			$translation_result = array();
			$translation_result['category_id'] 		= '';
			$translation_result['category_name'] 	= '';
			$translation_result['title'] 			= '';
			$translation_result['meta'] 			= '';
			$translation_result['text'] 			= '';
			
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}idnich_translation WHERE post_id = '$article_id' AND translation_from_to = '$from_to'");
			if(!is_null($row)) {
				
				$row = stripslashes_deep($row);
				
				$translation_result = array();
				$translation_result['category_id'] 		= $row->translation_category_id;
				$translation_result['category_name'] 	= $row->translation_category_name;
				$translation_result['title'] 			= $row->translation_title;
				$translation_result['meta'] 			= $row->translation_meta;
				$translation_result['text'] 			= $row->translation;
			}
			
			echo json_encode($translation_result);
			exit();
		}
		
		public function publish_traduction() {
			global $wpdb;
			
			$dialog_result 						= array();
			$dialog_result['dialog_title'] 		= __('Erreur lors de la publication', 'idnich_translate');
			$dialog_result['dialog_content'] 	= __('La traduction sélectionnée <b>n\'existe pas</b>.', 'idnich_translate');
			
			$article_id 	= (isset($_GET['article_id'])) 				? intval($_GET['article_id']) 					: 0;
			$from_to 		= (isset($_GET['from_to'])) 				? sanitize_text_field($_GET['from_to']) 		: '';
			
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}idnich_translation WHERE post_id = '$article_id' AND translation_from_to = '$from_to'");
			if(!is_null($row)) {
				$category_id = wp_create_category($row->translation_category_name);
				
				$new_article = 	array(
										'post_title'    => mb_convert_encoding(wp_strip_all_tags($row->translation_title), 'UTF-8', 'auto'),
										'post_content'  => mb_convert_encoding($row->translation, 'UTF-8', 'auto'),
										'post_status'   => 'publish',
										'post_author'   => 1,
										'post_category' => array($category_id)
								);
				$new_article_id = wp_insert_post($new_article, true);
				update_post_meta($new_article_id, '_yoast_wpseo_metadesc', $row->translation_meta);
				$data = array	(
									'post_id' 			=> $new_article_id, 
									'old_token' 		=> $article_id,
									'current_url' 		=> get_post_permalink($new_article_id, true)
								);
				$wpdb->insert("{$wpdb->prefix}idnich_imported_translation", $data);
				
				if($new_article_id != 0) {
					$dialog_result['dialog_title'] 		= __('Traduction publiée !', 'idnich_translate');
					$dialog_result['dialog_content'] 	= __('La traduction a <b>bien été publiée</b>.<br/>Voici l\'url a laquelle vous pouvez la consulter : <a href="' . get_post_permalink($new_article_id) . '" target="_blank">lien vers la publication</a>.', 'idnich_translate');	
				}
				else {
					$dialog_result['dialog_content'] 	= __('Impossible de publier ce contenu.', 'idnich_translate');
				}
			}
			
			echo json_encode($dialog_result);
			exit();
		}
		
		public function visualize_translate_admin_menu() {
			global $wpdb;
?>
			<div id="application_window">
				<div id="application_layout">
					<div id="idnich_visualize_translations_wrapper" style="padding:25px;">
						<div>
							<div align="left" style="color:black;padding:20px;vertical-align:middle;">
								<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/iDNich_logo.png'; ?>" width="180"/><br/>
								<h2 style="color:black;margin-left:55px;"><?php echo get_admin_page_title(); ?></h2>
							</div>
						</div><!--
						--><div id="idnich_visualize_translation_result" class="default_font" style="position:relative;display:none;vertical-align:middle;padding:20px;background-color:white;border: 1px solid darkgray;border-left:3px solid rgb(251,115,39);">
							<div style="padding:25px;margin:25px 0px;">
								<h1 id="idnich_visualize_translation_result_title"></h1>
								<hr/>
							</div>
							<div id="idnich_visualize_translation_result_text"></div>
							<button id="close_idnich_visualize_translation_result" class="button button-secondary" style="position:absolute;top:10px;right:10px;"><i class="fas fa-arrow-circle-left"></i></button>
						</div><!--
						--><div id="idnich_visualize_translations_list" class="default_font" style="vertical-align:middle;padding:20px;background-color:white;border: 1px solid darkgray;border-left:3px solid rgb(251,115,39);">
<?php
							$translations_rows = $wpdb->get_results("SELECT post_id, translation_category_name, translation_title, translation_from_to FROM {$wpdb->prefix}idnich_translation ORDER BY translation_from_to");
							$current_translation = '';
							foreach($translations_rows as $translation_row) {
								if($current_translation != $translation_row->translation_from_to) {
									$current_translation = $translation_row->translation_from_to;
									echo '<div style="font-weight:bold;padding:5px 20px;">' . $translation_row->translation_from_to . '</div>';
								}
								echo 	'<div style="border-bottom:1px solid lightgray;padding:15px 20px;margin:15px;">' . 
											'<div class="publish_traduction inline_box" style="width:25px;cursor:pointer;" data-id="' . $translation_row->post_id . '" data-from-to="' . $translation_row->translation_from_to . '">
												<i title="' . __('Publier<br/>(Ce bouton permet de publier directement une traduction sur le site d\'origine, sans passer par l\'importation.)', 'idnich_translate') . '" class="fas fa-rss-square fa-lg"></i>
											</div>' .
											'<div class="inline_box" style="width:calc(100% - 50px);">
												<em>' . $translation_row->translation_category_name . '</em> / ' . $translation_row->translation_title . 
											'</div>' . 
											'<div class="consult_traduction inline_box" style="width:25px;cursor:pointer;" data-id="' . $translation_row->post_id . '" data-from-to="' . $translation_row->translation_from_to . '">
												<i title="' . __('Visualiser', 'idnich_translate') . '" class="far fa-eye fa-lg"></i>
											</div>
										</div>';
							}
?>
						</div>
					</div>
				</div>
			</div>
<?php
		}
	}
?>