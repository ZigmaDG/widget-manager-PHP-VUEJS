import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import VueRouter from 'vue-router';
import Home from './components/Home.vue';
import CreateWidget from './components/CreateWidget.vue';
import EditWidget from './components/EditWidget.vue';
import Slider from './components/Slider.vue';

Vue.config.productionTip = false
Vue.use(VueRouter);
const routes=[
      {path: '/home', component: Home},
      {path: '/', component: Home},
      {path: '/editar/:id', name:'editar', component: EditWidget},
      {path: '/nuevo_widget', component: CreateWidget},
      {path: '/slider/:id', name:'slider' ,component: Slider},
];

const router = new VueRouter({
  routes,
  mode: 'history'
});


new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
