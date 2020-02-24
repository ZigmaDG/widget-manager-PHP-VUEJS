<template>
  <div style="width:75%; text-align: center">
    <HeaderComponent />
    <h2>Nuevo widget</h2>
    <div id="form-container" style="width:75%; text-align: center">
      <v-row align="center">
        <v-row justify="space-around">
          <v-switch v-model="valid" class="ma-4" label="Valid" readonly></v-switch>
        </v-row>
        <v-form ref="form" v-model="valid" :lazy-validation="lazy" v-on:submit.prevent="save()" enctype="multipart/form-data">
          <v-text-field
            name="Name"
            v-model="Widget.Name"
            :counter="20"
            :rules="nameRules"
            label="Nombre del Widget"
            required
          ></v-text-field>

          <v-select
            name="Type"
            item-text="Tipo"
            item-value="ID_TIPO"
            v-model="Widget.Type"
            :items="Types"
            :rules="[v => !!v || 'Item is required']"
            label="Tipo de widget"
            required
            @change="ShowTypeinput"
          ></v-select>
         <v-file-input  id="file" name="file0[]" ref="file" @change="File_change()" multiple v-if="File_show"/>
         

          <v-text-field
            name="src"
            v-model="Widget.src"
            label="Link de youtube"
            v-if="src_input"
            required
          ></v-text-field>

          <v-text-field
            name="Width"
            v-model="Iframe.Width"
            :counter="4"
            :rules="sizeRules"
            label="Ancho del Widget"
            suffix="px"
            required
          ></v-text-field>

          <v-text-field
            name="Height"
            v-model="Iframe.Height"
            :counter="4"
            :rules="sizeRules"
            label="Alto del Widget"
            suffix="px"
            required
          ></v-text-field>

          <v-text-field
            name="Border"
            v-model="Iframe.Border"
            :counter="4"
            :rules="sizeRules"
            label="Tamaño del borde "
            suffix="px"
            required
          ></v-text-field>

          <v-select
            name="Scrolling"
            item-text="Opt"
            item-value="Opt"
            v-model="Iframe.Scrolling"
            :items="ScrollOpt"
            :rules="[v => !!v || 'Se debe seleccionar']"
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
  name: "CreateWidget",
  components: {
    HeaderComponent
  },
  data: () => ({
    valid: true,
    name: "",
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
    Widget: {
      Name: "",
      Type: "",
      src: ''
    },
    Iframe: {
      Height: "",
      Width: "",
      Border: "",
      Scrolling: "",
      src: ""
    },
    id_widget: "",
    file: ''
  }),

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
      axios
        .post(
          "http://localhost/WIDGETCRUD/apiRest/public/api/widget/nuevo",
          this.Widget
        )
        .then(res => {
          console.log(res);
          this.id_widget = res.data;
          if(this.Widget.Type==1){

             const id_video= this.getVideoId(this.Widget.src);
                this.Iframe.src = 'https://www.youtube.com/embed/'+id_video;
          }
          else{
            this.Iframe.src="http://localhost:8080/slider/"+this.id_widget;
          }
          
          axios
            .post(
              "http://localhost/WIDGETCRUD/apiRest/public/api/iframe/nuevo/" +
                this.id_widget,
              this.Iframe
            )
            .then(res => {
              console.log(res);
            })
            .catch(error => {
              console.log(error);
            });


            if(this.file != ''){
                //Subida de Imagenes
            const formData = new FormData();

            for(var i=0; i <= this.file.length-1; i++){
                    formData.append("file0[]", this.file[i]);
                    console.log(i);
            }
            
            axios.post("http://localhost/WIDGETCRUD/apiRest/public/api/widget/subir-imagen/"+this.id_widget, formData)
            .then(res=>{
                 console.log(res.data);  
                 
            })
            .catch(err =>{
                console.log(err);
            })
            }
          Swal(
                   'Widget Creado', 
                   'consulta el código en la tabla de Home',
                   'success'
                 ) ;
                 
        })
        .catch(error => {
          console.log(error);
        });
         this.$router.push("/home");
         
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
        },

        getVideoId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);

    return (match && match[2].length === 11)
      ? match[2]
      : null;
}
  },
   
};
</script>