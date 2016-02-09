<style lang="scss">
</style>

<template>
    <ul class="collapsible popout" data-collapsible="accordion">
        <li v-for="course in courses">
            <div class="collapsible-header">
                <ul class="collection">
                    <li class="collection-item avatar">
                        <i class="material-icons circle green" style="top: 17%;">book</i>
                        <span class="title">{{ course.name }}</span>
                        <p style="color: rgba(0,0,0,0.7);">{{ course.professor }}<br>{{ course.department }}</p>
                        <div class="secondary-content hide-on-small-only">
                            <i
                                :class="[
                                    !!course.announcements ? 'light-green-text' : 'blue-grey-text',
                                    !!course.announcements ? '' : 'text-lighten-3'
                                ]"
                                class="material-icons tooltipped"
                                data-tooltip="{{ !!course.announcements ? course.announcements + '則新公告' : '無新公告' }}"
                            >comment</i>

                            <i
                                :class="[
                                    !!course.homework ? 'red-text' : 'blue-grey-text',
                                    !!course.homework ? 'text-darken-4' : 'text-lighten-3'
                                ]"
                                class="material-icons tooltipped"
                                data-tooltip="{{ '未交作業：' + course.homework }}"
                            >assignment</i>

                            <i
                                :class="[
                                    !!course.exams ? 'red-text' : 'blue-grey-text',
                                    !!course.exams ? 'text-darken-4' : 'text-lighten-3'
                                ]"
                                class="material-icons tooltipped"
                                data-tooltip="{{ '未做測驗：' + course.exams }}"
                            >description</i>
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
