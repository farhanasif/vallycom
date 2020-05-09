/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte');



window.Vue = require('vue');
import moment from 'moment';

import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast = toast;

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);


import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
const swalOptions = {
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
}
Vue.use(VueSweetalert2, swalOptions);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})

Vue.component('pagination', require('laravel-vue-pagination'));

import InfiniteLoading from 'vue-infinite-loading';

Vue.use(InfiniteLoading);


const routes = [
    { path: '/', component: require('./components/Home.vue').default },
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/invoice', component: require('./components/Invoice.vue').default },
    { path: '/department', component: require('./components/Department.vue').default },
    { path: '/categories', component: require('./components/Category.vue').default },
    { path: '*', component: require('./components/404.vue').default }
  ]

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('myDate',function(created){
    return moment(created).format('MMMM Do YYYY');
});

window.Fire =  new Vue();

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/404.vue').default
);

const app = new Vue({
    router,
    data:{
        search: '',
        cart: 0,
        cats: [],
        newcat: null,
    },
    methods:{
        searchit: _.debounce(() => {
            Fire.$emit('searching');
        },1000),

        printme() {
            window.print();
        },

        callCartModal(){
            console.log('Modal Called');
        },

        addvalue(title){
            let newcat = {
                name: title
            }
            this.cats.push(newcat);
            localStorage.setItem('cats', JSON.stringify(this.cats));
            this.cart += 1;
        },

        removeCat(n){
            console.log(n);
            this.cats.splice(n, 1);
            localStorage.setItem('cats', JSON.stringify(this.cats));
            console.log(this.cats);
        },

        openModal() {
            $('#cartModal').modal('show');
        }
    },
    mounted() {
        if (localStorage.getItem('cats')) {
            try {
                this.cats = JSON.parse(localStorage.getItem('cats'));
                console.log(JSON.stringify(this.cats));
            } catch(e) {
                localStorage.removeItem('cats');
            }
        }
        else{
            console.log('no cats found')
        }
    }
  }).$mount('#app')
