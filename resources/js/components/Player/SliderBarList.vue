<template>
    <div class="slider-container">
        <div class="slider-bar-list">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            applyListeners() {
                this.$el.addEventListener('mousemove', (e) => {
                    this.$emit('mousemove', {
                        'element': this.$el,
                        'event': e,
                    });
                })

                this.$el.addEventListener('mouseover', (e) => {
                    this.$emit('mouseover', {
                        'element': this.$el,
                        'event': e,
                    });
                })

                this.$el.addEventListener('mouseout', (e) => {
                    this.$emit('mouseout', {
                        'element': this.$el,
                        'event': e,
                    });
                })

                this.$el.addEventListener('mousedown', (e) => {
                    this.$emit('mousedown', {
                        'element': this.$el,
                        'event': e,
                    });
                })
            }
        },  

        mounted() {
            this.applyListeners()
        }
    }
</script>
