<?php
	$replace_table = [
		'&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
		'&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
		'&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
		'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
		'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
		'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
		'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
		'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
		'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
		'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
		'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
		'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
		'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
		'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
		'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
		'&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
		'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
		'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
		'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
		'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
		'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
		'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
		'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
		'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
		'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
		'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
		'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
		'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
		'&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
		'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
		'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
		'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
		'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
		'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
		'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
		'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
		'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
		'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
		'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
		'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
		'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
		'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
		'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
		'ю' => 'yu', 'я' => 'ya', '\'' => '', '.' => '-', '?' => ''
	];
	
	class iDNich_Import_Translations {
		public function __construct() {			
			add_action('admin_menu', array($this, 'add_admin_menu'), 20);
			
			add_action('wp_ajax_print_inside_links'		, array($this, 'print_inside_links'));
			add_action('wp_ajax_clean_inside_links'		, array($this, 'clean_inside_links'));
			add_action('wp_ajax_get_translations_count'	, array($this, 'get_translations_count'));
			add_action('wp_ajax_get_import_translation'	, array($this, 'get_import_translation'));
		}

		public function add_admin_menu() {
			add_submenu_page('iDNich_Plugin', 'Import Traduction', 'Import', 'manage_options', 'idnich_plugin_import_translations', array($this, 'import_translate_admin_menu'));
		}
		
		public function print_inside_links() {
			global $wpdb;
						
			$imported_translation_array = array();
			$imported_translations_rows = $wpdb->get_results("SELECT post_id, resolved FROM {$wpdb->prefix}idnich_imported_translation WHERE resolved = 0");
			
			$everything_resolved = true;
			foreach($imported_translations_rows as $imported_translation_row) {
				$imported_translation_array[] = $imported_translation_row->post_id;
				if((int)$imported_translation_row->resolved == 0) {
					$everything_resolved = false;
				}
			}
			
			$all_inside_links_answer_array = array();
			$all_inside_links_answer_array['everything_resolved'] 			= $everything_resolved;
			$all_inside_links_answer_array['imported_translation_array'] 	= $imported_translation_array;

			echo json_encode($all_inside_links_answer_array);
			exit();
		}
		
		public function all_inside_links() {
			global $wpdb;
						
			$imported_translation_array = array();
			$imported_translations_rows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}idnich_imported_translation");
			
			$everything_resolved = true;
			foreach($imported_translations_rows as $imported_translation_row) {
				$imported_translation_array[ $imported_translation_row->old_token ] = $imported_translation_row->current_url;
				if((int)$imported_translation_row->resolved == 0) {
					$everything_resolved = false;
				}
			}
			
			$all_inside_links_answer_array = array();
			$all_inside_links_answer_array['everything_resolved'] 			= $everything_resolved;
			$all_inside_links_answer_array['imported_translation_array'] 	= $imported_translation_array;
			

			return $all_inside_links_answer_array;
		}
			
		public function clean_inside_links() {
			global $wpdb;
			
			$current_id 		= (isset($_GET['current_post_id'])) ? intval($_GET['current_post_id']) : 0;
			
			$result 			= array();
			$result['status'] 	= 'error';
			$result['result'] 	= '<br/>';
			
			$all_inside_links_answer_array = $this->all_inside_links();
			$everything_resolved 		= $all_inside_links_answer_array['everything_resolved'];
			$imported_translation_array = $all_inside_links_answer_array['imported_translation_array'];
			
			if(!$everything_resolved) {
				$everything_resolved_current = true;
				//foreach($imported_translations_rows as $imported_translation_row) {
					//$current_id 			= $imported_translation_row->post_id;
					if($current_id != 0) {
						$current_post_content 	= get_post_field('post_content', $current_id);
						$current_post_link 		= get_post_permalink($current_id, true);
						
						$result['result'] .= '<span style="color:black;">Analyse de l\'article ou de la page <a href="' . $current_post_link . '" target="_blank">' . $current_post_link . '</a>...</span><br/>';
						
						$doc = idnich_str_get_html($current_post_content);
						foreach($doc->find("a") as $inside_link) {
							$link_old_href = $inside_link->href;
							$link_old_token = $inside_link->{'data-token'};
							
							if($link_old_token != '') {
								if(isset($imported_translation_array[$link_old_token])) {
									$inside_link->href 			 = $imported_translation_array[$link_old_token];
									$inside_link->{'data-token'} = '';
									$result['result'] .= '<span style="color:green;">' . $link_old_href . ' => ' . $imported_translation_array[$link_old_token] . '</span><br/>';
								}
								else {
									$everything_resolved_current = false;
									$result['result'] .= '<span style="color:red;">' . $link_old_href . ' => <em>Aucune correspondance trouvée.</em></span><br/>';
								}
							}
						}

						$current_post_content = trim($doc->save());
					
						$current_post_update = array(
														'ID'           => $current_id,
														'post_content' => $current_post_content,
													);

						wp_update_post($current_post_update);
					}
				//}
				
				if($everything_resolved_current) {
					$wpdb->query("UPDATE {$wpdb->prefix}idnich_imported_translation SET resolved = 1 WHERE post_id = '" . $current_id . "'");
				}
			}
			
			if($everything_resolved) {
				$result['status'] 	= 'success';
				$wpdb->query("DELETE FROM {$wpdb->prefix}idnich_imported_translation");
			}
			
			echo json_encode($result);
			exit();
		}
		
		public function get_translations_count() {
			global $wpdb;

			$fields 							= array();
			
			$fields['import_id'] 				= (isset($_POST['import_id'])) 				? sanitize_text_field($_POST['import_id']) 	: '';
			$fields['from'] 					= (isset($_POST['from'])) 					? sanitize_text_field($_POST['from']) 		: '';
			$fields['to'] 						= (isset($_POST['to'])) 					? sanitize_text_field($_POST['to'])			: '';

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

			$import_translations_count_answer	= wp_remote_post(get_option("dnich_api_url") . 'import_counts', $args);
			$import_translations_count_answer	= json_decode($import_translations_count_answer['body']);

			$import_count_status				= array();
			$import_count_status['status']  	= $import_translations_count_answer->status;
			if($import_translations_count_answer->status === 'success') {
				$import_count_status['imported']= (int)$import_translations_count_answer->imported;
				$import_count_status['left'] 	= (int)$import_translations_count_answer->left;
			}
			else if($import_translations_count_answer->status === 'not_found') {
				$import_count_status['result']  = $import_translations_count_answer->result;
			}
			else {
				$import_count_status['result']  = $import_translations_count_answer->result;
			}
			
			echo json_encode($import_count_status);
			exit();
		}
		
		public function get_import_translation() {
			global $wpdb, $replace_table;
			
			$import_translation_as 				= (isset($_POST['import_translation_as'])) 	? sanitize_text_field($_POST['import_translation_as']) 	: 'auto';
			$import_images 						= (isset($_POST['import_images'])) 			? intval($_POST['import_images']) 						: 1;
			$import_external_links 				= (isset($_POST['import_external_links'])) 	? intval($_POST['import_external_links']) 				: 1;
			$import_and_publish 				= (isset($_POST['import_and_publish'])) 	? intval($_POST['import_and_publish']) 					: 1;
			
			if($import_translation_as != 'auto')
				if($import_translation_as != 'articles')
					if($import_translation_as != 'pages')
						$import_translation_as = 'auto';

			$fields 							= array();

			$fields['import_id'] 				= (isset($_POST['import_id'])) 				? sanitize_text_field($_POST['import_id']) 				: '';
			$fields['from'] 					= (isset($_POST['from'])) 					? sanitize_text_field($_POST['from']) 					: '';
			$fields['to'] 						= (isset($_POST['to'])) 					? sanitize_text_field($_POST['to'])						: '';

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

			$import_translation_answer = wp_remote_post(get_option("dnich_api_url") . 'import', $args);
			$import_translation_answer = json_decode($import_translation_answer['body']);
			
			$import_status			  = array();
			$import_status['status']  = $import_translation_answer->status;
			if($import_translation_answer->status === 'success') {
				
				$post_token = $import_translation_answer->post_token;
				
				$doc = idnich_str_get_html($import_translation_answer->translation);

				if(!$import_external_links) {
					foreach($doc->find("a") as $external_link) {
						$is_external_link = ($external_link->{'data-token'} != '') ? false : true;
						if($is_external_link) {
							$external_link->outertext = $external_link->innertext;
						}
					}
				}
				
				if(!$import_images) {
					$doc->removeNodes('img');
				}
				else {
					foreach($doc->find("img") as $inside_img) {
						$img_url 				= $inside_img->src;
						$img_alt 				= $inside_img->alt;
						$wordpress_upload_dir 	= wp_upload_dir();
						
						if($img_alt != '') {
							$img_alt			= mb_strtolower(str_replace(' ' , '_', $img_alt),'UTF-8');
							$img_alt			= str_replace(array_keys($replace_table), $replace_table, $img_alt);
						}
						else {
							$img_alt 			= time();
						}

						$extension 				= explode('.', $img_url);
						$extension				= ((isset($extension[ (count($extension) - 1) ])) ? $extension[ (count($extension) - 1) ] : '');

						$img_alt			   .= '.' . $extension;

						$new_image_path 		= $wordpress_upload_dir['path'] . '/' . $img_alt;
						try {
							if(!@copy($img_url, $new_image_path)) {
								throw new Exception();
							}
							else {
								list($width, $height, $type) = getimagesize($new_image_path);
								if($width != 0 && $height != 0 && $width != '' && $height != '') {
									$new_image_mime = mime_content_type($new_image_path);
									$upload_id = wp_insert_attachment( array(
										'guid'           => $new_image_path, 
										'post_mime_type' => $new_image_mime,
										'post_title'     => '',
										'post_content'   => '',
										'post_status'    => 'inherit'
									), $new_image_path );
									wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_image_path));
									$inside_img->src = wp_get_attachment_url($upload_id);
								}
								else {
									throw new Exception();
								}
							}
						} catch (Exception $e) {
							$inside_img->outertext = '';
						}
					}
				}
				$import_translation_answer->translation = trim($doc->save());
				
				$new_article_id = 0;
				if($import_translation_as != 'pages' && ($import_translation_as == 'articles' || ($import_translation_as == 'auto' && $import_translation_answer->category_id != 0))) {
					
					$category_id = 1;
					if($import_translation_answer->category_id != 0)
						$category_id = wp_create_category($import_translation_answer->category_name);
					
					$new_article = 	array(
											'post_title'    => mb_convert_encoding(wp_strip_all_tags($import_translation_answer->title), 'UTF-8', 'auto'),
											'post_content'  => mb_convert_encoding($import_translation_answer->translation, 'UTF-8', 'auto'),
											'post_status'   => (($import_and_publish) ? 'publish' : 'pending'),
											'post_author'   => 1,
											'post_category' => array($category_id),
											'meta_input'   => array(
												'description' => $import_translation_answer->meta,
											)
									);
					$new_article_id = wp_insert_post($new_article, true);
				}
				else if($import_translation_as != 'articles' && ($import_translation_as == 'pages' || ($import_translation_as == 'auto' && $import_translation_answer->category_id == 0))) {
					$new_article = 	array(
											'post_type'   	=> 'page',
											'post_title'    => mb_convert_encoding(wp_strip_all_tags($import_translation_answer->title), 'UTF-8', 'auto'),
											'post_content'  => mb_convert_encoding($import_translation_answer->translation, 'UTF-8', 'auto'),
											'post_status'   => (($import_and_publish) ? 'publish' : 'pending'),
											'post_author'   => 1,
											'meta_input'   => array(
												'description' => $import_translation_answer->meta,
											)
									);
					$new_article_id = wp_insert_post($new_article, true);
				}

				if($new_article_id != 0) {
					//set_post_thumbnail($new_article_id, $random_image_id);
				}
				update_post_meta($new_article_id, '_yoast_wpseo_metadesc', $import_translation_answer->meta);
				$data = array	(
									'post_id' 			=> $new_article_id, 
									'old_token' 		=> $post_token,
									'current_url' 		=> get_post_permalink($new_article_id, true)
								);
				$wpdb->insert("{$wpdb->prefix}idnich_imported_translation", $data);
				
				$import_status['result']  = get_post_permalink($new_article_id);
			}
			else if($import_translation_answer->status === 'not_found') {
				$import_status['result']  = $import_translation_answer->result;
			}
			else {
				$import_status['result']  = $import_translation_answer->result;
			}
			
			echo json_encode($import_status);
			exit();
		}

		public function import_translate_admin_menu() {
?>
			<div id="application_window">
				<div id="application_layout">
					<div id="dnich_import_translations_wrapper" style="padding:25px;position:relative;">
						<style>
							@keyframes rotate360spinner {
							to { transform: rotate(360deg); }
							}
							.translation_importation_loader_spin { animation: 2s rotate360spinner infinite linear; }
						</style>
						<div class="white_space_no_wrap" align="left">
							<div class="inline_box" align="center" style="color:black;padding:20px;width:220px;">
								<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/iDNich_logo.png'; ?>" width="180"/><br/>
								<h2 style="color:black;"><?php echo get_admin_page_title(); ?></h2>
							</div>
							<div class="inline_box" style="width:300px;">
								<h4><?php echo __('Importer la translation', 'idnich_translate'); ?></h4>
								<div style="margin-bottom:15px;">
									<select id="dniche_import_translation_as" title="<?php echo __('Importer les traductions comme...', 'idnich_translate'); ?>" style="width:200px;">
										<option value="auto">Auto</option>
										<option value="articles">Articles</option>
										<option value="pages">Pages</option>
									</select>
									<br/><br/>
									<input id="dniche_import_translation_images" name="dniche_import_translation_images" type="checkbox" value="1" checked>
									<label for="dniche_import_translation_images" style="font-size:0.8rem;vertical-align:unset;"><?php echo __('Importer les images', 'idnich_translate'); ?></label>
									<br/>
									<input id="dniche_import_translation_external_links" name="dniche_import_translation_external_links" type="checkbox" value="1" checked>
									<label for="dniche_import_translation_external_links" style="font-size:0.8rem;vertical-align:unset;"><?php echo __('Importer les liens externes', 'idnich_translate'); ?></label>
									<br/>
									<input id="dniche_import_translation_and_publish" name="dniche_import_translation_and_publish" type="checkbox" value="1" checked>
									<label for="dniche_import_translation_and_publish" style="font-size:0.8rem;vertical-align:unset;"><?php echo __('Publier l\'article une fois importé', 'idnich_translate'); ?></label>
								</div>
							</div>
							<div class="inline_box" align="right" style="width:calc(100% - 640px);">
								<div style="margin-right:20px;">
									<div class="inline_box white_space_no_wrap" align="right" style="width:230px;position:relative;top:-5px;">
										<div class="inline_box" id="translation_importation_loader_spin_loader" style="color:black;font-size:0.8rem;vertical-align:middle;padding:0px 15px;visibility:hidden;">
											<div class="inline_box" style="vertical-align:middle;">
												<button id="dniche_stop_translation_importation_button" class="button button-secondary" style="min-width:30px;height:15px;" title="<?php echo __('Stop Traduction Importation', 'idnich_translate'); ?>"><i class="far fa-stop-circle"></i></button>
											</div>
											<div class="inline_box default_font inline_box .white_selector_box" style="vertical-align:middle;margin-left:10px;">
												<i class="translation_importation_loader_spin fas fa-spinner fa-1x"></i>&nbsp;&nbsp;<?php echo __('Importation en cours', 'idnich_translate'); ?>
											</div>
										</div>
										&nbsp;&nbsp;
										<div id="dniche_translation_importation_count" class="default_font inline_box white_informations_box" style="vertical-align:middle;margin:0px;text-align:center;min-width:120px;visibility:hidden;">
											<i class="translation_importation_loader_spin fas fa-spinner fa-1x"></i>
										</div>
									</div>
								</div>
							</div>
							<br/>
							<div class="inline_box white_space_no_wrap" align="left" style="margin-left:220px;">
								<input title="<?php echo __('Slug à retrouver dans l\'onglet translation du plugin iDNich\'.<br/>Attention : sensible à la casse !'); ?>" placeholder="<?php echo __('Slug du site à importer. Ex : ', 'idnich_translate') . get_bloginfo('name'); ?>" id="dniche_import_id_translation" type="text" value=""/>
								<div class="inline_box" style="background-color:white !important;border:none !important;border-radius:10px !important;">
									<select id="dniche_import_translation_from" title="<?php echo __('Langue du site de départ selon la translation faite précédemment.'); ?>" style="width:100px;">
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
									<select id="dniche_import_translation_to" title="<?php echo __('Langue du site d\'arrivée selon la translation faite précédemment.'); ?>" style="width:100px;">
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
								<button id="dniche_import_translation_button" class="idnich-button"><?php echo __('Importer', 'idnich_translate'); ?></button>
								&nbsp;
								<button id="clean_inside_links" title="<?php echo __('Une fois l\'importation terminée, cliquez-ici pour optimiser votre maillage interne (comme sur votre site d\'origine)'); ?>" class="idnich-button" style="min-width:40px !important;"><i class="fas fa-link"></i></button>
								<button id="clean_inside_links_stop" title="<?php echo __('Stopper l\'optimisation du maillage interne.'); ?>" class="idnich-button" style="min-width:40px !important;display:none;"><i class="far fa-stop-circle"></i></button>
							</div>
						</div>
						<div id="translation_importation_logs" class="default_font white_space_no_wrap" style="overflow:auto;margin:20px 0px;margin-left:220px;height:460px;padding:10px;border:1px solid darkgray;border-left:3px solid rgb(251,115,39);font-style:italic;">
							
						</div>
					</div>
				
				</div>
			</div>
<?php
		}
	}
?>