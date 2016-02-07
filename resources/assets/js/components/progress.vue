<template>
    <div v-show="loading" class="preloader-wrapper big active">
        <div class="spinner-layer spinner-blue-only">
            <div class="circle-clipper left">
                <div class="circle"></div>
            </div>
            <div class="gap-patch">
                <div class="circle"></div>
            </div>
            <div class="circle-clipper right">
                <div class="circle"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'loading': {
                type: Boolean,
                required: true,
                twoWay: false
            }
        }
    }
</script>
