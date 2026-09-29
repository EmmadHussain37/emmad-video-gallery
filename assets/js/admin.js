/**
 * Admin JavaScript for Emmad Video Gallery.
 *
 * @package Emmad_Video_Gallery
 */

jQuery(document).ready(function ($) {
	'use strict';

	// Handle logo upload media modal via event delegation.
	$(document).on('click', '#emmad_upload_logo, #emmad_vg_upload_logo, #ehvg-upload-logo, #vg-upload-logo', function (e) {
		e.preventDefault();

		if (typeof wp === 'undefined' || !wp.media) {
			alert('WordPress Media library could not be loaded.');
			return;
		}

		var titleText  = (window.emmadAdminData && window.emmadAdminData.mediaTitle) ? window.emmadAdminData.mediaTitle : 'Select Player Logo';
		var buttonText = (window.emmadAdminData && window.emmadAdminData.mediaButton) ? window.emmadAdminData.mediaButton : 'Use Selected Logo';

		var frame = wp.media({
			title: titleText,
			button: {
				text: buttonText
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});

		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();

			if (attachment && attachment.url) {
				$('#emmad_player_logo, #emmad_vg_player_logo, #ehvg_player_logo, #vg_player_logo').val(attachment.url).trigger('change');
				$('#emmad_logo_preview, #emmad_vg_logo_preview').attr('src', attachment.url);
				$('#emmad_logo_preview_wrap, #emmad_vg_logo_preview_wrap').show();
				$('#emmad_remove_logo, #emmad_vg_remove_logo').show();
			}
		});

		frame.open();
	});

	// Handle logo removal via event delegation.
	$(document).on('click', '#emmad_remove_logo, #emmad_vg_remove_logo', function (e) {
		e.preventDefault();
		$('#emmad_player_logo, #emmad_vg_player_logo, #ehvg_player_logo, #vg_player_logo').val('').trigger('change');
		$('#emmad_logo_preview_wrap, #emmad_vg_logo_preview_wrap').hide();
		$('#emmad_logo_preview, #emmad_vg_logo_preview').attr('src', '');
		$(this).hide();
	});

	// Handle manual input in logo URL field.
	$(document).on('input change', '#emmad_player_logo, #emmad_vg_player_logo', function () {
		var val = $.trim($(this).val());
		if (val) {
			$('#emmad_remove_logo, #emmad_vg_remove_logo').show();
			$('#emmad_logo_preview, #emmad_vg_logo_preview').attr('src', val);
			$('#emmad_logo_preview_wrap, #emmad_vg_logo_preview_wrap').show();
		} else {
			$('#emmad_remove_logo, #emmad_vg_remove_logo').hide();
			$('#emmad_logo_preview_wrap, #emmad_vg_logo_preview_wrap').hide();
			$('#emmad_logo_preview, #emmad_vg_logo_preview').attr('src', '');
		}
	});
});
