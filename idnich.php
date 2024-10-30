<?php
/*
	Plugin Name: iDNich
	Description: iDNich' vous permet, à partir d'un seul site, de traduire tout ou partie de ses pages et articles, et de les implanter sur d'autres sites, pour tester de nouveaux marchés. Particulièrement attractif pour les sites de niches, le plugin propose sans cesse de nouvelles features pour les éditeurs.
	Version: 1.3.5
	Author: iDNich' Team
	License: GPL2
*/

	class iDNich_Plugin {
		public $api_controller;
		
		public function __construct() {
			include_once plugin_dir_path( __FILE__ ) . '/controllers/api_functions.php';
			include_once plugin_dir_path( __FILE__ ) . '/controllers/api_controller.php';

			include_once plugin_dir_path( __FILE__ ) . '/functions/simple_html_dom.php';
			
			include_once plugin_dir_path( __FILE__ ). '/idnich_explore.php';
			include_once plugin_dir_path( __FILE__ ). '/idnich_translate.php';
			include_once plugin_dir_path( __FILE__ ). '/idnich_visualize_translations.php';
			include_once plugin_dir_path( __FILE__ ). '/idnich_import_translations.php';
			new iDNich_Explore();
			new iDNich_Translate();
			new iDNich_Visualize_Translations();
			new iDNich_Import_Translations();
			
			$this->api_controller = new iDNich_Api_Controller();
			
			add_action('wp_ajax_inscription', array($this->api_controller, 'inscription'));
			add_action('wp_ajax_print_simple_captcha_from_ajax', array($this->api_controller, 'print_simple_captcha_from_ajax'));

			register_activation_hook(__FILE__, array('iDNich_Plugin', 'install'));
			register_deactivation_hook(__FILE__, array('iDNich_Plugin', 'uninstall'));
			register_uninstall_hook(__FILE__, array('iDNich_Plugin', 'uninstall'));
			
			add_action('admin_init', array($this, 'register_settings'));
			add_action('admin_menu', array($this, 'add_admin_menu'));
			add_action('admin_enqueue_scripts', array($this, 'idnich_enqueue_scripts'));
			
			global $wpdb;
			$has_resolved_column = $wpdb->query("SHOW COLUMNS FROM {$wpdb->prefix}idnich_imported_translation LIKE 'resolved'");
			$column_exists = ($wpdb->num_rows) ? TRUE : FALSE;
			
			if(!$column_exists) {
				$wpdb->query("ALTER TABLE {$wpdb->prefix}idnich_imported_translation ADD COLUMN `resolved` TINYINT(1) NOT NULL DEFAULT '0' AFTER `current_url`;");
			}
		}
		
		public function idnich_enqueue_scripts() {
			wp_register_style('idnich_fontawesome', 'https://use.fontawesome.com/releases/v5.7.1/css/all.css');
			wp_register_style('idnich_Hind_font', 'https://fonts.googleapis.com/css?family=Hind');
			wp_register_style('idnich_stylesheet', plugin_dir_url( __FILE__ ) . 'stylesheet.css', false, '1.3.5');
			wp_register_script('idnich_js', plugin_dir_url( __FILE__ ) . 'js/idnich.js', false, '1.3.5');
			
			wp_enqueue_style('wp-jquery-ui-dialog');
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-tooltip');
			wp_enqueue_script('jquery-ui-button');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-effects-bounce');

			wp_enqueue_style('idnich_fontawesome');
			wp_enqueue_style('idnich_Hind_font');
			wp_enqueue_style('idnich_stylesheet');
			wp_enqueue_script('idnich_js');

			$script  = 'var dnich_api_token = "' . get_option("dnich_api_token") . '";';
			$script .= 'var import_translation_finished_title = "' . __('Importation Terminée !') . '";';
			$script .= 'var import_translation_finished_content = "' . __('Importation finie, vous pouvez consulter les articles ou <b>réparer les liens internes</b>, comme sur le site d\'origine') . '";';
			wp_add_inline_script('idnich_js', $script, 'before');
		}
		
		public function register_settings() {
			register_setting('dnich_core', 'dnich_api_url');
			register_setting('dnich_settings', 'dnich_api_token');
			register_setting('dnich_settings', 'dnich_keep_list');
			register_setting('dnich_settings', 'dnich_options');
		}

		public static function install() {
			global $wpdb;
			
			update_option('dnich_api_url', 'https://app.idnich.com/v1/');
			update_option('dnich_api_token', '');
			update_option('dnich_keep_list', '');
			update_option('dnich_options', '');

			$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}idnich_translation 	(
																							post_id INT(11) NOT NULL,
																							translation_category_id INT(11) NOT NULL DEFAULT 0,
																							translation_category_name VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
																							translation_from_to VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
																							translation_title VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
																							translation_meta VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
																							translation LONGTEXT CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
																							UNIQUE KEY `post_id` (`post_id`,`translation_from_to`)
																						);");
																						
			$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}idnich_imported_translation (
																									post_id INT(11) NOT NULL,
																									old_token VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
																									current_url VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
																									resolved TINYINT(1) NOT NULL DEFAULT '0',
																									PRIMARY KEY (post_id)
																								);");
		}
		
		public static function uninstall() {
			global $wpdb;

			delete_option('dnich_api_url');
			delete_option('dnich_api_token');
			
			$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}idnich_translation;");
			$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}idnich_imported_translation;");
		}

		public function add_admin_menu() {
			add_menu_page('iDNich', 'iDNich', 'manage_options', 'iDNich_Plugin', array($this, 'dnich_admin_menu'), 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(plugin_dir_path( __FILE__ ) . '/images/dnich_icon.svg')));
			add_submenu_page('iDNich_Plugin', 'Manage', 'Manage', 'manage_options', 'iDNich_Plugin', array($this, 'dnich_admin_menu'));
		}

		public function dnich_admin_menu() {
?>
			<div id="application_window">
				<div id="application_layout">
				
					<div class="white_space_no_wrap" width="100%"><!--
						--><div class="inline_box" style="width:200px;">
							<div align="left" style="color:black;padding:20px;vertical-align:middle;">
								<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/iDNich_logo.png'; ?>" width="180"/><br/>
								<h2 style="color:black;margin-left:55px;"><?php echo get_admin_page_title(); ?></h2>
							</div>
						</div><!--
						--><div class="inline_box default_font" style="width:calc(100% - 200px);vertical-align:middle;">
							<div style="text-align:center;padding:20px;font-weight:bold;font-size:1.3rem">
								<i class="fas fa-unlock" style="color:rgb(251,115,39);"></i>&nbsp;&nbsp;Activation du plugin
							</div>
							<div style="width:80%;margin:0 auto;" align="left">
								<form method="post" action="options.php">
<?php 
									settings_errors();
									settings_fields("dnich_settings");
									do_settings_sections("dnich_settings");
?>
									<div class="form-content" style="padding:30px;margin:15px;border: 1px solid darkgray;border-left: 3px solid rgb(251,115,39);background-color:white;white-space:normal;">
										<div class="star star-full" aria-hidden="true"></div>
										<div class="star star-full" aria-hidden="true"></div>
										<div class="star star-full" aria-hidden="true"></div>
										<div class="star star-full" aria-hidden="true"></div>
										<div class="star star-full" aria-hidden="true"></div>
<?php 
										echo __('Le plugin vous plaît ? Notez-le 
												<a href="https://wordpress.org/support/plugin/idnich/reviews/#new-post" target="_blank"><i class="fas fa-star fa-xs" style="color:#FF8C00;"></i><i class="fas fa-star fa-xs" style="color:#FF8C00;"></i><i class="fas fa-star fa-xs" style="color:#FF8C00;"></i><i class="fas fa-star fa-xs" style="color:#FF8C00;"></i><i class="fas fa-star fa-xs" style="color:#FF8C00;"></i></a>, 
												parlez-en autour de vous, et 
												récupérez <b>10% sur les ventes</b> ! 
												Intéressé(e)s ? Inscrivez-vous gratuitement
												à notre <a href="https://ad.idnich.com/" target="_blank"><b>
												programme affilié</b></a> !', 'idnich_translate');
?>
									</div>
									<div class="form-content" style="padding:30px;margin:15px;border: 1px solid darkgray;border-left: 3px solid rgb(251,115,39);background-color:white;">
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
											$credits_left_translation = ((int)$api_limits->api_limit - (int)$api_limits->current_count);
											echo 	'<div class="default_font inline_box white_informations_box">
														Crédits restants API Traduction
													</div>
													<div class="default_font inline_box white_informations_box">
														' . $credits_left_translation  . '
													</div>';
											echo 	'<div class="default_font inline_box" style="vertical-align:middle;padding:3px 20px;margin: 15px 0px;">
														<button id="order_new_credits_api_translation" class="idnich-button">' . __('Recharger', 'idnich_translate') . '</button>
														<a class="inline_box idnich-button" style="text-align:center;line-height:30px;min-width:40px;" target="_blank" title="' . __('Si la popup ne s\'ouvre pas avec le bouton recharger, cliquez ici pour ouvrir directement la fenêtre dans un nouvel onglet.', 'idnich_translate') . '" href="https://app.idnich.com/api_refund.php?api_type=translation&token=' . get_option("dnich_api_token") . '"><i class="fas fa-sync-alt"></i></a>
													</div>';
										}
										else if($api_limits->status === 'pending') {
											echo 	'<div class="default_font inline_box white_informations_box">
														Crédits restants API Traduction
													</div>
													<div class="default_font inline_box white_informations_box">
														' . '0'  . '
													</div>';
											echo 	'<div class="default_font inline_box" style="vertical-align:middle;padding:3px 20px;margin: 15px 0px;">
														<button id="order_new_credits_api_translation" class="idnich-button">' . __('Recharger', 'idnich_translate') . '</button>
														<a class="inline_box idnich-button" style="text-align:center;line-height:30px;min-width:40px;" target="_blank" title="' . __('Si la popup ne s\'ouvre pas avec le bouton recharger, cliquez ici pour ouvrir directement la fenêtre dans un nouvel onglet.', 'idnich_translate') . '" href="https://app.idnich.com/api_refund.php?api_type=translation&token=' . get_option("dnich_api_token") . '"><i class="fas fa-sync-alt"></i></a>
													</div>';
										}
										else {
											echo 	'<div class="default_font inline_box white_informations_box">
														<em>
															Une erreur s\'est produite : ' . $api_limits->result . '.
														</em>
													</div>';
										}
										
										echo '<hr/>';
									}
