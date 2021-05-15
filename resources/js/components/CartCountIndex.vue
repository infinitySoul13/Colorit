<template>
    <div class="badge count">
        <div class="row m-auto w-100 h-100 align-items-center justify-content-center">
            <p v-if="cartTotalCount<=99">{{cartTotalCount}}</p>
            <p v-else>99+</p>
        </div>
    </div>
</template>
<script>

    export default {
        mounted() {
            this.$store.dispatch("getProductList")
        },
        activated() {
            this.$store.dispatch("getProductList")
        },
        computed: {
            cartProducts: function () {
                return this.$store.getters.cartProducts;
            },
            cartTotalCount: function () {
                return this.$store.getters.cartTotalCount;
            },
        },
    }
</script>
<style lang="scss">
    .count {
        height: 20px;
        min-width:20px;
        padding:5px;
        border-radius:10px;
        position: absolute;
        text-align: center;
        background: #ff4073;
        left: 80%;
        top: -10px;
        color: #fff;
        line-height:12px;
        letter-spacing: 0;
        font-weight: 400;
    }
</style>
