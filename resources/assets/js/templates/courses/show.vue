<style lang="scss">
    .tabs-pushpin {
        margin-top: 180px;

        a {
            user-select: none;
        }
    }

    .comment-footer {
        a, span {
            margin-right: 5px !important;
        }
    }
</style>

<template>
    <div class="row">
        <div class="col hide-on-small-only m3 l2" style="min-height: 1px;">
            <div class="tabs-pushpin">
                <ul class="section table-of-contents">
                    <li><a data-target="info" @click="pushpin"><i class="fa fa-caret-right fa-fw"></i> 課程資訊</a></li>
                    <li><a data-target="comments" @click="pushpin"><i class="fa fa-caret-right fa-fw"></i> 課程評論</a></li>
                    <li><a data-target="exams" @click="pushpin"><i class="fa fa-caret-right fa-fw"></i> 考古題</a></li>
                </ul>
            </div>
        </div>

        <div class="col s12 m9 l10">
            <section id="info">
                <div class="card blue-grey darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">{{ info.name }}</span>
                        <p>{{ info.department.name }} {{ info.code }}</p>
                    </div>

                    <div class="card-action white-text">
                        <div class="row">
                            <div class="col s4 m2">
                                <p><i class="fa fa-info-circle fa-fw fa-inverse"></i> 授課年度</p>
                                <p style="margin-left: 22px;">{{ info.semester.name }}</p>
                            </div>
                            <div class="col s8 m10">
                                <p><i class="fa fa-user fa-fw fa-inverse"></i> 授課教師</p>
                                <p style="margin-left: 22px;">{{ professorsJoin(info.professors) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-action white-text">
                        <p><i class="fa fa-info-circle fa-fw fa-inverse"></i> 授課歷史資料</p>

                        <p style="margin-left: 22px;">
                            <a
                                v-for="course in courses"
                                @click="currentSemester = $index"
                            >{{ course.semester.name }}</a>
                        </p>
                    </div>
                </div>
            </section>

            <br>

            <section id="comments">
                <div v-if="$root.$data.user" class="right-align">
                    <!-- 課程評論按鈕 -->
                    <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
                        <button class="btn-floating btn-large waves-effect waves-light green modal-trigger" data-target="course-comment-modal">
                            <i class="large material-icons">add</i>
                        </button>
                    </div>

                    <!-- 課程評論表單 -->
                    <div id="course-comment-modal" class="modal">
                        <div class="modal-content">
                            <form @submit.prevent="create()">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <legend style="font-size: 24px;"><i class="fa fa-comments"></i> 課程評論</legend>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea
                                            v-model="form.comment.content"
                                            id="content"
                                            class="materialize-textarea validate"
                                            maxlength="3000"
                                            length="3000"
                                            required
                                        ></textarea>
                                        <label for="content">評論...</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12 m6">
                                        <select
                                            v-model="form.comment.professor"
                                            id="professor"
                                            multiple
                                            required
                                        >
                                            <option value="" disabled selected>授課教授</option>
                                            <template v-for="professor in professors">
                                                <option value="{{ professor.id }}">{{ professor.name }}</option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="input-field col s12 m6">
                                        <div class="right">
                                            <input
                                                v-model="form.comment.anonymous"
                                                id="anonymous"
                                                type="checkbox"
                                                class="filled-in"
                                            >
                                            <label for="anonymous">匿名</label>

                                            <button
                                                type="submit"
                                                class="btn waves-effect waves-light"
                                                style="margin-left: 20px; vertical-align: text-top;"
                                            >
                                                <span>送出 <i class="fa fa-send right"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <template v-for="comment in comments.data">
                    <div class="card">
                        <div class="card-content">
                            <template v-if="null !== comment.user">
                                <strong class="teal-text text-darken-1">{{ comment.user.nickname }}</strong>
                            </template>

                            <template v-else>
                                <span class="grey-text text-darken-1">匿名</span>
                            </template>
                        </div>

                        <div class="card-action">
                            <blockquote
                                class="pre-line"
                                style="margin: 0;"
                            ><span>受評教授：{{ professorsJoin(comment.professors) }}</span>


                                <span>{{ comment.content }}</span>
                            </blockquote>
                        </div>

                        <div class="card-action comment-footer">
                            <template v-if="$root.$data.user">
                                <a @click="likeComment(comment)">{{ comment.liked ? '收回讚' : '讚' }}</a>
                                <span> · </span>
                            </template>

                            <span class="green-text"><i class="fa fa-thumbs-o-up"></i> {{ comment.likes }}</span>
                            <span> · </span>
                            <span
                                class="tooltipped"
                                data-tooltip="{{ comment.created_at }}"
                                data-time-humanize="{{ comment.created_at }}"
                            ></span>

                            <template v-if="comment.comments.length">
                                <div>
                                    <template v-for="subComment in comment.comments">
                                        <div class="card">
                                            <div class="card-content">
                                                <template v-if="subComment.user">
                                                    <strong class="teal-text text-darken-1">{{ subComment.user.nickname }}</strong>
                                                </template>

                                                <template v-else>
                                                    <span class="grey-text text-darken-1">匿名</span>
                                                </template>
                                            </div>

                                            <div class="card-action">
                                                <blockquote
                                                    class="pre-line"
                                                    style="margin: 0;"
                                                ><span>{{ subComment.content }}</span></blockquote>
                                            </div>

                                            <div class="card-action comment-footer">
                                                <template v-if="$root.$data.user">
                                                    <a @click="likeComment(subComment)">{{ subComment.liked ? '收回讚' : '讚' }}</a>
                                                    <span> · </span>
                                                </template>

                                                <span class="green-text"><i class="fa fa-thumbs-o-up"></i> {{ subComment.likes }}</span>
                                                <span> · </span>
                                                <span
                                                    class="tooltipped"
                                                    data-tooltip="{{ subComment.created_at }}"
                                                    data-time-humanize="{{ subComment.created_at }}"
                                                ></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template v-if="$root.$data.user">
                                <form @submit.prevent="create()">
                                    <div class="row">
                                        <div class="input-field col s12">
                                        <textarea
                                            v-model="form.subComments[$index].content"
                                            id="content-{{ $index }}"
                                            class="materialize-textarea validate"
                                            maxlength="3000"
                                            length="3000"
                                            required
                                        ></textarea>
                                            <label for="content-{{ $index }}">評論...</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="input-field col s12">
                                            <div class="right">
                                                <input
                                                    v-model="form.subComments[$index].anonymous"
                                                    id="anonymous-{{ $index }}"
                                                    type="checkbox"
                                                    class="filled-in"
                                                >
                                                <label for="anonymous-{{ $index }}">匿名</label>

                                                <button
                                                    type="submit"
                                                    class="btn waves-effect waves-light"
                                                    style="margin-left: 20px; vertical-align: text-top;"
                                                >
                                                    <span>送出 <i class="fa fa-send right"></i></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </template>
                        </div>
                    </div>
                </template>
            </section>

            <br>

            <section id="exams">
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                courses: [],
                currentSemester: 0,
                comments: [],
                form: {
                    comment: {
                        anonymous: false
                    },
                    subComments: []
                }
            };
        },

        computed: {
            info() {
                if (0 === this.courses.length) {
                    return {
                        department: {},
                        semester: {},
                        professors: []
                    };
                }

                return this.courses[this.currentSemester];
            },

            professors() {
                let professors = [];

                this.courses.map((course) => {
                    course.professors.map((professor) => {
                        let exists = false;

                        for (let i in professors) {
                            if (professors.hasOwnProperty(i) && professor.name === professors[i].name) {
                                exists = true;

                                break;
                            }
                        }

                        if (! exists) {
                            professors.push({
                                id: professor.id,
                                name: professor.name
                            });
                        }
                    });
                });

                return professors;
            }
        },

        methods: {
            pushpin(e) {
                document.body.scrollTop = document.getElementById(e.target.getAttribute('data-target')).offsetTop - 16;
            },

            professorsJoin(professors) {
                return professors.map((professor) => {
                    return professor.name;
                }).join('、');
            },

            create() {
                this.$http.post(`/api/v1/courses/${this.$route.params.code}/comments`, this.form.comment).then((response) => {
                    this.$dispatch('http-response', response, {
                        'modal-close': '#course-comment-modal'
                    });

                    this.comments.data.unshift(response.data);

                    this.form.comment.content = '';
                }, (response) => {
                    this.$dispatch('http-response', response);
                });
            },

            likeComment(comment) {
                this.$http.patch(`/api/v1/courses/${this.$route.params.code}/comments/${comment.id}/like`).then((response) => {
                    comment.likes = response.data.likes;
                    comment.liked = !comment.liked;
                }, (response) => {
                    this.$dispatch('http-response', response);
                });
            }
        },

        watch: {
            professors() {
                $('select').material_select();
            }
        },

        created() {
            let _this = this;

            this.$http.get(`/api/v1/courses/${this.$route.params.code}`).then((response) => {
                this.courses = response.data;
            }, (response) => {
                this.$dispatch('http-response', response, {
                    redirect: {
                        name: 'courses.index'
                    }
                });
            });

            this.$http.get(`/api/v1/courses/${this.$route.params.code}/comments`).then((response) => {
                this.comments = response.data;
            });

            $(document).on('change', '#professor', function () {
                _this.$set(`form.comment['professor']`, $(this).val());
            });
        }
    }
</script>
