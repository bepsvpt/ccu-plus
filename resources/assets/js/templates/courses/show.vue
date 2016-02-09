<style lang="scss">
    .professor:not(:first-child) {
        margin-left: 5px;
    }

    .tabs-pushpin {
        margin-top: 180px;

        a {
            user-select: none;
        }
    }
</style>

<template>
    <div class="row">
        <div class="col hide-on-small-only m3 l2" style="min-height: 1px;">
            <div class="tabs-pushpin">
                <ul class="section table-of-contents">
                    <li><a data-target="info" @click="pushpin">課程資訊</a></li>
                    <li><a data-target="comments" @click="pushpin">課程評論</a></li>
                    <li><a data-target="exams" @click="pushpin">考古題</a></li>
                </ul>
            </div>
        </div>

        <div class="col s12 m9 l10">
            <div id="info">
                <ul class="collection">
                    <template v-for="course in courses">
                        <li v-if="0 === $index" class="collection-item avatar">
                            <i class="material-icons circle orange">book</i>
                            <h5>{{course.department.name}}：{{course.name}} - {{ course.code }}</h5>
                        </li>

                        <li class="collection-item avatar">
                            <i class="material-icons circle green">insert_chart</i>
                            <span class="title">{{ course.semester.name }}</span>
                            <p>
                                <span v-for="professor in course.professors" class="professor">{{ professor.name }}</span>
                            </p>
                        </li>
                    </template>
                </ul>
            </div>

            <div id="comments">
            </div>

            <div id="exams">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                courses: []
            };
        },

        methods: {
            pushpin(e) {
                document.body.scrollTop = document.getElementById(e.target.getAttribute('data-target')).offsetTop - 16;
            }
        },

        created() {
            this.$http.get('/api/v1/courses/' + this.$route.params.seriesId).then((response) => {
                this.courses = response.data;
            }, (response) => {
                this.$dispatch('http-response', response, {
                    redirect: {
                        name: 'courses.index'
                    }
                });
            });
        }
    }
</script>
