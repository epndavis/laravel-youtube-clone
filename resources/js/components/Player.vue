<template>
    <div class="video-player">
         <video id="video_player" width="100%" controls>
            <source :src="src" type="video/mp4"/>
        </video>  
    </div>
</template>

<script>
    export default {
        props: {
            src: {
                type: String,
                required: true,
            }
        },

        mounted() {
            const video = document.getElementById('video_player')

            video.play().catch(function(error) {
                console.log("Cannot play video right now!")
            });
            
        }
    }
</script>
