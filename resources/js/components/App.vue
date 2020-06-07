<template>
  <div>
      <nav-bar/>

      <div>
          <router-view></router-view>
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
