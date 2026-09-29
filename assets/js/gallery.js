function initEmmadVideoGallery(){

    const overlay = document.getElementById("vg-player");
    if(!overlay) return;

    // Attach overlay directly to body to prevent theme container clipping or transform issues
    if (overlay.parentNode !== document.body) {
        document.body.appendChild(overlay);
    }

    const video = document.getElementById("vg-video-player");
    const iframe = document.getElementById("vg-iframe-player");

    const controls = document.querySelector(".vg-controls");

    const playBtn = document.getElementById("vg-play");

    const progress = document.getElementById("vg-progress");
    const progressFill = document.getElementById("vg-progress-fill");
    const progressThumb = document.getElementById("vg-progress-thumb");

    const current = document.getElementById("vg-current");
    const duration = document.getElementById("vg-duration");

    const muteBtn = document.getElementById("vg-mute");

    const title = document.getElementById("vg-title");
    const closeBtn = document.querySelector(".vg-close");

    overlay.style.display = "none";

    let currentPlayer = "video";

    /*
    =====================================
    HELPERS
    =====================================
    */

    function isYouTube(url){
        return /(youtube\.com|youtu\.be)/i.test(url);
    }

    function isVimeo(url){
        return /vimeo\.com/i.test(url);
    }

    function youtubeEmbed(url){
        let id = "";
        if(url.includes("youtu.be/")){
            id = url.split("youtu.be/")[1].split("?")[0].split("#")[0];
        }else if(url.includes("youtube.com/shorts/")){
            id = url.split("youtube.com/shorts/")[1].split("?")[0].split("#")[0];
        }else{
            const match = url.match(/[?&]v=([^&#]+)/);
            if(match){
                id = match[1];
            }
        }
        return id
            ? "https://www.youtube.com/embed/" + id + "?autoplay=1&rel=0"
            : "";
    }

    function vimeoEmbed(url){
        if(url.includes("player.vimeo.com/video/")){
            return url + (url.includes("?") ? "&" : "?") + "autoplay=1";
        }
        const match = url.match(/vimeo\.com\/(\d+)/);
        if(match){
            return "https://player.vimeo.com/video/" + match[1] + "?autoplay=1";
        }
        return "";
    }

    /*
    =====================================
    OPEN PLAYER
    =====================================
    */

    document.querySelectorAll(".vg-video").forEach(function(item){
        item.addEventListener("click", function(e){
            e.preventDefault();

            const videoURL = this.dataset.video;
            const videoTitle = this.dataset.title || "";

            if(!videoURL) return;

            if(title){
                title.textContent = videoTitle;
            }

            overlay.style.display = "flex";
            document.body.style.overflow = "hidden";

            /*
            ----------------------------
            YOUTUBE
            ----------------------------
            */
            if(isYouTube(videoURL)){
                currentPlayer = "youtube";
                if(video){
                    video.pause();
                    video.removeAttribute("src");
                    video.style.display = "none";
                }
                if(iframe){
                    iframe.src = youtubeEmbed(videoURL);
                    iframe.style.display = "block";
                }
                if(controls){
                    controls.style.display = "none";
                }
            }
            /*
            ----------------------------
            VIMEO
            ----------------------------
            */
            else if(isVimeo(videoURL)){
                currentPlayer = "vimeo";
                if(video){
                    video.pause();
                    video.removeAttribute("src");
                    video.style.display = "none";
                }
                if(iframe){
                    iframe.src = vimeoEmbed(videoURL);
                    iframe.style.display = "block";
                }
                if(controls){
                    controls.style.display = "none";
                }
            }
            /*
            ----------------------------
            MP4
            ----------------------------
            */
            else{
                currentPlayer = "video";
                if(iframe){
                    iframe.src = "";
                    iframe.style.display = "none";
                }
                if(video){
                    video.style.display = "block";
                    video.src = videoURL;
                    setTimeout(function(){
                        video.play();
                    }, 50);
                }
                if(controls){
                    controls.style.display = "";
                }
            }

            setTimeout(function(){
                overlay.classList.add("active");
            }, 50);
        });
    });

    /*
    =====================================
    CLOSE PLAYER
    =====================================
    */

    function closePlayer(){
        overlay.classList.remove("active");

        if(currentPlayer === "video" && video){
            video.pause();
            video.currentTime = 0;
            video.removeAttribute("src");
            video.load();
        }

        if(iframe){
            iframe.src = "";
            iframe.style.display = "none";
        }

        if(video){
            video.style.display = "block";
        }

        if(controls){
            controls.style.display = "";
        }

        setTimeout(function(){
            overlay.style.display = "none";
            document.body.style.overflow = "";

            if(progressFill){
                progressFill.style.width = "0%";
            }
            if(progressThumb){
                progressThumb.style.left = "0%";
            }
        }, 300);
    }

    if(closeBtn){
        closeBtn.addEventListener("click", closePlayer);
    }

    document.addEventListener("keydown", function(e){
        if(e.key === "Escape" && overlay.classList.contains("active")){
            closePlayer();
        }
    });

    overlay.addEventListener("click", function(e){
        if(e.target === overlay){
            closePlayer();
        }
    });

    /*
    ------------------------------------
    PLAY / PAUSE
    ------------------------------------
    */

    if(playBtn && video){
        playBtn.addEventListener("click", function(){
            if(video.paused){
                video.play();
            }else{
                video.pause();
            }
        });

        video.addEventListener("play", function(){
            playBtn.innerHTML = `
            <svg viewBox="0 0 24 24" width="18" height="18" fill="white">
                <rect x="6" y="5" width="4" height="14"></rect>
                <rect x="14" y="5" width="4" height="14"></rect>
            </svg>`;
        });

        video.addEventListener("pause", function(){
            playBtn.innerHTML = `
            <svg viewBox="0 0 24 24" width="18" height="18" fill="white">
                <polygon points="7,5 20,12 7,19"></polygon>
            </svg>`;
        });

        video.addEventListener("loadedmetadata", function(){
            if(duration){
                duration.textContent = formatTime(video.duration);
            }
        });

        video.addEventListener("timeupdate", function(){
            if(current){
                current.textContent = formatTime(video.currentTime);
            }

            if(video.duration && !dragging){
                const percent = (video.currentTime / video.duration) * 100;
                if(progressFill){
                    progressFill.style.width = percent + "%";
                }
                if(progressThumb){
                    progressThumb.style.left = percent + "%";
                }
            }
        });
    }

    /*
    ------------------------------------
    SEEK (Mouse + Touch)
    ------------------------------------
    */

    let dragging = false;

    function seek(clientX){
        if(!progress || !video || !video.duration) return;
        const rect = progress.getBoundingClientRect();
        let percent = (clientX - rect.left) / rect.width;
        percent = Math.max(0, Math.min(1, percent));
        if(progressFill){
            progressFill.style.width = (percent * 100) + "%";
        }
        if(progressThumb){
            progressThumb.style.left = (percent * 100) + "%";
        }
        video.currentTime = percent * video.duration;
    }

    if(progress){
        progress.addEventListener("mousedown", function(e){
            dragging = true;
            seek(e.clientX);
        });

        progress.addEventListener("touchstart", function(e){
            if(e.touches && e.touches[0]){
                dragging = true;
                seek(e.touches[0].clientX);
            }
        }, {passive: true});

        progress.addEventListener("click", function(e){
            seek(e.clientX);
        });
    }

    document.addEventListener("mousemove", function(e){
        if(!dragging) return;
        seek(e.clientX);
    });

    document.addEventListener("touchmove", function(e){
        if(!dragging || !e.touches || !e.touches[0]) return;
        seek(e.touches[0].clientX);
    }, {passive: true});

    document.addEventListener("mouseup", function(){
        dragging = false;
    });

    document.addEventListener("touchend", function(){
        dragging = false;
    });

    /*
    ------------------------------------
    MUTE
    ------------------------------------
    */

    if(muteBtn && video){
        muteBtn.addEventListener("click", function(){
            const svg = muteBtn.querySelector("svg");
            video.muted = !video.muted;

            if(svg){
                if(video.muted){
                    svg.innerHTML = `
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                        <line x1="16" y1="8" x2="22" y2="16"></line>
                        <line x1="22" y1="8" x2="16" y2="16"></line>
                    `;
                }else{
                    svg.innerHTML = `
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                        <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                    `;
                }
            }
        });
    }

    /*
    ------------------------------------
    FILTERS
    ------------------------------------
    */

    const filterButtons = document.querySelectorAll(".vg-filters button");
    const galleryItems = document.querySelectorAll(".vg-item");

    filterButtons.forEach(function(button){
        button.addEventListener("click", function(){
            filterButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");
            const filter = this.dataset.filter;
            galleryItems.forEach(function(item){
                if(filter === "*"){
                    item.style.display = "";
                    return;
                }
                if(item.classList.contains(filter.replace(".", ""))){
                    item.style.display = "";
                }else{
                    item.style.display = "none";
                }
            });
        });
    });

    /*
    ------------------------------------
    FORMAT TIME
    ------------------------------------
    */

    function formatTime(seconds){
        if(isNaN(seconds)) return "00:00";
        let mins = Math.floor(seconds / 60);
        let secs = Math.floor(seconds % 60);
        if(mins < 10) mins = "0" + mins;
        if(secs < 10) secs = "0" + secs;
        return mins + ":" + secs;
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initEmmadVideoGallery);
} else {
    initEmmadVideoGallery();
}
