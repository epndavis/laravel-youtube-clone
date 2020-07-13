export {
    browserAllowsFullscreen,
    onFullscreenChange,
    enterFullscreen,
    exitFullscreen,
}

function browserAllowsFullscreen() {
    return document.exitFullscreen || document.mozCancelFullScreen || document.webkitExitFullscreen || document.msExitFullscreen;
}

function onFullscreenChange(exitHandler) {
    if (!browserAllowsFullscreen()) {
        return;
    }

    if (document.exitFullscreen) {
        document.addEventListener('fullscreenchange', exitHandler, false);
    } else if (document.mozCancelFullScreen) { /* Firefox */
        document.addEventListener('mozfullscreenchange', exitHandler, false);
    } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
        document.addEventListener('webkitfullscreenchange', exitHandler, false);                  
    } else if (document.msExitFullscreen) { /* IE/Edge */
        document.addEventListener('MSFullscreenChange', exitHandler, false);
    }   
}

function enterFullscreen($el) {
    if ($el.requestFullscreen) {
        $el.requestFullscreen()
    } else if ($el.mozRequestFullScreen) {
        $el.mozRequestFullScreen()
    } else if ($el.webkitRequestFullscreen) {
        $el.webkitRequestFullscreen()
    } else if ($el.msRequestFullscreen) { /* IE/Edge */
        $el.msRequestFullscreen()
    }
}

function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) { /* Firefox */
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE/Edge */
        document.msExitFullscreen();
    }
}
