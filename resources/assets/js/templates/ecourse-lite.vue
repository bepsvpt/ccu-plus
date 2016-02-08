<style lang="scss">
</style>

<template>
    <ul class="collapsible popout" data-collapsible="accordion">
        <li v-for="course in courses">
            <div class="collapsible-header">
                <ul class="collection">
                    <li class="collection-item avatar">
                        <i class="material-icons circle green" style="top: 20%;">book</i>
                        <h5>{{ course.name }}</h5>
                        <span>{{ course.professor }}</span><br>
                        <span>{{ course.department }}</span>
                        <div class="secondary-content hide-on-small-only">
                            <span :class="[course.announcements > 0 ? 'red-text' : 'grey-text']">新公告：{{ course.announcements }}</span><br>
                            <span :class="[course.homework > 0 ? 'red-text' : 'grey-text']">未交作業：{{ course.homework }}</span><br>
                            <span :class="[course.exams > 0 ? 'orange-text' : 'grey-text']">未做測驗：{{ course.exams }}</span>
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
