<template>
    <div class="parallax-container">
        <div class="parallax"><img src="/assets/images/home/p1.jpg" style="width: 100%;"></div>
        <div class="container" style="position: inherit;">
            <img src="/assets/images/home/p1.svg" class="home-cover-img">
        </div>
    </div>

    <div class="grey lighten-4" style="padding-top: 3rem; padding-bottom: 3rem;">
        <div class="row container" style="margin: 0 auto;">
            <div class="col s12 m3 center">
                <h2 class="teal-text text-lighten-3"><i class="fa fa-compass fa-3x"></i></h2>
            </div>
            <div class="col s12 m9">
                <h3 class="header  grey-text text-darken-3">無可替代。屬於中正人的校園系統</h3>
                <h5 class="grey-text text-darken-1" style="line-height: 180%;">
                    曾幾何時，我們曾經想要在選課時有過來人的經驗來指引我們<br>
                    曾幾何時，我們曾經想要一套更好、更親民的 Ecourse 系統<br>
                    曾幾何時，我們都想讓這些夢實現，但是一直苦無機會，困擾遲遲不去<br><br>
                    現在，我們推出 CCU PLUS，劃時代的服務，將顛覆我們既往選課以及學業上的模式
                </h5>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        created() {
            $('main').addClass('container-remove');

            $('footer').css('margin-top', '0');
        },

        destroyed() {
            $('main').removeClass('container-remove');

            $('footer').css('margin-top', '');
        }
    }
</script>
