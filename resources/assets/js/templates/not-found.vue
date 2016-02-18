<template>
    <div class="center" style="margin-top: 6%;">
        <div style="position: relative;">
            <img src="/assets/images/icon.png">
            <i
                class="material-icons medium orange-text"
                style="position: absolute; bottom: -1px; margin-left: -31px"
            >warning</i>
        </div>

        <br>

        <h4>船長，這裡沒路了</h4>

        <br>

        <h5><a v-link="{name: 'home'}">回首頁</a></h5>
    </div>
</template>

<script type="text/babel">
    export default {
    }
</script>
