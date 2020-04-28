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
                <div class="col-md-4" v-for="product in products" :key="product.id">
                    <div class="card" style="width: 18rem;">
                        <img :src="randomColor()" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{product.title}}</h5>
                            <p class="card-text">${{product.current_price}}</p>
                            <a href="#" class="btn btn-warning"><i class="nav-icon fas fa-heart black mr-1"></i>Wishlist</a>
                            <a href="#" class="btn btn-primary"><i class="nav-icon fas fa-cart-plus white mr-1"></i>Add to Cart</a>
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
                products: [],
                page: 1,
            }
        },
        methods: {
            infiniteHandler($state) {
                setTimeout(() => {
                    console.log('loading infinite...')
                    axios.get('api/products',{
                        params: {
                            page: this.page,
                        },
                    }).then(({ data }) => {
                        console.log(data);
                        if (data.data.length) {
                            this.page += 1;
                            for(let i = 0; i<data.data.length; i++){
                                this.products.push(data.data[i]);
                            }
                            $state.loaded();
                        } else {
                            $state.complete();
                        }
                        
                    })
                    $state.loaded();
                }, 1000);
            },

            randomColor : function(){
                const colors = ["B42506", "B4A406", "09B406", "063BB4"];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                const url = "https://dummyimage.com/150x150/"+randomColor+"/fff";
                return url;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>