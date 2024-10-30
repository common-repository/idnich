<?php
	class iDNich_Api_Controller {
		public function __construct() {
			
		}

		function inscription() {
			
			$captcha 					= array();

			if(isset($_POST['answer_cap']) && isset($_POST['answser_code']) && dnich_api_not_null($_POST['answer_cap']) && dnich_api_not_null($_POST['answser_code'])) {
				$fields												= array();
				$fields['accept_conditions_checkbox'] 				= (isset($_POST['accept_conditions_checkbox'])) 	? intval($_POST['accept_conditions_checkbox']) 					: false;
				$fields['accept_newsletter_checkbox'] 				= (isset($_POST['accept_newsletter_checkbox'])) 	? intval($_POST['accept_newsletter_checkbox'])					: '0';
				$fields['email_address'] 							= (isset($_POST['email_address'])) 					? sanitize_text_field($_POST['email_address']) 					: '';
				$fields['password'] 	 							= (isset($_POST['password'])) 						? sanitize_text_field($_POST['password']) 						: '';
				$fields['confirmation'] 							= (isset($_POST['confirmation'])) 					? sanitize_text_field($_POST['confirmation'])					: '';
				
				$fields['answer_cap'] 								= (isset($_POST['answer_cap'])) 					? sanitize_text_field($_POST['answer_cap']) 					: '';
				$fields['answser_code'] 							= (isset($_POST['answser_code'])) 					? sanitize_text_field($_POST['answser_code']) 					: '';

				$args = array(
					'body'        => $fields,
					'timeout'     => '500',
					'redirection' => '5',
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'cookies'     => array(),
				);

				$inscription_output = wp_remote_post('https://app.idnich.com/client/client_controller.php?action=inscription', $args);
				echo $inscription_output['body'];
			}
			else {
				$result_array = array();
				$result_array['status'] = 'error';
				$result_array['result'] = 'Captcha Invalid.';
				echo json_encode($result_array);
			}
			exit();
		}
		
		function print_simple_captcha_from_ajax() {
			$this->print_simple_captcha(true);
		}
		
		function print_simple_captcha($from_ajax = false) {
			$args = array(
				'body'        => array(),
				'timeout'     => '500',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(),
				'cookies'     => array(),
			);

			$api_captcha_challenge = wp_remote_post('https://app.idnich.com/api_get_challenge.php', $args);
			$api_captcha_challenge = json_decode($api_captcha_challenge['body']);
			echo  	"<p>
						<label for=\"answer_cap\">" . $api_captcha_challenge->question . "</u></label><br/>
						<input class=\"inscription_form_element\" type=\"text\" name=\"answer_cap\" value=\"\" /><br/>
						<input name=\"answser_code\" type=\"hidden\" value=\"" . $api_captcha_challenge->hash  ."\" />
					</p>";
					
			if($from_ajax) {
				exit();
			}
		}
	}
?>