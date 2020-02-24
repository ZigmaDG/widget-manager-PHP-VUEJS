
<template>

<div>
  <HeaderComponent/>
  <Widgets/>
</div>


  
  
</template>


<script>
import Widgets from "./Widgets.vue";
import HeaderComponent from "./HeaderComponent.vue";
export default {
  name: "Home",
  components: {
    Widgets,
    HeaderComponent
  }
};
</script>