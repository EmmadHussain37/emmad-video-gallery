/**
 * Admin JavaScript for Emmad Video Gallery.
 *
 * @package Emmad_Video_Gallery
 */

jQuery(document).ready(function ($) {
	'use strict';

	// Handle logo upload media modal via event delegation.
	$(document).on('click', '#emmaviga_upload_logo', function (e) {
		e.preventDefault();

		if (typeof wp === 'undefined' || !wp.media) {
			alert('WordPress Media library could not be loaded.');
			return;
		}

		var adminData  = window.emmavigaAdminData || {};
		var titleText  = adminData.mediaTitle || 'Select Player Logo';
		var buttonText = adminData.mediaButton || 'Use Selected Logo';

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
				$('#emmaviga_player_logo').val(attachment.url).trigger('change');
				$('#emmaviga_logo_preview').attr('src', attachment.url);
				$('#emmaviga_logo_preview_wrap').show();
				$('#emmaviga_remove_logo').show();
			}
		});

		frame.open();
	});

	// Handle logo removal via event delegation.
	$(document).on('click', '#emmaviga_remove_logo', function (e) {
		e.preventDefault();
		$('#emmaviga_player_logo').val('').trigger('change');
		$('#emmaviga_logo_preview_wrap').hide();
		$('#emmaviga_logo_preview').attr('src', '');
		$(this).hide();
	});

	// Handle manual input in logo URL field.
	$(document).on('input change', '#emmaviga_player_logo', function () {
		var val = $.trim($(this).val());
		if (val) {
			$('#emmaviga_remove_logo').show();
			$('#emmaviga_logo_preview').attr('src', val);
			$('#emmaviga_logo_preview_wrap').show();
		} else {
			$('#emmaviga_remove_logo').hide();
			$('#emmaviga_logo_preview_wrap').hide();
			$('#emmaviga_logo_preview').attr('src', '');
		}
	});
});
