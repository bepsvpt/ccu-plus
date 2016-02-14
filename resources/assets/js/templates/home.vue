<template>
    <div class="parallax-container">
        <div class="parallax"><img src="/assets/images/home/p1.png"></div>
    </div>

    <div class="parallax-container">
        <div class="parallax"><img src="/assets/images/home/p2.png"></div>
    </div>
</template>

<script>
    export default {
        created() {
            $('main').removeClass('container');
        }
    }
</script>
