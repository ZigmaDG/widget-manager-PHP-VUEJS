<template>
    <div id="app">
        
  <v-app id="inspire">
    <v-carousel
      cycle
      height="400"
      hide-delimiter-background
      show-arrows-on-hover
    >
      <v-carousel-item
        v-for="(slide, i) in images"
        :key="i"
        :src="'http://localhost/WIDGETCRUD/apiRest/src/uploads/'+slide"
      >
        
      </v-carousel-item>
    </v-carousel>
  </v-app>
</div>
</template>

<script>
import axios from 'axios';
export default {
    name: "Slider",
    beforeMount(){
         this.id_widget = this.$route.params.id;
        
    },
    mounted(){
       
      this.getimages();
    },
    data(){
        return{
            images: []
            
        }
             
        },
        methods:{
            getimages(){
                axios.get('http://localhost/WIDGETCRUD/apiRest/public/api/slider/' + this.id_widget)
            .then(res => {
                
               
              this.images = JSON.parse(res.data[0].WIDGET_DATA);
              console.log(this.images)
              
              
              
            });
            }
        }
}
</script>