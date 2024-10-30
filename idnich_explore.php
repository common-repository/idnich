<?php
	class iDNich_Explore {
		public function __construct() {			
			add_action('admin_menu', array($this, 'add_admin_menu'), 20);
			
			add_action('wp_ajax_explore_url', array($this, 'explore_url'));
		}

		public function add_admin_menu() {
			add_submenu_page('iDNich_Plugin', 'Explore URL', 'Explore URL', 'manage_options', 'idnich_plugin_explore', array($this, 'explore_admin_menu'));
		}
			
		public function explore_url() {
			global $wpdb;
			
			$fields 							= array();
			$fields['idnich_url'] 				= (isset($_POST['idnich_url'])) 		? sanitize_textarea_field($_POST['idnich_url']) 			: '';
			
			$headers   = array(
				'Authorization' => 'Bearer ' . get_option("dnich_api_token")
			);
			
			$args = array(
				'body'        => $fields,
				'timeout'     => '50000000',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $headers,
				'cookies'     => array(),
			);

			$explore_url_api_answer  = wp_remote_post(get_option("dnich_api_url") . 'explore', $args);
			$explore_url_api_answer	= $explore_url_api_answer['body'];
			
			echo $explore_url_api_answer;
			exit();
		}
		
		public function explore_admin_menu() {
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
						--><div id="idnich_explore_list" class="default_font" style="vertical-align:middle;padding:20px;background-color:white;border: 1px solid darkgray;border-left:3px solid rgb(251,115,39);">
							<!--<input id="generate_idnich_url" placeholder="URL du site" type="text" style="width:400px;" value="">&nbsp;&nbsp;
							<button id="generate_idnich_url_button" class="idnich-button">Inspecter l'URL</button>
							<div class="default_font" id="generate_idnich_result" style="text-align:left;margin:15px 0px;word-break:break-all;white-space:normal;">

							</div>-->
							Temporairement indisponible...
						</div>
					</div>
				</div>
			</div>
<?php
		}
	}
?>