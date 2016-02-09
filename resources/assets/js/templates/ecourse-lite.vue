<style lang="scss">
    .tab-content {
        margin-top: 1.75rem;
    }
</style>

<template>
    <ul v-if="courses.length > 0" class="collapsible popout" data-collapsible="accordion">
        <li v-for="course in courses">
            <div @click="touchCourse(course)" class="collapsible-header">
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
            <div class="collapsible-body">
                <div v-if="course.touch" class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s3"><a href="#{{ course.code }}-announcements" class="active">公告</a></li>
                            <li class="tab col s3"><a href="#{{ course.code }}-homework">作業</a></li>
                            <li class="tab col s3"><a href="#{{ course.code }}-grades">成績查詢</a></li>
                            <li class="tab col s3"><a href="#{{ course.code }}-attachments">授課教材</a></li>
                        </ul>
                    </div>
                    <div id="{{ course.code }}-announcements" class="col offset-s1 s10 tab-content">
                        <ul
                            v-if="course.content.announcements.length > 0"
                            class="collapsible"
                            data-collapsible="expandable"
                        >
                            <li v-for="announcement in course.content.announcements">
                                <div class="collapsible-header">
                                    <i class="material-icons">sms</i>
                                    <span>{{ announcement.title}}</span>
                                    <span
                                        class="right grey-text"
                                        style="font-style: italic;"
                                        data-time-humanize="{{ announcement.date}}"
                                    ></span>
                                </div>
                                <div class="collapsible-body">
                                    <p style="white-space: pre-line;">{{{ announcement.content | urlify }}}</p>
                                </div>
                            </li>
                        </ul>
                        <h5 v-else class="center">尚無公告</h5>
                    </div>
                    <div id="{{ course.code }}-homework" class="col offset-s1 s12 tab-content">這是作業</div>
                    <div id="{{ course.code }}-grades" class="col offset-s1 s12 tab-content">這是成績查詢</div>
                    <div id="{{ course.code }}-attachments" class="col offset-s1 s12 tab-content">這是授課教材</div>
                </div>

                <div v-else class="center" style="margin: 20px 0;">
                    <progress-bar :loading="! course.touch"></progress-bar>
                </div>
            </div>
        </li>
    </ul>
    <div v-else class="center">
        <progress-bar :loading="0 === courses.length"></progress-bar>
    </div>
</template>

<script>
    import ProgressBar from '../components/progress.vue';

    export default {
        data() {
            return {
                courses: []
            };
        },

        components: {
            ProgressBar
        },

        methods: {
            touchCourse(course) {
                if (! course.touch) {
                    this.$http.get(`/api/v1/ecourse-lite/course-content/${course.courseId}`).then((response) => {
                        course.content = response.data;
                        course.touch = true;
                    });
                }
            }
        },

        created() {
            this.$http.get('/api/v1/ecourse-lite/course-list').then((response) => {
                response.data.map(function (item) {
                    item.content = {};

                    item.touch = false;
                });

                this.courses = response.data;
            });
        }
    }
</script>
