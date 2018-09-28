<template>
    <!-- 搜尋表單 -->
    <div>
        <div class="row">
            <form class="col s12" @submit.prevent="search()">
                <div class="row">
                    <div class="input-field col s12 offset-m1 m3">
                        <select
                            v-model="form.college"
                            id="college"
                        >
                            <option value="">學院</option>
                            <template v-for="college in colleges">
                                <option :value="college.name">{{ college.name }}</option>
                            </template>
                        </select>
                    </div>

                    <div class="input-field col s12 m3">
                        <select
                            v-model="form.department_id"
                            id="department_id"
                        >
                            <option value="">系所</option>
                            <template v-for="department in departments">
                                <option :value="department.id">{{ department.name }}</option>
                            </template>
                        </select>
                    </div>

                    <div class="input-field col s12 m3">
                        <input
                            v-model="form.keyword"
                            id="keyword"
                            type="text"
                        >
                        <label for="keyword">課程代碼/課程名稱</label>
                    </div>

                    <div class="input-field col s12 m2">
                        <button
                            type="submit"
                            class="btn btn-large waves-effect waves-light"
                            :disabled="loading.courses"
                        ><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 搜尋結果 -->
    <div>
        <table class="bordered highlight centered z-depth-1">
            <thead>
                <tr>
                    <th class="hide-on-small-only">開課系所</th>
                    <th>課程代碼</th>
                    <th>課程名稱</th>
                    <th>授課教授</th>
                </tr>
            </thead>
            <tbody>
                <tr v-show="! loading.courses" v-for="course in courses">
                    <td class="hide-on-small-only">
                        <div style="display: inline-block; vertical-align: middle;">
                            <i
                              v-if="course.new"
                              class="material-icons green-text icon-top tooltipped"
                              style="display: block;"
                              data-position="bottom"
                              data-delay="50"
                              data-tooltip="新課程"
                            >fiber_new</i>

                            <i
                              v-if="course.comments_count > 0"
                              class="material-icons blue-text icon-top tooltipped"
                              style="display: block;"
                              data-position="bottom"
                              data-delay="50"
                              data-tooltip="{{ course.comments_count }} 則評論"
                            >comment</i>
                        </div>

                        <span>{{ course.department.name }}</span>
                    </td>
                    <td>
                        <span>{{ course.code }}</span>

                        <template v-if="course.dimension.length > 0">
                            <br>
                            <span>{{ course.dimension[0].name }}</span>
                        </template>

                        <div class="hide-on-med-and-up">
                            <i
                              v-if="course.new"
                              class="material-icons green-text icon-top"
                            >fiber_new</i>

                            <i
                              v-if="course.comments_count > 0"
                              class="material-icons blue-text icon-top"
                            >comment</i>
                        </div>
                    </td>
                    <td><a v-link="{name: 'courses.show', params: {code: course.code}}">{{ course.name }}</a></td>
                    <td>
                        <template v-for="professor in course.professors | limitBy 5">
                            <span class="truncate">{{ professor.name }}</span>
                            <span v-if="4 === $index" class="truncate">……</span>
                        </template>
                    </td>
                </tr>

                <tr v-show="! loading.courses && 0 === courses.length">
                    <td colspan="5">探索，帶來無限可能！</td>
                </tr>

                <tr v-show="loading.courses">
                    <td colspan="5">
                        <progress-bar :loading="loading.courses"></progress-bar>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <br>

        <div class="row">
            <!--<div class="col s12 m5">-->
                <!--<h5><i class="fa fa-heart"></i> 課程追蹤</h5>-->
            <!--</div>-->

            <div class="col s12 offset-m1 m10">
                <h5><i class="fa fa-comments"></i> 最新評論</h5>

                <template v-for="comment in comments" v-if="!!comment.content">
                    <div class="card hoverable user-select-none" data-comment>
                        <div class="card-content comment-header">
                            <template v-if="null !== comment.user">
                                <strong class="teal-text text-darken-1">{{ comment.user.nickname }}</strong>
                            </template>
                            <template v-else>
                                <span class="grey-text text-darken-1">匿名</span>
                            </template>

                            <a
                                v-link="{name: 'courses.show', params: {code: comment.commentable.code}}"
                                class="right"
                            >{{ comment.commentable.department.name }}：{{ comment.commentable.name }}</a>
                        </div>

                        <div class="card-content" style="padding: 0 20px;">
                            <blockquote class="pre-line" style="margin: 0;">{{ comment.content }}</blockquote>
                        </div>

                        <div class="card-content comment-footer">
                            <span class="green-text"><i class="fa fa-thumbs-o-up"></i> {{ comment.likes }}</span>
                            <span> · </span>
                            <span
                                class="tooltipped"
                                data-tooltip="{{ comment.created_at }}"
                                data-time-humanize="{{ comment.created_at }}"
                            ></span>
                            <a @click="backToTop()" class="right grey-text text-darken-1 back-to-top">Back to top.</a>
                        </div>
                    </div>
                </template>

                <div v-show="loading.comments" class="row">
                    <div class="col s12 center">
                        <br><br>

                        <progress-bar :loading="loading.comments"></progress-bar>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script type="text/babel">
    import ProgressBar from '../../components/progress.vue';

    export default{
        data() {
            return{
                courses: [],
                comments: [],
                colleges: [],
                departments: [],
                form: {
                    college: '',
                    department_id: '',
                    keyword: ''
                },
                selectTouchTimes: 0,
                loading: {
                    comments: false,
                    courses: false
                }
            }
        },

        components: {
            ProgressBar
        },

        methods: {
            backToTop() {
              window.scrollTo(0, 0);
            },

            search() {
                this.loading.courses = true;

                this.$http.get(`/api/v1/courses/search`, this.form).then((response) => {
                    this.courses = response.data;

                    sessionStorage.setItem('courses.search', JSON.stringify(response.data));

                    this.loading.courses = false;

                    ga('send', 'event', 'Course', 'search');
                });
            },

            loadComments() {
                if (! this.loading.comments) {
                    this.loading.comments = true;

                    this.$http.get('/api/v1/courses/waterfall', {
                        id: this.comments[this.comments.length - 1].id
                    }).then((response) => {
                        response.data.map((item) => {
                            this.comments.push(item);
                        });

                        this.loading.comments = false;
                    });
                }
            }
        },

        watch: {
            colleges()  {
                ++this.selectTouchTimes;
            },

            departments() {
                ++this.selectTouchTimes;
            },

            'form["college"]'() {
                let temp = JSON.parse(localStorage.getItem(`colleges.${this.form.college}`));

                if (null !== temp) {
                    this.departments = temp;

                    ++this.selectTouchTimes;
                } else {
                    this.$http.get(`/api/v1/resources/colleges/${this.form.college}`).then((response) => {
                        this.departments = response.data;

                        localStorage.setItem(`colleges.${this.form.college}`, JSON.stringify(response.data));

                        ++this.selectTouchTimes;
                    });
                }
            },

            selectTouchTimes() {
                setTimeout(() => {
                  $('select').material_select()
                }, 1);
            }
        },

        created() {
            let _this = this;

            if (null !== localStorage.getItem('colleges')) {
                this.colleges = JSON.parse(localStorage.getItem('colleges'));
            } else {
                this.$http.get(`/api/v1/resources/colleges`).then((response) => {
                    this.colleges = response.data;

                    localStorage.setItem('colleges', JSON.stringify(response.data));
                });
            }

            // 取得搜尋快取紀錄
            if (null !== sessionStorage.getItem('courses.search')) {
                this.courses = JSON.parse(sessionStorage.getItem('courses.search'));
            }

            this.$http.get('/api/v1/courses/waterfall').then((response) => {
                this.$set('comments', response.data);
            });

            $(document).on('change', '#college, #department_id', function () {
                let id = $(this).attr('id');

                _this.$set(`form['${id}']`, $(this).val());

                if ('college' === id) {
                    _this.$set(`form['department_id']`, '');
                }
            });

            $(window).scroll(function () {
                let comments = $('div[data-comment]');

                if (comments.length >= 5) {
                    let target = comments[comments.length - 2];

                    if (window.innerHeight + window.pageYOffset >= target.offsetTop) {
                        _this.loadComments();
                    }
                }
            });
        }
    }
</script>
