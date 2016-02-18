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
                        <i class="material-icons circle green">book</i>
                        <a
                            @click.stop
                            v-link="{name: 'courses.show', params: {code: course.code.substr(0, course.code.indexOf('_'))}}"
                            target="_blank"
                            class="title"
                        >{{ course.name }}</a>
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
                    <!-- Tabs -->
                    <div class="col s12">
                        <ul class="tabs">
                            <li class="tab col s3"><a href="#{{ course.code }}-announcements" class="active">公告</a></li>
                            <li class="tab col s3"><a href="#{{ course.code }}-homework">作業</a></li>
                            <li class="tab col s3"><a href="#{{ course.code }}-grades">成績查詢</a></li>
                            <li class="tab col s3"><a href="#{{ course.code }}-attachments">授課教材</a></li>
                        </ul>
                    </div>

                    <!-- 公告 -->
                    <div id="{{ course.code }}-announcements" class="col offset-s1 s10 tab-content">
                        <ul
                            v-if="course.content.announcements.length > 0"
                            class="collapsible"
                            data-collapsible="expandable"
                        >
                            <li v-for="announcement in course.content.announcements">
                                <div class="collapsible-header">
                                    <i
                                        :class="[isRecent(announcement.date) ? 'green-text' : 'grey-text']"
                                        class="material-icons"
                                    >sms</i>
                                    <span>{{ announcement.title}}</span>
                                    <span
                                        class="right grey-text"
                                        style="font-style: italic;"
                                        data-time-humanize="{{ announcement.date }}"
                                    ></span>
                                </div>

                                <div class="collapsible-body">
                                    <p class="pre-line">{{{ announcement.content | urlify }}}</p>
                                </div>
                            </li>
                        </ul>

                        <h5 v-else class="center">尚無公告</h5>
                    </div>

                    <!-- 作業 -->
                    <div id="{{ course.code }}-homework" class="col offset-s1 s10 tab-content">
                        <table v-if="course.content.homework.length > 0" class="bordered striped centered">
                            <thead>
                                <tr>
                                    <th>作業名稱</th>
                                    <th>繳交期限</th>
                                    <th>已繳交</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="homework in course.content.homework">
                                    <td>
                                        <a v-if="homework.link" href="{{ homework.link }}" target="_blank">{{ homework.name }}</a>
                                        <template v-else>
                                            <a
                                                @click="updateModal(homework.content)"
                                                class="modal-trigger"
                                                href="#homework-content"
                                            >{{ homework.name }}</a>
                                        </template>
                                    </td>
                                    <td>
                                        <span
                                            :class="{'red-text': ! homework.submitted && ! isRecent(homework.date, 3)}"
                                        >{{ homework.date }}
                                            <br class="hide-on-med-and-up">
                                            <span> ( <span data-time-humanize="{{ homework.date}}"></span> )</span>
                                        </span>
                                    </td>
                                    <td>
                                        <i v-if="homework.submitted" class="material-icons green-text">done</i>
                                        <i v-else class="material-icons red-text">clear</i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h5 v-else class="center">尚無作業</h5>
                    </div>

                    <!-- 成績 -->
                    <div id="{{ course.code }}-grades" class="col offset-s1 s10 tab-content">
                        <table class="bordered striped centered">
                            <thead>
                                <tr>
                                    <th>項目</th>
                                    <th>成績</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="grade in course.content.grades">
                                    <td>{{ grade.name }}</td>
                                    <td>{{ grade.value || '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- 教材 -->
                    <div id="{{ course.code }}-attachments" class="col offset-s1 s10 tab-content">
                        <div v-if="course.content.attachments.length > 0" class="collection">
                            <template v-for="attachment in course.content.attachments">
                                <a href="{{ attachment.link }}" target="_blank" class="collection-item">{{ attachment.name }}</a>
                            </template>
                        </div>

                        <h5 v-else class="center">尚無教材</h5>
                    </div>
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

    <div id="homework-content" class="modal">
        <div class="modal-content">
            <p class="pre-line">{{{ $data['homework-content'] }}}</p>
        </div>
    </div>
</template>

<script type="text/babel">
    import ProgressBar from '../components/progress.vue';

    export default {
        data() {
            return {
                courses: [],
                'homework-content': ''
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
            },

            isRecent(date, days = 5) {
                return moment().diff(moment(date), 'days') < days;
            },

            updateModal(content) {
                this.$data['homework-content'] = content;
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
