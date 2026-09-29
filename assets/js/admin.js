/**
 * Admin JavaScript for Emmad Video Gallery.
 *
 * @package Emmad_Video_Gallery
 */

jQuery(document).ready(function ($) {
	'use strict';

	// Cache selectors.
	var $inputField   = $('#emmad_vg_player_logo, #ehvg_player_logo, #vg_player_logo');
	var $uploadBtn    = $('#emmad_vg_upload_logo, #ehvg-upload-logo, #vg-upload-logo');
	var $removeBtn    = $('#emmad_vg_remove_logo');
	var $previewWrap  = $('#emmad_vg_logo_preview_wrap');
	var $previewImg   = $('#emmad_vg_logo_preview');

	if (!$uploadBtn.length) {
		return;
	}

	$uploadBtn.on('click', function (e) {
		e.preventDefault();

		var frame = wp.media({
			title: 'Select Player Logo',
			button: {
				text: 'Use Selected Logo'
			},
			multiple: false,
			library: {
				type: 'image'
			}
		});

		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();

			if (attachment && attachment.url) {
				$inputField.val(attachment.url);

				if ($previewImg.length) {
					$previewImg.attr('src', attachment.url);
					$previewWrap.show();
					$removeBtn.show();
				}
			}
		});

		frame.open();
	});

	$removeBtn.on('click', function (e) {
		e.preventDefault();
		$inputField.val('');
		$previewWrap.hide();
		$previewImg.attr('src', '');
		$(this).hide();
	});
});
