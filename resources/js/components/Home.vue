<template>
    <div class="container mb-5">
        <div class="col-md-12 mt-2">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner" style="height: 400px !important;">
                    <div class="carousel-item active">
                        <img src="img/master/lauren-fleischmann-R2aodqJn3b8-unsplash.jpg" class="d-cover w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="img/master/artem-beliaikin-a8MJQHunT-8-unsplash.jpg" class="d-cover w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                    <img src="img/master/mnz-RsJDUzKdBws-unsplash.jpg" class="d-cover w-100" alt="...">
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center mt-2 mb-2">
                <h3 class="h3">... Explore ...</h3>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6" v-for="product in products[0]" :key="product.id">
                    <div class="product-grid3">
                        <div class="product-image3">
                            <a href="#">
                                <img class="pic-1" src="http://bestjquery.com/tutorial/product-grid/demo4/images/img-1.jpg">
                                <img class="pic-2" src="http://bestjquery.com/tutorial/product-grid/demo4/images/img-2.jpg">
                            </a>
                            <ul class="social">
                                <li><a href="#"><i class="fa fa-shopping-bag"></i></a></li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                            <span class="product-new-label">SALE</span>
                        </div>
                        <div class="product-content">
                            <h3 class="title"><a href="#">{{product.title}}</a></h3>
                            <div class="price">
                                ${{product.price}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <infinite-loading @infinite="infiniteHandler"></infinite-loading>
    </div>
</template>

<script>
    import InfiniteLoading from 'vue-infinite-loading';

    export default {
        data(){
            return {
                products: []
            }
        },
        methods: {
            // loadProducts(){
            //     axios.get('MOCK_DATA.json').then(({ data }) => {
            //         console.log(data);
            //         this.products = data
            //     })
            // },
            // infiniteHandler(){
            //     console.log('loading infinite...')
            //     axios.get('MOCK_DATA.json').then(({ data }) => {
            //         //console.log(data);
            //         if(data.length){
            //             this.products.push(data);
            //             console.log('products loaded');
            //         }
                    
            //     })
            // },
            infiniteHandler($state) {
                setTimeout(() => {
                    console.log('loading infinite...')
                    axios.get('MOCK_DATA.json').then(({ data }) => {
                        //console.log(data);
                        if(data.length){
                            const temp = [];
                            for(let i in data){
                                temp.push(data[i]);
                            }
                            //this.products = this.products.concat(temp);
                            this.products.push(data);
                            console.log(this.products);
                        }
                        
                    })
                    //this.list = this.list.concat(temp);
                    $state.loaded();
                }, 1000);
            },
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            //this.loadProducts();
        },
        components: {
            InfiniteLoading,
        },
    }
</script>