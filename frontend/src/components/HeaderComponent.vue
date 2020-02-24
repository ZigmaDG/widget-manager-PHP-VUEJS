<template>
    <v-app-bar
      app
      color="red"
      dark
    >
      <div class="d-flex align-center">
       <router-link to="/home" style="color: white; text-decoration: none">Inicio</router-link>
      </div>

      <v-spacer></v-spacer>
       <div class="d-flex align-center">
       <router-link to="/nuevo_widget" style="color: white; text-decoration: none">Crear nuevo Widget</router-link>
      </div>

      <v-btn
        href="https://github.com/ZigmaDG?tab=repositories"
        target="_blank"
        text
      >
        <span class="mr-2">Guillermo Ramos github</span>
        <v-icon>mdi-open-in-new</v-icon>
      </v-btn>
    </v-app-bar>
</template>