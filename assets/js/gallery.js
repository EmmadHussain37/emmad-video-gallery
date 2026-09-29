/**
 * Frontend Video Gallery & Fullscreen Player.
 *
 * @package Emmad_Video_Gallery
 */

(function () {
	'use strict';

	function initGallery() {
		var overlay = document.getElementById('vg-player');
		if (!overlay) {
			return;
		}

		var video         = document.getElementById('vg-video-player');
		var iframe        = document.getElementById('vg-iframe-player');
		var controls      = overlay.querySelector('.vg-controls');
		var playBtn       = document.getElementById('vg-play');
		var progress      = document.getElementById('vg-progress');
		var progressFill  = document.getElementById('vg-progress-fill');
		var progressThumb = document.getElementById('vg-progress-thumb');
		var current       = document.getElementById('vg-current');
		var duration      = document.getElementById('vg-duration');
		var muteBtn       = document.getElementById('vg-mute');
		var title         = document.getElementById('vg-title');
		var closeBtn      = overlay.querySelector('.vg-close');

		overlay.style.display = 'none';

		var currentPlayer = 'video';
		var dragging      = false;

		/*
		=====================================
		HELPERS
		=====================================
		*/

		function isYouTube(url) {
			return /(youtube\.com|youtu\.be)/i.test(url);
		}

		function isVimeo(url) {
			return /vimeo\.com/i.test(url);
		}

		function youtubeEmbed(url) {
			var id = '';

			if (url.indexOf('youtu.be/') !== -1) {
				id = url.split('youtu.be/')[1].split('?')[0].split('#')[0];
			} else if (url.indexOf('youtube.com/shorts/') !== -1) {
				id = url.split('youtube.com/shorts/')[1].split('?')[0].split('#')[0];
			} else if (url.indexOf('youtube.com/embed/') !== -1) {
				id = url.split('youtube.com/embed/')[1].split('?')[0].split('#')[0];
			} else {
				var match = url.match(/[?&]v=([^&#]+)/);
				if (match && match[1]) {
					id = match[1];
				}
			}

			return id ? 'https://www.youtube.com/embed/' + encodeURIComponent(id) + '?autoplay=1&rel=0' : '';
		}

		function vimeoEmbed(url) {
			if (url.indexOf('player.vimeo.com/video/') !== -1) {
				return url + (url.indexOf('?') !== -1 ? '&' : '?') + 'autoplay=1';
			}

			var match = url.match(/vimeo\.com\/(\d+)/);
			if (match && match[1]) {
				return 'https://player.vimeo.com/video/' + encodeURIComponent(match[1]) + '?autoplay=1';
			}

			return '';
		}

		function formatTime(seconds) {
			if (isNaN(seconds) || seconds < 0) {
				return '00:00';
			}

			var totalSecs = Math.floor(seconds);
			var mins      = Math.floor(totalSecs / 60);
			var secs      = totalSecs % 60;

			var minsStr = (mins < 10 ? '0' : '') + mins;
			var secsStr = (secs < 10 ? '0' : '') + secs;

			return minsStr + ':' + secsStr;
		}

		/*
		=====================================
		OPEN PLAYER
		=====================================
		*/

		function openPlayer(item) {
			var videoURL   = item.getAttribute('data-video');
			var videoTitle = item.getAttribute('data-title') || '';

			if (!videoURL) {
				return;
			}

			if (title) {
				title.textContent = videoTitle;
			}

			overlay.style.display = 'flex';
			document.body.style.overflow = 'hidden';

			if (isYouTube(videoURL)) {
				currentPlayer = 'youtube';

				if (video) {
					video.pause();
					video.removeAttribute('src');
					video.style.display = 'none';
				}

				if (iframe) {
					iframe.src = youtubeEmbed(videoURL);
					iframe.style.display = 'block';
				}

				if (controls) {
					controls.style.display = 'none';
				}
			} else if (isVimeo(videoURL)) {
				currentPlayer = 'vimeo';

				if (video) {
					video.pause();
					video.removeAttribute('src');
					video.style.display = 'none';
				}

				if (iframe) {
					iframe.src = vimeoEmbed(videoURL);
					iframe.style.display = 'block';
				}

				if (controls) {
					controls.style.display = 'none';
				}
			} else {
				currentPlayer = 'video';

				if (iframe) {
					iframe.src = '';
					iframe.style.display = 'none';
				}

				if (video) {
					video.style.display = 'block';
					video.src = videoURL;

					setTimeout(function () {
						var playPromise = video.play();
						if (playPromise !== undefined) {
							playPromise.catch(function () {
								// Browser policy prevented autoplay.
							});
						}
					}, 50);
				}

				if (controls) {
					controls.style.display = '';
				}
			}

			setTimeout(function () {
				overlay.classList.add('active');
			}, 50);
		}

		document.querySelectorAll('.vg-video').forEach(function (item) {
			item.addEventListener('click', function (e) {
				e.preventDefault();
				openPlayer(this);
			});

			item.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					openPlayer(this);
				}
			});
		});

		/*
		=====================================
		CLOSE PLAYER
		=====================================
		*/

		function closePlayer() {
			overlay.classList.remove('active');

			if (currentPlayer === 'video' && video) {
				video.pause();
				video.currentTime = 0;
			}

			if (iframe) {
				iframe.src = '';
				iframe.style.display = 'none';
			}

			if (video) {
				video.removeAttribute('src');
				video.load();
				video.style.display = 'block';
			}

			if (controls) {
				controls.style.display = '';
			}

			setTimeout(function () {
				overlay.style.display = 'none';
				document.body.style.overflow = '';

				if (progressFill) {
					progressFill.style.width = '0%';
				}
				if (progressThumb) {
					progressThumb.style.left = '0%';
				}
			}, 300);
		}

		if (closeBtn) {
			closeBtn.addEventListener('click', closePlayer);
		}

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && overlay.classList.contains('active')) {
				closePlayer();
			}
		});

		overlay.addEventListener('click', function (e) {
			if (e.target === overlay) {
				closePlayer();
			}
		});

		/*
		------------------------------------
		PLAY / PAUSE
		------------------------------------
		*/

		if (playBtn && video) {
			playBtn.addEventListener('click', function () {
				if (video.paused) {
					video.play();
				} else {
					video.pause();
				}
			});

			video.addEventListener('play', function () {
				playBtn.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" fill="white"><rect x="6" y="5" width="4" height="14"></rect><rect x="14" y="5" width="4" height="14"></rect></svg>';
			});

			video.addEventListener('pause', function () {
				playBtn.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" fill="white"><polygon points="7,5 20,12 7,19"></polygon></svg>';
			});

			video.addEventListener('loadedmetadata', function () {
				if (duration) {
					duration.textContent = formatTime(video.duration);
				}
			});

			video.addEventListener('timeupdate', function () {
				if (current) {
					current.textContent = formatTime(video.currentTime);
				}

				if (video.duration && !dragging) {
					var percent = (video.currentTime / video.duration) * 100;
					if (progressFill) {
						progressFill.style.width = percent + '%';
					}
					if (progressThumb) {
						progressThumb.style.left = percent + '%';
					}
				}
			});
		}

		/*
		------------------------------------
		SEEK (Mouse + Touch)
		------------------------------------
		*/

		function seek(clientX) {
			if (!progress || !video || !video.duration) {
				return;
			}

			var rect    = progress.getBoundingClientRect();
			var percent = (clientX - rect.left) / rect.width;
			percent     = Math.max(0, Math.min(1, percent));

			if (progressFill) {
				progressFill.style.width = (percent * 100) + '%';
			}
			if (progressThumb) {
				progressThumb.style.left = (percent * 100) + '%';
			}

			video.currentTime = percent * video.duration;
		}

		if (progress) {
			progress.addEventListener('mousedown', function (e) {
				dragging = true;
				seek(e.clientX);
			});

			progress.addEventListener('touchstart', function (e) {
				if (e.touches && e.touches[0]) {
					dragging = true;
					seek(e.touches[0].clientX);
				}
			}, { passive: true });

			progress.addEventListener('click', function (e) {
				seek(e.clientX);
			});
		}

		document.addEventListener('mousemove', function (e) {
			if (!dragging) {
				return;
			}
			seek(e.clientX);
		});

		document.addEventListener('touchmove', function (e) {
			if (!dragging || !e.touches || !e.touches[0]) {
				return;
			}
			seek(e.touches[0].clientX);
		}, { passive: true });

		document.addEventListener('mouseup', function () {
			dragging = false;
		});

		document.addEventListener('touchend', function () {
			dragging = false;
		});

		/*
		------------------------------------
		MUTE
		------------------------------------
		*/

		if (muteBtn && video) {
			muteBtn.addEventListener('click', function () {
				var svg = muteBtn.querySelector('svg');
				video.muted = !video.muted;

				if (svg) {
					if (video.muted) {
						svg.innerHTML = '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><line x1="16" y1="8" x2="22" y2="16"></line><line x1="22" y1="8" x2="16" y2="16"></line>';
					} else {
						svg.innerHTML = '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>';
					}
				}
			});
		}

		/*
		------------------------------------
		FILTERS
		------------------------------------
		*/

		var filterButtons = document.querySelectorAll('.vg-filters button');
		var galleryItems  = document.querySelectorAll('.vg-item');

		filterButtons.forEach(function (button) {
			button.addEventListener('click', function () {
				filterButtons.forEach(function (btn) {
					btn.classList.remove('active');
				});
				this.classList.add('active');

				var filter = this.getAttribute('data-filter');

				galleryItems.forEach(function (item) {
					if (filter === '*') {
						item.style.display = '';
						return;
					}

					var targetClass = filter.replace('.', '');
					if (item.classList.contains(targetClass)) {
						item.style.display = '';
					} else {
						item.style.display = 'none';
					}
				});
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initGallery);
	} else {
		initGallery();
	}
})();
