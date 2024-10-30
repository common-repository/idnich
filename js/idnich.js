	var block_call 						= false;
	
	var auto_translation_launched 		= false;
	
	var import_counts_already_imported 	= 0;
	var import_counts_left 				= 0;
	var import_translation_launched 	= false;
	var clean_inside_links_launched 	= false;
	var all_inside_links_array 			= [];

	function information_dialog(dialog_title, dialog_data) {
		jQuery('<div style="padding:20px 35px !important;white-space:pre-line !important;"></div>').dialog({
			modal: true,
			height: 'auto',
			width: ((jQuery(window).width() > 768) ? (jQuery(window).width() * 0.5) :  '90%'),
			title: dialog_title,
			open: function() {
				jQuery(this).html('<span class="default_font">' + dialog_data + '</span>');
				jQuery('.ui-dialog-buttonpane').find('button:contains("OK")').removeClass('ui-button').addClass('no_style_button').html('<i class="far fa-check-circle fa-2x"></i>');
			},
			close: function() {
				jQuery(this).dialog('destroy');
			},
			buttons: {
				'OK': function() {
					jQuery(this).dialog('destroy');
				}
			}
		});
	}
	
	function escape_html(text) {
	  return jQuery("<textarea/>")
		.text(text)
		.html();
	}
	
	function unescape_html(text) {
	  return jQuery("<textarea/>")
		.html(text)
		.text();
	}
	
	function save_options_and_keep_list() {
		var form_data = new FormData();

		form_data.append('options' , jQuery('#generate_idnich_translation_options').val());
		form_data.append('keep_list' , jQuery('#generate_idnich_translation_keep_list').val());

		jQuery.ajax({
			url: ajaxurl + '?action=save_options_and_keep_list',
			type: 'POST',
			data: form_data,
			dataType: 'JSON',
			cache: false,
			contentType: false,
			processData: false,
			success: function(save_options_and_keep_list_data) {
				if(save_options_and_keep_list_data.status !== 'success') {
					console.log(save_options_and_keep_list_data.result);
				}
			}
		});
	}

	jQuery(document).ready(function() {
		
		jQuery.widget( "app.dialog", jQuery.ui.dialog, {
			options: {
				iconButtons: []
			},
			_create: function() {
				this._super();
				var $titlebar = this.uiDialog.find( ".ui-dialog-titlebar" );
				jQuery.each( this.options.iconButtons, function( i, v ) {
					var $button = jQuery("<button/>").text( this.text ),
						right = $titlebar.find( "[role='button']:last" )
										 .css( "right" );
					$button.button( { icons: { primary: this.icon }, text: false } )
						   .addClass( "ui-dialog-titlebar-close" )
						   .css( "right", ( parseInt( right ) + 22) + "px" )
						   .click( this.click )
						   .appendTo( $titlebar );
		
				});
			}
		});
		
		jQuery(function() {
			jQuery.ajaxSetup({ cache: false });
			
			jQuery.widget("ui.tooltip", jQuery.ui.tooltip, {
				 options: {
					 content: function () {
						 return jQuery(this).prop('title');
					 }
				 }
			 });
			
			jQuery(document).tooltip({
				position: {
					my: "center bottom-20",
					at: "center top",
					using: function(position, feedback) {
					  jQuery( this ).css( position );
					  jQuery( "<div>" )
						.addClass('tooltip_arrow')
						.addClass(feedback.vertical)
						.addClass(feedback.horizontal)
						.appendTo(this);
					}
				}
			});
		});
		
		// idnich manager ----------------------------------------------------------------------------------------------------- //
			jQuery(document).on('click', '#generate_idnich_url_button', function(e) {
				e.preventDefault();
				e.stopImmediatePropagation();
				
				if(!block_call) {
					block_call = true;
					jQuery('#loader').show();

					jQuery('#generate_idnich_result').html('<style>\
															@keyframes rotate360spinner {\
																to { transform: rotate(360deg); }\
															}\
															#generate_idnich_result_loader_spin { animation: 2s rotate360spinner infinite linear; }\
														</style>\
														<em><i id="generate_idnich_result_loader_spin" class="fas fa-spinner fa-1x"></i>&nbsp;&nbsp;Chargement en cours...</em>');
					
					var idnich_url = jQuery('#generate_idnich_url').val();

					var form_data = new FormData();
					form_data.append('idnich_url', idnich_url);

					jQuery.ajax({
						url: ajaxurl + '?action=explore_url',
						type: 'POST',
						dataType: 'JSON',
						data: form_data,
						cache: false,
						contentType: false,
						processData: false,
						success: function(dniche_result) {	
							if(dniche_result.status === "success") {
								jQuery('#generate_idnich_result').html(dniche_result.data);
							}
							else {
								jQuery('#generate_idnich_result').html('');
								information_dialog('Erreur', dniche_result.result + ' : Vous devez créer un compte iDNich\' avant de pouvoir utiliser cette fonctionnalité.');
							}
							
							jQuery('#loader').hide();
							block_call = false;
						},
						error: function(dniche_result) {
							jQuery('#generate_idnich_result').html('<em>Request timed out. Please try again.</em>');
							
							jQuery('#loader').hide();
							block_call = false;
						}
					});

				}
			});
		// -------------------------------------------------------------------------------------------------------------------- //
		
		// idnich manager ----------------------------------------------------------------------------------------------------- //
		jQuery(document).on('click', '#save_dnich_config', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call	  					= true;
				jQuery('body').append('<div id="loader"></div>');
				
				var current_selected_category 	= jQuery('#categories_list option:selected').val();
					current_selected_category 	= ((current_selected_category != 'None') ? current_selected_category : '');
				
				var form_data 					= new FormData();

				form_data.append('bad_host_browser_choice' 			, jQuery('[name="mash_browser"]:checked').val()									);

				jQuery.ajax({
					url: ajaxurl + '?action=save_project_infos',
					type: 'POST',
					data: form_data,
					dataType: 'JSON',
					cache: false,
					contentType: false,
					processData: false,
					success: function(save_dnich_config_data) {
						if(save_dnich_config_data.status === 'success') {
							information_dialog('Success', 'Configuration correctement sauvegardée.');
						}
						else if(save_dnich_config_data.status === 'pending') {
							information_dialog('Pending', 'Configuration correctement sauvegardée.<br/>Le serveur la mettra à jour dès qu\'il le pourra.');
						}
						else {
							information_dialog('Error', 'Impossible de sauvegarder la configuration.');
						}
						get_recent_logs();
						
						jQuery('#loader').remove();
						block_call = false;
					}
				});
			}
		});
		
		jQuery(document).on('click', '#order_new_credits_api_translation', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call	  					= true;
				var popup_reload = window.open('https://app.idnich.com/api_refund.php?api_type=translation&token=' + dnich_api_token, 'Reload', 'width=600,height=650,menubar=yes,location=yes,resizable=no,scrollbars=yes,status=yes')
				var timer = setInterval(function() { 
					if(popup_reload.closed) {
						clearInterval(timer);
						block_call = false;
						location.reload();
					}
				}, 1000);
			}
		});
		// inscription part
		jQuery(document).on('click', '#button_inscription', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!jQuery('#accept_conditions_checkbox').is(':checked')) {
				jQuery('.error_box').html('Vous devez reconnaître avoir lu et accepté les conditions générales de vente.');
				return;
			}
			
			jQuery('.error_box').html('');
			
			if(!block_call) {
				block_call = true;
				
				var form_data = new FormData(jQuery('#inscription_form')[0]);
				
				jQuery.ajax({
					url: ajaxurl + '?action=inscription',
					type: 'POST',
					dataType: 'JSON',
					data: form_data,
					cache: false,
					contentType: false,
					processData: false,
					success: function(inscription_data) {
						jQuery.get(ajaxurl + '?action=print_simple_captcha_from_ajax', function(simple_captcha) {
							jQuery('.recaptcha_box').html(simple_captcha);
						});
						
						if(inscription_data.status === "success" && inscription_data.result) {
							email = jQuery('#inscription_email').val();
							password = jQuery('#inscription_password').val();
																					
							jQuery('#inscription_output_form_inscription').css('position', 'relative');
							jQuery('#inscription_output_form_inscription').html('<div style="position: absolute;left: 0px;top: 0px;padding: 10px;font-size: 0.9rem;color: #118811;" class="default_font">\
																					<em>Inscription réussie.</em>\
																				</div>\
																				<div class="default_font" style="font-weight:bold;">\
																					Votre Token à reporter ci-dessus\
																				</div>\
																				<div style="margin:0 auto;width:80%;background-color:white;border:1px solid darkgray;height:40px;text-align: center;line-height: 40px;font-size: 0.9rem;" contenteditable>\
																					' + inscription_data.token + '\
																				</div>');
							
							jQuery('.error_box').html(inscription_data.result);
						}
						else {
							jQuery('.error_box').html(inscription_data.result);
						}

						block_call = false;
					}
				});
			}
		});
		// idnich manager end ------------------------------------------------------------------------------------------------- //
		
		
		// idnich translate --------------------------------------------------------------------------------------------------- //
		jQuery(document).on('click', '#generate_idnich_translation_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call = true;
				jQuery('body').append('<div id="loader"></div>');

				var dniche_translation_article_id 	= jQuery('#generate_idnich_translation_article').val();
				var dniche_translation_options 		= jQuery('#generate_idnich_translation_options').val();
				var dniche_translation_keep_list 	= jQuery('#generate_idnich_translation_keep_list').val();
				var dniche_translation_category_id	= jQuery('#generate_idnich_translation_category_id').val();
				var dniche_translation_category_name= jQuery('#generate_idnich_translation_category_name').val();
				var dniche_translation_from 		= jQuery('#generate_idnich_translation_from').val();
				var dniche_translation_to 			= jQuery('#generate_idnich_translation_to').val();
				var dniche_translation_title 		= jQuery('#generate_idnich_translation_title').val();
				var dniche_translation_meta 		= jQuery('#generate_idnich_translation_meta').val();
				var dniche_translation_text 		= jQuery('#generate_idnich_translation_text').html();

				var form_data = new FormData();
				form_data.append('options'			, dniche_translation_options);
				form_data.append('keep_list'		, dniche_translation_keep_list);
				form_data.append('category_id'		, dniche_translation_category_id);
				form_data.append('category_name'	, dniche_translation_category_name);
				form_data.append('from'				, dniche_translation_from);
				form_data.append('to'				, dniche_translation_to);
				form_data.append('title'			, dniche_translation_title);
				form_data.append('meta'				, dniche_translation_meta);
				form_data.append('text'				, unescape_html(dniche_translation_text));

				jQuery.ajax({
					url: ajaxurl + '?action=generate_translation&article_id=' + dniche_translation_article_id,
					type: 'POST',
					data: form_data,
					dataType: 'JSON',
					cache: false,
					contentType: false,
					processData: false,
					success: function(dniche_translationed_result) {
						if(dniche_translationed_result.status === "success") {
							jQuery('#generate_idnich_translation_result').html(dniche_translationed_result.translation);
							jQuery('#generate_idnich_translation_result').attr('contenteditable', 'true');

							jQuery('#generate_idnich_translation_category_id_result').val(dniche_translationed_result.translation_category_id);
							jQuery('#generate_idnich_translation_category_name_result').val(dniche_translationed_result.translation_category_name);
							jQuery('#generate_idnich_translation_title_result').val(dniche_translationed_result.translation_title);
							jQuery('#generate_idnich_translation_meta_result').val(dniche_translationed_result.translation_meta);
							
							jQuery('#translations_api_limit').html(	'<div class="default_font inline_box white_informations_box">\
																		Nombre de requêtes API translationion\
																	</div>\
																	<div class="default_font inline_box white_informations_box">\
																		' + dniche_translationed_result.current_count + '/' + dniche_translationed_result.api_limit + '\
																	</div>');
						}
						else {
							information_dialog('Erreur', dniche_translationed_result.result);
						}
						
						jQuery('#loader').remove();
						block_call = false;
					}
				});
			}
		});

		jQuery(document).on('click', '#generate_idnich_stop_auto_translation_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(auto_translation_launched) {
				auto_translation_launched 	= false;
				jQuery('body').append('<div id="loader"></div>');
				jQuery('#auto_translation_loader_spin_loader').css('visibility', 'hidden');
			}
		});
			
		jQuery(document).on('click', '#generate_idnich_auto_translation_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call 				= true;
				auto_translation_launched 	= true;
				
				if(auto_translation_launched) {

					jQuery('#auto_translation_loader_spin_loader').css('visibility', 'unset');
					
					var dniche_translation_article_id 	= jQuery('#generate_idnich_translation_article').val();
					
					if(dniche_translation_article_id === 0) {
						dniche_translation_article_id 	= jQuery('#generate_idnich_translation_article option:selected').next().attr('selected', 'selected');
					}
					
					var dniche_translation_options 		= jQuery('#generate_idnich_translation_options').val();
					var dniche_translation_from 			= jQuery('#generate_idnich_translation_from').val();
					var dniche_translation_to 			= jQuery('#generate_idnich_translation_to').val();
					
					jQuery.ajax({
						url: ajaxurl + '?action=get_article_content&article_id=' + dniche_translation_article_id + '&from=' + dniche_translation_from + '&to=' + dniche_translation_to,
						type: 'GET',
						dataType: 'JSON',
						success: function(translation_result) {
							if(translation_result.has_translation === 0) {
					
								jQuery('#generate_idnich_translation_result').html('');
								jQuery('#generate_idnich_translation_category_id_result').val('');
								jQuery('#generate_idnich_translation_category_name_result').val('');
								jQuery('#generate_idnich_translation_title_result').val('');
								jQuery('#generate_idnich_translation_meta_result').val('');
								
								jQuery('#generate_idnich_translation_category_id').val(translation_result.original_category_id);
								jQuery('#generate_idnich_translation_category_name').val(translation_result.original_category_name);
								jQuery('#generate_idnich_translation_title').val(translation_result.original_title);
								jQuery('#generate_idnich_translation_meta').val(translation_result.original_meta);
								jQuery('#generate_idnich_translation_text').html(translation_result.original_content);
								
								var dniche_translation_category_id 	= translation_result.original_category_id;
								var dniche_translation_category_name = translation_result.original_category_name;
								var dniche_translation_title 		= translation_result.original_title;
								var dniche_translation_meta 			= translation_result.original_meta;
								var dniche_translation_text 			= translation_result.original_content;

								var form_data = new FormData();
								form_data.append('options'			, dniche_translation_options);
								form_data.append('category_id'		, dniche_translation_category_id);
								form_data.append('category_name'	, dniche_translation_category_name);
								form_data.append('from'				, dniche_translation_from);
								form_data.append('to'				, dniche_translation_to);
								form_data.append('title'			, dniche_translation_title);
								form_data.append('meta'				, dniche_translation_meta);
								form_data.append('text'				, unescape_html(dniche_translation_text));

								jQuery.ajax({
									url: ajaxurl + '?action=generate_translation&article_id=' + dniche_translation_article_id,
									type: 'POST',
									data: form_data,
									dataType: 'JSON',
									cache: false,
									contentType: false,
									processData: false,
									success: function(dniche_translationed_result) {
										if(dniche_translationed_result.status === "success") {
											jQuery('#generate_idnich_translation_result').html(dniche_translationed_result.translation);
											jQuery('#generate_idnich_translation_result').attr('contenteditable', 'true');

											jQuery('#generate_idnich_translation_category_id_result').val(dniche_translationed_result.category_id);
											jQuery('#generate_idnich_translation_category_name_result').val(dniche_translationed_result.category_name);
											jQuery('#generate_idnich_translation_title_result').val(dniche_translationed_result.translation_title);
											jQuery('#generate_idnich_translation_meta_result').val(dniche_translationed_result.translation_meta);
											
											jQuery('#translations_api_limit').html(	'<div class="default_font inline_box white_informations_box">\
																						Nombre de requêtes API translationion\
																					</div>\
																					<div class="default_font inline_box white_informations_box">\
																						' + dniche_translationed_result.current_count + '/' + dniche_translationed_result.api_limit + '\
																					</div>');
										}
										else {
											information_dialog('Erreur', dniche_translationed_result.result);
											auto_translation_launched 	= false;
											jQuery('#auto_translation_loader_spin_loader').css('visibility', 'hidden');
										}
										
										block_call = false;
										jQuery('#loader').remove();
										
										jQuery('#generate_idnich_translation_article option:selected').next().attr('selected', 'selected');
										var next_selection = jQuery('#generate_idnich_translation_article option:selected').val();
										if(auto_translation_launched && next_selection != dniche_translation_article_id) {
											setTimeout(
												function() {
													jQuery('#generate_idnich_auto_translation_button').trigger('click');
												}, 300
											);
										}
										else {
											auto_translation_launched = false;
											jQuery('#auto_translation_loader_spin_loader').css('visibility', 'hidden');
										}
									}
								});
							}
							else {
								block_call = false;
								jQuery('#loader').remove();
								
								jQuery('#generate_idnich_translation_article option:selected').next().attr('selected', 'selected');
								var next_selection = jQuery('#generate_idnich_translation_article option:selected').val();
								if(auto_translation_launched && next_selection != dniche_translation_article_id) {
									setTimeout(
										function() {
											jQuery('#generate_idnich_auto_translation_button').trigger('click');
										}, 300
									);
								}
								else {
									auto_translation_launched = false;
									jQuery('#auto_translation_loader_spin_loader').css('visibility', 'hidden');
								}
							}
						}
					});
				}
			}
		});

		jQuery(document).on('change', '#generate_idnich_translation_article', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call = true;
				jQuery('body').append('<div id="loader"></div>');

				var dniche_translation_article_id 	= jQuery(this).val();
				var dniche_translation_from 		= jQuery('#generate_idnich_translation_from').val();
				var dniche_translation_to 			= jQuery('#generate_idnich_translation_to').val();

				jQuery.ajax({
					url: ajaxurl + '?action=get_article_content&article_id=' + dniche_translation_article_id + '&from=' + dniche_translation_from + '&to=' + dniche_translation_to,
					type: 'GET',
					dataType: 'JSON',
					success: function(dniche_article_content) {
						jQuery('#generate_idnich_translation_category_id').val(dniche_article_content.original_category_id);
						jQuery('#generate_idnich_translation_category_name').val(dniche_article_content.original_category_name);
						jQuery('#generate_idnich_translation_title').val(dniche_article_content.original_title);
						jQuery('#generate_idnich_translation_meta').val(dniche_article_content.original_meta);
						jQuery('#generate_idnich_translation_text').html(dniche_article_content.original_content);
						
						if(!jQuery('#text_to_translation_format_html').hasClass('white_selector_box_selected')) {
							jQuery('#text_to_translation_format_html').trigger('click');
						}
						
						jQuery('#generate_idnich_translation_category_id_result').val(dniche_article_content.translation_category_id);
						jQuery('#generate_idnich_translation_category_name_result').val(dniche_article_content.translation_category_name);
						jQuery('#generate_idnich_translation_title_result').val(dniche_article_content.translation_title);
						jQuery('#generate_idnich_translation_meta_result').val(dniche_article_content.translation_meta);
						jQuery('#generate_idnich_translation_result').html(dniche_article_content.translation_content);
						
						jQuery('#loader').remove();
						block_call = false;
					}
				});
			}
		});
		
		jQuery(document).on('change', '#generate_idnich_translation_from, #generate_idnich_translation_to', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call = true;
				jQuery('body').append('<div id="loader"></div>');

				var dniche_translation_article_id 	= jQuery('#generate_idnich_translation_article').val();
				var dniche_translation_from 		= jQuery('#generate_idnich_translation_from').val();
				var dniche_translation_to 			= jQuery('#generate_idnich_translation_to').val();

				jQuery.ajax({
					url: ajaxurl + '?action=get_translation_result_from_get&article_id=' + dniche_translation_article_id + '&from=' + dniche_translation_from + '&to=' + dniche_translation_to,
					type: 'GET',
					dataType: 'JSON',
					success: function(dniche_article_content) {
						jQuery('#generate_idnich_translation_category_id_result').val(dniche_article_content.category_id);
						jQuery('#generate_idnich_translation_category_name_result').val(dniche_article_content.category_name);
						jQuery('#generate_idnich_translation_title_result').val(dniche_article_content.translation_title);
						jQuery('#generate_idnich_translation_meta_result').val(dniche_article_content.translation_meta);
						jQuery('#generate_idnich_translation_result').html(dniche_article_content.translation_content);
						
						jQuery('#loader').remove();
						block_call = false;
					}
				});
			}
		});
			
		jQuery(document).on('click', '#text_to_translation_format_text, #text_to_translation_format_html', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!jQuery(this).hasClass('white_selector_box_selected')) {
				var is_format_text = (jQuery(this).attr('id') === 'text_to_translation_format_text') ? true : false;
				
				var current_format_text_content = jQuery('#generate_idnich_translation_text').html();
				
				jQuery(this).addClass('white_selector_box_selected');
				if(is_format_text) {
					jQuery('#text_to_translation_format_html').removeClass('white_selector_box_selected');
					jQuery('#generate_idnich_translation_text').html(escape_html(current_format_text_content));
				}
				else {
					jQuery('#text_to_translation_format_text').removeClass('white_selector_box_selected');
					jQuery('#generate_idnich_translation_text').html(unescape_html(current_format_text_content));
				}
			}
		});
		
		jQuery(document).on('click', '#text_translated_format_text, #text_translated_format_html', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!jQuery(this).hasClass('white_selector_box_selected')) {
				var is_format_text = (jQuery(this).attr('id') === 'text_translated_format_text') ? true : false;
				
				var current_format_text_content = jQuery('#generate_idnich_translation_result').html();
				
				jQuery(this).addClass('white_selector_box_selected');
				if(is_format_text) {
					jQuery('#text_translated_format_html').removeClass('white_selector_box_selected');
					jQuery('#generate_idnich_translation_result').html(escape_html(current_format_text_content));
				}
				else {
					jQuery('#text_translated_format_text').removeClass('white_selector_box_selected');
					jQuery('#generate_idnich_translation_result').html(unescape_html(current_format_text_content));
				}
			}
		});
		
		jQuery(document).on('click', '#generate_idnich_translation_meta_result_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			jQuery('#generate_idnich_translation_keep_list').hide();
			jQuery('#generate_idnich_translation_options').hide();
			jQuery('#generate_idnich_translation_meta').hide();
			
			if(jQuery('#generate_idnich_translation_meta_result').is(':visible')) {
				jQuery('#generate_idnich_translation_meta_result').hide();
			}
			else {
				jQuery('#generate_idnich_translation_meta_result').show();
			}
		});
		
		jQuery(document).on('click', '#generate_idnich_translation_meta_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			jQuery('#generate_idnich_translation_keep_list').hide();
			jQuery('#generate_idnich_translation_options').hide();
			jQuery('#generate_idnich_translation_meta_result').hide();
			
			if(jQuery('#generate_idnich_translation_meta').is(':visible')) {
				jQuery('#generate_idnich_translation_meta').hide();
			}
			else {
				jQuery('#generate_idnich_translation_meta').show();
			}
		});
		
		jQuery(document).on('click', '#generate_idnich_translation_options_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			jQuery('#generate_idnich_translation_keep_list').hide();
			jQuery('#generate_idnich_translation_meta').hide();
			jQuery('#generate_idnich_translation_meta_result').hide();
			
			if(jQuery('#generate_idnich_translation_options').is(':visible')) {
				save_options_and_keep_list();
				jQuery('#generate_idnich_translation_options').hide();
			}
			else {
				jQuery('#generate_idnich_translation_options').show();
				jQuery('#generate_idnich_translation_options').css('display', 'block');
			}
		});
		
		jQuery(document).on('click', '#generate_idnich_translation_keep_list_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			jQuery('#generate_idnich_translation_options').hide();
			jQuery('#generate_idnich_translation_meta').hide();
			jQuery('#generate_idnich_translation_meta_result').hide();
			
			if(jQuery('#generate_idnich_translation_keep_list').is(':visible')) {
				save_options_and_keep_list();
				jQuery('#generate_idnich_translation_keep_list').hide();
			}
			else {
				jQuery('#generate_idnich_translation_keep_list').show();
				jQuery('#generate_idnich_translation_keep_list').css('display', 'block');
			}
		});
		
		jQuery(document).on('click', 'select, .white_informations_box, .white_selector_box, .generate_idnich_translation_title, #generate_idnich_translation_button, #generate_idnich_auto_translation_button, #generate_idnich_translation_text, #generate_idnich_translation_result', function(e) {
			jQuery('#generate_idnich_translation_options').hide();
			jQuery('#generate_idnich_translation_keep_list').hide();
			jQuery('#generate_idnich_translation_meta').hide();
			jQuery('#generate_idnich_translation_meta_result').hide();
			
			save_options_and_keep_list();
		});
		
		// idnich translate end ---------------------------------------------------------------------------------------------------- //
		
		// idnich import translate ------------------------------------------------------------------------------------------------- //
		jQuery(document).on('click', '#clean_inside_links_stop', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(clean_inside_links_launched) {
				clean_inside_links_launched = false;
				jQuery('body').append('<div id="loader"></div>');
				jQuery('#clean_inside_links').show();
				jQuery('#clean_inside_links_stop').hide();
			}
			
		});
			
		jQuery(document).on('click', '#clean_inside_links', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();

			if(!block_call) {
				block_call 				= true;
				if(!clean_inside_links_launched) {
					clean_inside_links_launched = true;
					
					jQuery('body').append('<div id="loader"></div>');
					jQuery.ajax({
						url: ajaxurl + '?action=print_inside_links',
						type: 'GET',
						dataType: 'JSON',
						success: function(all_inside_links_result) {
							block_call = false;
							jQuery('#loader').remove();
							
							all_inside_links_array = all_inside_links_result.imported_translation_array;
							if(all_inside_links_result.everything_resolved === 'true') {
								clean_inside_links_launched = false;
							}
							else {
								setTimeout(
									function() {
										jQuery('#clean_inside_links').hide();
										jQuery('#clean_inside_links_stop').show();
										jQuery('#clean_inside_links').trigger('click');
									}, 300
								);
							}
						}
					});
				}
				else {
					var current_post_id = all_inside_links_array.pop();
					if(current_post_id) {
						jQuery.ajax({
							url: ajaxurl + '?action=clean_inside_links&current_post_id=' + current_post_id,
							type: 'GET',
							dataType: 'JSON',
							success: function(clean_inside_links_result) {
								if(clean_inside_links_result.status === "success") {
									jQuery('#translation_importation_logs').append(clean_inside_links_result.result + 'Tous les liens internes ont été résolus !');
								}
								else {
									jQuery('#translation_importation_logs').append(clean_inside_links_result.result);
									jQuery('#translation_importation_logs').scrollTop(jQuery('#translation_importation_logs')[0].scrollHeight);
									if(clean_inside_links_launched) {
										setTimeout(
											function() {
												jQuery('#clean_inside_links').trigger('click');
											}, 300
										);
									}
								}
								
								block_call = false;
								jQuery('#loader').remove();
							}
						});
					}
					else {
						jQuery('#translation_importation_logs').append('Analyse terminée.');
						jQuery('#translation_importation_logs').scrollTop(jQuery('#translation_importation_logs')[0].scrollHeight);
						
						jQuery('#clean_inside_links_stop').trigger('click');
						jQuery('#loader').remove();
					}
				}
			}
		});
		
		jQuery(document).on('click', '#dniche_stop_translation_importation_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(import_translation_launched) {
				import_translation_launched = false;
				jQuery('body').append('<div id="loader"></div>');
				jQuery('#translation_importation_loader_spin_loader').css('visibility', 'hidden');
			}
		});
		
		jQuery(document).on('click', '#dniche_import_translation_button', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call 				= true;
				
				var dniche_import_translation_as 	= jQuery('#dniche_import_translation_as').val();
				var dniche_import_images 			= (jQuery('#dniche_import_translation_images').is(':checked')) ? '1' : '0';
				var dniche_import_external_links 	= (jQuery('#dniche_import_translation_external_links').is(':checked')) ? '1' : '0';
				var dniche_import_and_publish 		= (jQuery('#dniche_import_translation_and_publish').is(':checked')) ? '1' : '0';
				var dniche_translation_import_id 	= jQuery('#dniche_import_id_translation').val();
				var dniche_translation_from 		= jQuery('#dniche_import_translation_from').val();
				var dniche_translation_to 			= jQuery('#dniche_import_translation_to').val();
				
				if(!import_translation_launched) {
					jQuery('#translation_importation_logs').append('Début de l\'importation ' + dniche_translation_from + '/' + dniche_translation_to + ' à partir de ' + dniche_translation_import_id + '.<br/>');
					jQuery('#dniche_translation_importation_count').css('visibility', 'unset');
					
					var form_data = new FormData();
					form_data.append('import_id'			, dniche_translation_import_id);
					form_data.append('from'					, dniche_translation_from);
					form_data.append('to'					, dniche_translation_to);

					jQuery.ajax({
						url: ajaxurl + '?action=get_translations_count',
						type: 'POST',
						data: form_data,
						dataType: 'JSON',
						cache: false,
						contentType: false,
						processData: false,
						success: function(translations_count) {
							if(translations_count.status === "success") {
								import_counts_already_imported 	= parseInt(translations_count.imported);
								import_counts_left 				= parseInt(translations_count.left);
								jQuery('#dniche_translation_importation_count').html('Importation ' + import_counts_already_imported + '/' + (import_counts_already_imported + import_counts_left));
							}
							else if(translations_count.status === "error") {
								information_dialog('Erreur', translations_count.result);
							}
							else {
								jQuery('#dniche_translation_importation_count').html('<em>Aucune translation à importer.</em>');
							}
						}
					});
				}
				import_translation_launched = true;
				
				if(import_translation_launched) {
					jQuery('#translation_importation_loader_spin_loader').css('visibility', 'unset');
					
					var form_data = new FormData();
					form_data.append('import_translation_as', dniche_import_translation_as);
					form_data.append('import_images'		, dniche_import_images);
					form_data.append('import_external_links', dniche_import_external_links);
					form_data.append('import_and_publish'	, dniche_import_and_publish);
					form_data.append('import_id'			, dniche_translation_import_id);
					form_data.append('from'					, dniche_translation_from);
					form_data.append('to'					, dniche_translation_to);

					jQuery.ajax({
						url: ajaxurl + '?action=get_import_translation',
						type: 'POST',
						data: form_data,
						dataType: 'JSON',
						cache: false,
						contentType: false,
						processData: false,
						success: function(dniche_imported_result) {
							block_call = false;
							
							if(dniche_imported_result.status === "success") {
								jQuery('#translation_importation_logs').append('<a href="' + dniche_imported_result.result + '" target="_blank">' + dniche_imported_result.result + '</a><br/>');
								import_counts_already_imported++;
								import_counts_left--;
								if(import_counts_already_imported <= (import_counts_already_imported + import_counts_left)) {
									jQuery('#dniche_translation_importation_count').html('Importation ' + import_counts_already_imported + '/' + (import_counts_already_imported + import_counts_left));
								}
								if(import_translation_launched) {
									setTimeout(
										function() {
											jQuery('#dniche_import_translation_button').trigger('click');
										}, 300
									);
								}
							}
							else if(dniche_imported_result.status === "error") {
								information_dialog('Erreur', dniche_imported_result.result);
								jQuery('#dniche_stop_translation_importation_button').trigger('click');
								jQuery('#translation_importation_logs').append('Importation impossible.');
							}
							else {
								jQuery('#dniche_stop_translation_importation_button').trigger('click');
								jQuery('#translation_importation_logs').append('Importation terminée.');
								
								information_dialog(import_translation_finished_title, import_translation_finished_content);
							}
							
							jQuery('#loader').remove();
						}
					});
				}
			}
		});
		// idnich import translate end ---------------------------------------------------------------------------------------- //

		// idnich translate --------------------------------------------------------------------------------------------------- //
		jQuery(document).on('click', '#close_idnich_visualize_translation_result', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			jQuery('#idnich_visualize_translations_list').show();
			jQuery('#idnich_visualize_translation_result').hide();
			
			jQuery('#idnich_visualize_translation_result_title').html('');
			jQuery('#idnich_visualize_translation_result_text').html('');
		});
			
		jQuery(document).on('click', '.consult_traduction', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call = true;
				jQuery('body').append('<div id="loader"></div>');

				var dniche_translation_article_id 	= jQuery(this).attr('data-id');
				var dniche_translation_from_to 		= jQuery(this).attr('data-from-to');

				jQuery.ajax({
					url: ajaxurl + '?action=consult_traduction&article_id=' + dniche_translation_article_id + '&from_to=' + dniche_translation_from_to,
					type: 'GET',
					dataType: 'JSON',
					success: function(dniche_article_content) {
						jQuery('#idnich_visualize_translations_list').hide();
						jQuery('#idnich_visualize_translation_result').show();
						
						jQuery('#idnich_visualize_translation_result_title').html('[' + dniche_article_content.category_name + '] ' + dniche_article_content.title);
						jQuery('#idnich_visualize_translation_result_text').html(dniche_article_content.text);
						
						jQuery('#loader').remove();
						block_call = false;
					}
				});
			}
		});
		
		jQuery(document).on('click', '.publish_traduction', function(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			
			if(!block_call) {
				block_call = true;
				jQuery('body').append('<div id="loader"></div>');

				var dniche_translation_article_id 	= jQuery(this).attr('data-id');
				var dniche_translation_from_to 		= jQuery(this).attr('data-from-to');

				jQuery.ajax({
					url: ajaxurl + '?action=publish_traduction&article_id=' + dniche_translation_article_id + '&from_to=' + dniche_translation_from_to,
					type: 'GET',
					dataType: 'JSON',
					success: function(publish_dniche_article) {
						information_dialog(publish_dniche_article.dialog_title, publish_dniche_article.dialog_content);
						
						jQuery('#loader').remove();
						block_call = false;
					}
				});
			}
		});
		// idnich translate end ----------------------------------------------------------------------------------------------- //

	});