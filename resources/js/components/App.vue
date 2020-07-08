<template>
  <div :class="`${$route.name}-page`">
      <nav-bar/>

      <div>
          <router-view :key="$route.fullPath"></router-view>
      </div>  
  </div>
</template>

<script>
import NavBar from './NavBar'

export default {
    components: {
        'nav-bar': NavBar
    }
}
</script>
