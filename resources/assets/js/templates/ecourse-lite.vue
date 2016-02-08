<style lang="scss">
</style>

<template>
    <ul class="collapsible popout" data-collapsible="accordion">
        <li v-for="course in courses">
            <div class="collapsible-header">
                <ul class="collection">
                    <li class="collection-item avatar">
                        <i class="material-icons circle green" style="top: 20%;">book</i>
                        <h5 style="margin: 0.82rem 0 0.3rem;">{{ course.name }}</h5>
                        <span>{{ course.professor }}</span><br>
                        <span>{{ course.department }}</span>
                        <div class="secondary-content hide-on-small-only">
                            <i
                                :class="[
                                    !!course.announcements ? 'light-green-text' : 'blue-grey-text',
                                    !!course.announcements ? '' : 'text-lighten-3'
                                ]"
                                class="material-icons tooltipped"
                                data-position="bottom"
                                data-delay="100"
                                data-tooltip="{{ !!course.announcements ? course.announcements + '則新公告' : '無新公告' }}"
                            >comment</i>

                            <i
                                :class="[
                                    !!course.homework ? 'light-green-text' : 'blue-grey-text',
                                    !!course.homework ? '' : 'text-lighten-3'
                                ]"
                                class="material-icons tooltipped"
                                data-position="bottom"
                                data-delay="100"
                                data-tooltip="{{ '未交作業：' + course.homework }}"
                            >assignment</i>

                            <i
                                :class="[
                                    !!course.exams ? 'light-green-text' : 'blue-grey-text',
                                    !!course.exams ? '' : 'text-lighten-3'
                                ]"
                                class="material-icons tooltipped"
                                data-position="bottom"
                                data-delay="100"
                                data-tooltip="{{ '未做測驗：' + course.exams }}"
                            >description</i>
                            <!--<span>新公告：{{ course.announcements }}</span>-->
                            <!--<span>未交作業：{{ course.homework }}</span>-->
                            <!--<span>未做測驗：{{ course.exams }}</span>-->
                        </div>
                    </li>
                </ul>
            </div>
            <div class="collapsible-body"><p>Coding</p></div>
        </li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {
                courses: []
            };
        },

        created() {
            this.$http.get('/api/v1/ecourse-lite/course-list').then((response) => {
                this.courses = response.data;
            });
        }
    }
</script>
