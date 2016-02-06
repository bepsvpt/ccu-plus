<style lang="scss">
    .back-to-top {
        cursor: pointer;
    }

    .comment {
        &-header {
            padding: 20px 20px 5px !important;
        }

        &-content {
            padding: 5px 20px !important;
        }

        &-footer {
             padding: 5px 20px 20px !important;
        }
    }
</style>

<template>
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
                            class="validate"
                        >
                        <label for="keyword">課程代碼/課程名稱</label>
                    </div>

                    <div class="input-field col s12 m2">
                        <button class="btn waves-effect waves-light" type="submit" name="action">
                            <span>搜尋</span><i class="material-icons right">search</i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div>
            <table class="bordered striped highlight centered z-depth-1">
                <thead>
                    <tr>
                        <th>學期</th>
                        <th class="hide-on-small-only">開課系所</th>
                        <th>課程代碼</th>
                        <th>課程名稱</th>
                        <th>授課教授</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in courses">
                        <td>{{ course.semester.name }}</td>
                        <td class="hide-on-small-only">{{ course.department.name }}</td>
                        <td>{{ course.code }}</td>
                        <td>
                            <template v-if="course.dimension.length > 0">
                                <a
                                    v-link="{name: 'courses.show', params: {seriesId: course.series_id}}"
                                    class="tooltipped"
                                    data-position="bottom"
                                    data-delay="100"
                                    data-tooltip="{{ course.dimension[0].name }}"
                                >{{ course.name }}</a>
                            </template>
                            <template v-else>
                                <a v-link="{name: 'courses.show', params: {seriesId: course.series_id}}">{{ course.name }}</a>
                            </template>
                        </td>
                        <td>
                            <template v-for="professor in course.professors | limitBy 5">
                                <span class="truncate">{{ professor.name }}</span>
                                <span v-if="4 === $index" class="truncate">……</span>
                            </template>
                        </td>
                    </tr>
                    <tr v-show="0 === courses.length">
                        <td colspan="5">探索，帶來無限可能！</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br><br>

        <div>
            <div class="row">
                <div class="col s12 m7">
                    <template v-for="comment in comments">
                        <div class="card hoverable" data-comment>
                            <div class="card-content comment-header">
                                <template v-if="null !== comment.user">
                                    <strong class="teal-text text-darken-1">{{ comment.user.nickname }}</strong>
                                </template>
                                <template v-else>
                                    <span class="grey-text text-darken-1">匿名</span>
                                </template>

                                <a
                                    v-link="{name: 'courses.show', params: {seriesId: comment.commentable.series_id}}"
                                    class="right"
                                >{{ comment.commentable.department.name }}：{{ comment.commentable.name }}</a>
                            </div>

                            <div class="card-content comment-content">
                                <blockquote>{{ comment.content }}</blockquote>
                            </div>

                            <div class="card-content comment-footer">
                                <span class="green-text"><i class="fa fa-thumbs-o-up"></i> {{ comment.likes }}</span>
                                <span> · </span>
                                <span
                                    class="tooltipped"
                                    data-position="bottom"
                                    data-delay="100"
                                    data-tooltip="{{ comment.created_at }}"
                                    data-time-humanize="{{ comment.created_at }}"
                                ></span>
                                <a @click="backToTop()" class="right grey-text text-darken-1 back-to-top">Back to top.</a>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="col s12 m5"></div>
            </div>

        </div>
    </div>
</template>

<script>
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
                loadingComment: false
            }
        },

        methods: {
            backToTop() {
                document.body.scrollTop = 0;
            },

            search() {
                this.$http.get(`/api/v1/courses/search`, this.form).then((response) => {
                    this.courses = response.data;
                });
            },

            loadComments() {
                if (! this.loadingComment) {
                    this.loadingComment = true;

                    this.$http.get('/api/v1/courses/waterfall', {
                        id: this.comments[this.comments.length - 1].id
                    }).then((response) => {
                        response.data.map((item) => {
                            this.comments.push(item);
                        });

                        this.loadingComment = false;
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
                $('select').material_select();
            }
        },

        created() {
            var _this = this;
            let colleges = JSON.parse(localStorage.getItem('colleges'));

            if (null !== colleges) {
                this.colleges = colleges;
            } else {
                this.$http.get(`/api/v1/resources/colleges`).then((response) => {
                    this.colleges = response.data;

                    localStorage.setItem('colleges', JSON.stringify(response.data));
                });
            }

            this.$http.get('/api/v1/courses/waterfall').then((response) => {
                this.$set('comments', response.data);
            });

            $(document).on('change', '#college, #department_id', function () {
                _this.$set(`form['${$(this).attr('id')}']`, $(this).val());
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
