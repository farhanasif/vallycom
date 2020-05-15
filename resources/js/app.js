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
    { path: '/details/:id', component: require('./components/ProductDetails.vue').default, name: 'details', props: true },
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/invoice', component: require('./components/Invoice.vue').default },
    { path: '/department', component: require('./components/Department.vue').default },
    { path: '/categories', component: require('./components/Category.vue').default },
    { path: '/subcategories', component: require('./components/SubCategory.vue').default },
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
        cats: [],
        newcat: null,
        cart: [],
        item: null,
        total: 0,
        baseURL: 'http::/localhost:8081/'
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

        addvalue(product){
            console.log(product)
            let item = {
                title : product.title,
                quantity: 1,
                price: product.current_price
            }

            this.cart.push(item);
            localStorage.setItem('cart', JSON.stringify(this.cart));
        },

        removeCat(n){
            console.log(n);
            this.cats.splice(n, 1);
            localStorage.setItem('cats', JSON.stringify(this.cats));
            console.log(this.cats);
        },

        removeCart(n){
            console.log(n);
            this.cart.splice(n, 1);
            localStorage.setItem('cart', JSON.stringify(this.cart));
            console.log(this.cart);
        },

        openModal() {
            $('#cartModal').modal('show');
        }
    },
    mounted() {
        if (localStorage.getItem('cart')) {
            try {
                this.cart = JSON.parse(localStorage.getItem('cart'));
                console.log(JSON.stringify(this.cart));
            } catch(e) {
                localStorage.removeItem('cart');
            }
        }
        else{
            console.log('no cart found')
        }
    },
    computed: {
        totalItem: function(){
            let sum = 0;
            for(let i = 0; i < this.cart.length; i++){
              sum += (parseFloat(this.cart[i].price));
            }
      
           return sum;
         }
    }
  }).$mount('#app')
