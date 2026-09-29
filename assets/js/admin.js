/**
 * Admin JavaScript for Emmad Video Gallery.
 *
 * @package Emmad_Video_Gallery
 */

jQuery(document).ready(function ($) {
	'use strict';

	var fileFrame;

	// Media upload handler.
	$(document).on('click', '#emmad_upload_logo, #emmad_vg_upload_logo, #ehvg-upload-logo, #vg-upload-logo', function (e) {
		e.preventDefault();

		if (typeof wp === 'undefined' || !wp.media) {
			return;
		}

		if (fileFrame) {
			fileFrame.open();
			return;
		}

		var i18n = window.emmadAdminData || {
			mediaTitle: 'Select Player Logo',
			mediaButton: 'Use Selected Logo'
		};

		fileFrame = wp.media({
			title: i18n.mediaTitle,
			button: {
				text: i18n.mediaButton
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});

		fileFrame.on('select', function () {
			var attachment = fileFrame.state().get('selection').first().toJSON();

			if (attachment && attachment.url) {
				$('#emmad_player_logo, #emmad_vg_player_logo, #ehvg_player_logo, #vg_player_logo').val(attachment.url);

				var $preview = $('#emmad_logo_preview, #emmad_vg_logo_preview');
				if ($preview.length) {
					$preview.attr('src', attachment.url);
				}

				$('#emmad_logo_preview_wrap, #emmad_vg_logo_preview_wrap').show();
				$('#emmad_remove_logo, #emmad_vg_remove_logo').show();
			}
		});

		fileFrame.open();
	});

	// Remove logo handler.
	$(document).on('click', '#emmad_remove_logo, #emmad_vg_remove_logo', function (e) {
		e.preventDefault();
		$('#emmad_player_logo, #emmad_vg_player_logo, #ehvg_player_logo, #vg_player_logo').val('');
		$('#emmad_logo_preview_wrap, #emmad_vg_logo_preview_wrap').hide();
		$('#emmad_logo_preview, #emmad_vg_logo_preview').attr('src', '');
		$(this).hide();
	});
});
