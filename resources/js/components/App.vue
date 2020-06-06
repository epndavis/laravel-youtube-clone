<template>
  <div>
      <div>
          <router-link :to="{ name: 'home' }">Home</router-link>
          <router-link :to="{ name: 'upload' }">Upload</router-link>
      </div>
      <div>
          <router-view></router-view>
      </div>  
  </div>
</template>

<script>
export default {

}
</script>
