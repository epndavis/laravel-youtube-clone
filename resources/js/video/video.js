class Video {
    constructor ($el) {
        this.$el = $el
        this.currentTime = 0  
        this.duration = 1
        this.paused = this.$el.paused
        this.ended = false
        this.buffered = 0
        this.muted = this.$el.muted

        let self = this

        this.$el.onloadedmetadata = function() {
            self.duration = this.duration
        }

        this.$el.ontimeupdate = function() {
            self.currentTime = this.currentTime
        }

        this.$el.onprogress = function() {
            if (this.buffered.length > 0) {
                self.buffered = this.buffered.end(0)
            } else {
                self.buffered = 0
            }
        }
        
        this.$el.onpause = function() {
            self.paused = true
        }
        
        this.$el.onended = function() {
            self.ended = true
        }

        this.$el.onvolumechange = function() {
            self.muted = this.muted
        }
    }

    play() {
        return this.$el.play().then(() => {
            this.paused = false
            this.ended = false
        })
    }

    pause() {
        return this.$el.pause()
    }

    replay() {
        return this.jumpTo(0).play()
    }

    fullscreen() {
        let vid = this.$el
        if (vid.requestFullscreen) {
            vid.requestFullscreen()
        } else if (vid.mozRequestFullScreen) {
            vid.mozRequestFullScreen()
        } else if (vid.webkitRequestFullscreen) {
            vid.webkitRequestFullscreen()
        }  else if (vid.msRequestFullscreen) { /* IE/Edge */
            vid.msRequestFullscreen()
        }

        return vid;
    }

    pictureInPicture() {
        return this.$el.requestPictureInPicture()
    }

    jumpTo(time) {
        this.$el.currentTime = time

        return this
    }
}

export default Video