?>
										<div class="textbox">
											<label for="dnich_api_token"><b><?php echo __('API Token', 'idnich_translate'); ?></b></label>
											<p style="margin-top:15px;">
												<input class="p-input" type="text" name="dnich_api_token" style="border-bottom:1px solid darkgray;height:30px;" value="<?php echo get_option("dnich_api_token"); ?>" placeholder="<?php echo __('Token', 'idnich_translate'); ?>" />
											</p>
										</div>
										<div style="padding:20px 0px;">
											<button class="idnich-button"><?php echo __('Enregistrer les modifications', 'idnich_translate'); ?></button>
										</div>
									</div>
									
								</form>
<?php
								if(get_option("dnich_api_token") == '') {
									echo '<h4>' . __('Si vous n\'avez pas encore de compte <span style="color:rgb(251,115,39);">iDNich\'</span>, vous pouvez en créer un dès maintenant :') . '</h4>';
?>
									<div id="inscription_output_box" align="center">
									
										<div align="center" style="display:inline-block;text-align:center;padding:40px;margin:15px;max-width:650px;vertical-align:middle;background-color:white;border:1px solid darkgray;">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '/images/iDNich_logo.png'; ?>" width="280">
											<br/><br/>
											<div class="default_font">
												L'outil complet pour tout <br/><b>dénicher facilement</b>
											</div>
											<br/>
											<div class="default_font">
												<b>Avec <span style="color:rgb(251,115,39);">iDNich'</span>, on déniche tout !</b>
											</div>
										</div>
									
										<div id="inscription_output_form_inscription" align="center">
											<form enctype="multipart/form-data" method="post" id="inscription_form">
												<div class="error_box" align="center">
												</div>
												<div align="center" style="margin:10px 0px;">
													<div class="inline_box" align="left" style="width:300px;">
														<div style="margin:5px 0px;">
															<input placeholder="Adresse mail" class="inscription_form_element" type="text" id="inscription_email_address" name="email_address">
														</div>
														<div style="margin:5px 0px;">
															<input placeholder="Mot de passe" class="inscription_form_element" type="password" id="inscription_password" name="password"><br/>
														</div>
														<div style="margin:5px 0px;">
															<input placeholder="Mot de passe confirmation" class="inscription_form_element" type="password" id="inscription_password_confirmation" name="confirmation">
														</div>
													</div>
													<div class="inline_box" align="left" style="width:320px;white-space:normal;">
														<div class="inscription_checkboxes">
															<input type="checkbox" id="accept_newsletter_checkbox" name="accept_newsletter_checkbox" value="1">
															&nbsp;
															<label for="accept_newsletter_checkbox" class="default_font">
																Je m'abonne à la newsletter.
															</label>
														</div>
														<div class="inscription_checkboxes">
															<input type="checkbox" id="accept_conditions_checkbox" name="accept_conditions_checkbox" value="1">
															&nbsp;
															<label for="accept_conditions_checkbox" class="default_font">
																Je reconnais avoir lu et accepté les 
																<a href="https://idnich.com/confidentiality/" target="_blank">
																	Conditions générales de vente
																	et déclaration de confidentialité
																</a>.
															</label>
														</div>
													</div>
												</div>
												<div class="recaptcha_box" align="center">
<?php
													$this->api_controller->print_simple_captcha();
?>
												</div>
												<div class="buttons_box" align="left">
													<button class="idnich-button" id="button_inscription">S'inscrire</button>
												</div>
											</form>
										</div>
										
									</div>
<?php
								}
?>
							</div>
						</div><!--
					--></div>	
				</div>
			</div>
<?php
		}
	}

	new iDNich_Plugin();
?>