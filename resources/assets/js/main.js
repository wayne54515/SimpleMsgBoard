import Vue from 'vue'
// import App from './App.vue'
// import VueRouter from 'vue-router'
import axios from 'axios'
import VueAxios from 'vue-axios'
window.jQuery = require('jquery')

require('bootstrap-sass')

// window.Tether = require('tether')
// require('bootstrap')

// Vue.use(VueRouter)
Vue.use(VueAxios, axios)

// import Gallery from './components/Gallery.vue'
// import CardInfo from './components/CardInfo.vue'

// const router = new VueRouter({
//     mode: 'history',
//     base: __dirname,
//     routes: [
//         {path: '/gallery', component: Gallery},
//         {path: '/cardInfo/:id', component: CardInfo}
//     ]
// })

// new Vue(Vue.util.extend({ router }, App)).$mount('#app')

Vue.component('home', require('./components/Home.vue'));
Vue.component('article_page', require('./components/Article.vue'));
Vue.component('navbar', require('./components/Navbar.vue'));

const app = new Vue({
    el: '#app'
});