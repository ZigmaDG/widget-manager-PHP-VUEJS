<template>
  <div style="width:75%; text-align: center">
    <HeaderComponent />
    <h2>Editar widget</h2>
    <div id="form-container" style="width:75%; text-align: center">
      <v-row align="center">
        <v-row justify="space-around">
          <v-switch v-model="valid" class="ma-4" label="Valido" readonly></v-switch>
        </v-row>
        <v-form ref="form" v-model="valid" :lazy-validation="lazy" v-on:submit.prevent="save()" enctype="multipart/form-data">
          <v-text-field
            name="Name"
            v-model="Widget_toSave.Name"
            :counter="20"
            :rules="nameRules"
            label="Nombre del Widget"
            required
          ></v-text-field>

         
          <v-text-field
            name="Width"
            v-model="Iframe_toSave.Width"
            :counter="4"
            :rules="sizeRules"
            label="Ancho del Widget"
            required
            suffix="px"
          ></v-text-field>

          <v-text-field
            name="Height"
            v-model="Iframe_toSave.Height"
            :counter="4"
            :rules="sizeRules"
            label="Alto del Widget"
            required
            suffix="px"
          ></v-text-field>

          <v-text-field
            name="Border"
            v-model="Iframe_toSave.Border"
            :counter="4"
            :rules="sizeRules"
            label="Tamaño del borde"
            required
            suffix="px"
          ></v-text-field>

          <v-select
            name="Scrolling"
            item-text="Opt"
            item-value="Iframe_toSave.Scrolling"
            v-model="Iframe_toSave.Scrolling"
            :items="ScrollOpt"
            :rules="[v => !!v || 'Item is required']"
            label="Mostrar ScrollBar"
          ></v-select>

          <v-btn :disabled="!valid" color="success" class="mr-4" type="submit">Guardar</v-btn>
        </v-form>
      </v-row>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import HeaderComponent from "./HeaderComponent.vue";
import Swal from 'sweetalert';
export default {
  name: "EditWidget",
  components: {
    HeaderComponent
  },
  data: () => ({
    valid: true,
    name: "",
    //REGLAS DE VALIDACIÓN
     
    nameRules: [
      v => !!v || "Nombre Requerido",
      v => (v && v.length <= 20) || "Debe tener menos de 20 caracteres"
    ],
    sizeRules: [
      v => !!v || "Tamaño Requerido",
      v => (v && v.length <= 4) || "Solo se admite un máximo de 4 digitos"
    ],
    select: "",
    Types: [
      { ID_TIPO: 1, Tipo: "Youtube" },
      { ID_TIPO: 2, Tipo: "Slider" }
    ],
    ScrollOpt: [{ Opt: "No" }, { Opt: "Yes" }],
    checkbox: false,
    lazy: false,
    File_show: false,
    src_input: false,
    // PROPIEDADES DEL WIDGET
    Widget_toSave: {
      Name: "",
      Type: "",
      src: ''
    },
    Iframe:{

    },
    Widget:{

    },
    Iframe_toSave: {
      Height: "",
      Width: "",
      Border: "",
      Scrolling: "",
      src: ""
    },
    id_widget: "",
    file: ''
  }),
 
beforeMount()
{
this.id_widget = this.$route.params.id;
},
  mounted(){
      
      this.getIframe();
      
  },

  methods: {
    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
      }
    },
    reset() {
      this.$refs.form.reset();
    },
    resetValidation() {
      this.$refs.form.resetValidation();
    },

    save() {

            axios.put('http://localhost/WIDGETCRUD/apiRest/public/api/widget/modificar/'+ this.id_widget, this.Widget_toSave)
            .then(res=>{
                console.log(res.data);
                const id_iframe = this.Iframe[0].ID_IFRAME_PROPERTIES;
                axios.put('http://localhost/WIDGETCRUD/apiRest/public/api/iframe/modificar/'+ id_iframe, this.Iframe_toSave)
                .then(res=>{
                    console.log(res.data);
                    console.log(id_iframe);
                    console.log(this.Iframe);
                })
                Swal(
                   'Widget Modificado', 
                   'consulta el código en la tabla de Home',
                   'success'
                 ) ;

                 
                
            })
            .catch(error => {
          console.log(error);
        });
        
    },

 getIframe(){
            axios.get('http://localhost/WIDGETCRUD/apiRest/public/api/widget/' +this.id_widget)
            .then(res => {
   
              this.Iframe = res.data;
             
               this.Iframe_toSave = JSON.parse(this.Iframe[0].IFRAME_PROPERTIES);
               this.Widget_toSave.Name = this.Iframe[0].WIDGET_NAME;
                console.log(this.Iframe_toSave);
             
            });
        },
    ShowTypeinput() {
      if (this.Widget.Type == 1) {
        this.File_show = false;
        this.src_input = true;
      }
      if (this.Widget.Type == 2) {
        this.File_show = true;
        this.src_input = false;
      }
    },

    File_change(){
       this.file = this.$refs.file.$refs.input.files;
       console.log(this.file);
        }
  }
};
</script>