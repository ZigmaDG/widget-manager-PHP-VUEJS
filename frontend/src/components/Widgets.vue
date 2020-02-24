<template>
    <div id="Widget Manager">
    <h1 style="text-align: center; margin-top: 15px">Widget Manager</h1>
  
 <div id="app"> 
<v-app id="inspire">
  
  <h3 v-if="!widgets" style="text-align: center">aun no hay widgets creados</h3>

    <v-simple-table fixed-header height="300px" v-if="widgets">
      <template v-slot:default>
        <thead>
          <tr>
            <th class="text-center">Nombre</th>
            <th class="text-center">Tipo</th>
            <th class="text-center" > Acciones</th>
          </tr>
        </thead>
        <tbody >
          
          <tr v-for="item in Iframes" :key="item.ID_IFRAME_PROPERTIES">
            <td class="text-center">{{ item.WIDGET_NAME }}</td>
            <td class="text-center">{{ item.NAME_WIDGET_TYPE }}</td>
            <td class="text-center">
                <router-link :to="{name:'editar', params: {id: item.ID_IFRAME_PROPERTIES}}">
                    <v-btn style="margin: 25px">Editar</v-btn>
                </router-link>

                <router-link v-if="item.ID_WIDGET_TYPE == 2" :to="{name:'slider', params: {id: item.ID_WIDGET}}" target='_blank'>
                    <v-btn style="margin: 25px">Ver slider</v-btn>
                </router-link>
                 <v-btn style="margin-right: 25px; color: white" color="blue" @click=GetCode(item)>Generar Codigo</v-btn>
                <v-btn style="margin-right: 25px; color: white" color="red" @click=Eliminar(item)>Eliminar</v-btn>
            </td>
            
          </tr>
        </tbody>
      
      </template>
    </v-simple-table>
    <div>

      <v-dialog v-model="dialog">
        <v-card>
          <v-card-title> Copia y pega este código en tu página web</v-card-title>
          <v-card-text> <b></b>
          {{iframelabel}}
          
          </v-card-text>
            <v-divider></v-divider>
            <v-card-actions><v-btn @click="dialog=false">Cerrar</v-btn></v-card-actions>
        </v-card> 
      </v-dialog>
     
    </div>
  </v-app>
 </div>
  </div>
</template>

<script>

import axios from 'axios';
import Swal from 'sweetalert';
export default {
     name: 'Widgets',
    data(){
        return{
            Iframes: [],
            Iframe_Props: {},
            widgets: false,
            dialog: false,
            iframelabel: `<iframe id="frame" src="" width="" height=""></iframe>`,

        }
    },
    
  beforeMount(){
      this.getIframes();
  },



    methods: {
        getIframes(){
            axios.get('http://localhost/WIDGETCRUD/apiRest/public/api/iframe')
            .then(res => {
                
               
              this.Iframes = res.data;
              var Widg_exist = typeof this.Iframes;
              if(Widg_exist!='string'){
                this.widgets =true;
              }
               
             console.log(this.Iframes)
              
             
            });
        },
        Eliminar(item){
          console.log(item.ID_IFRAME_PROPERTIES)
           axios.delete('http://localhost/WIDGETCRUD/apiRest/public/api/widget/eliminar/'+ item.ID_IFRAME_PROPERTIES)
            .then(res =>{
              this.$router.go("/");
                Swal(
                  res.data,
                  'Completado',
                  'success'
                )
              
             
            })
       
            },
        
        
        GetCode(item){
               this.Iframe_Props=JSON.parse(item.IFRAME_PROPERTIES);
               console.log(this.Iframe_Props);
                this.dialog=true;
                 this.iframelabel= `<iframe id="frame" src="${this.Iframe_Props['src']}" width=${this.Iframe_Props['Width']} height=${this.Iframe_Props['Height']} scrolling="${this.Iframe_Props['Scrolling']}" frameborder=${this.Iframe_Props['Border']}></iframe>`
        }

    }
    }
    

</script>