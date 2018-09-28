<template>
    <!-- 課程資訊 -->
    <section>
        <div class="card blue-grey darken-1">
            <div class="card-content white-text">
                <div class="row" style="margin-bottom: 0;">
                    <div class="col s9 m11">
                        <span class="card-title">{{ info.name }}</span>
                        <p>{{ info.department.name }} {{ info.code }}</p>
                    </div>

                    <div v-if="info.professors.length" class="col s3 m1 center">
                        <h3 style="margin: 0;">{{ info.professors[0].pivot.credit }}</h3>
                        <h6 style="margin: 0.3rem 0 0 0;">學分</h6>
                    </div>
                </div>
            </div>

            <div class="card-action white-text">
                <div class="row">
                    <div class="col s4 m2">
                        <p><i class="fa fa-info-circle fa-fw fa-inverse"></i> 授課年度</p>
                        <p style="margin-left: 22px;">{{ info.semester.name }}</p>
                    </div>
                    <div class="col s8 m10">
                        <p><i class="fa fa-user fa-fw fa-inverse"></i> 授課教師</p>
                        <p style="margin-left: 22px;">{{{ professorsJoin(info) }}}</p>
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

    <div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab col s12"><a href="#comments" class="active"><i class="fa fa-comments fa-fw"></i> 課程評論</a></li>
                <!--<li class="tab col s6"><a href="#exams"><i class="fa fa-file fa-fw"></i> 考古題</a></li>-->
            </ul>
        </div>

        <!-- 課程評論 -->
        <section id="comments" class="col s12 user-select-none">
            <template v-if="$root.$data.user">
                <!-- 課程評論按鈕 -->
                <div v-if="! reply" class="fixed-action-btn">
                    <button
                        @click="$set('reply', true)"
                        class="btn-floating btn-large waves-effect waves-light green"
                    ><i class="large material-icons">add</i>
                    </button>
                </div>

                <!-- 課程評論表單 -->
                <div v-if="reply">
                    <br>

                    <form @submit.prevent="create(form.comment, comments)">
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea
                                    v-model="form.comment.content"
                                    id="content"
                                    class="materialize-textarea validate"
                                    maxlength="3000"
                                    length="3000"
                                    autofocus
                                    required
                                ></textarea>
                                <label for="content">評論</label>
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
                                        class="btn btn-large waves-effect waves-light"
                                        style="margin-left: 35px; vertical-align: text-top;"
                                    >
                                        <span><i class="fa fa-send"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <br>
                </div>
            </template>

            <template v-for="comment in comments">
                <div class="card">
                    <div class="card-content" style="padding: 10px 20px;">
                        <div class="row" style="margin-bottom: 0;">
                            <div class="col s3 m1 center" style="padding: 0 0 0 0.75rem;">
                                <div class="pre-line">
                                    <strong v-if="comment.user" class="teal-text text-darken-1">{{ comment.user.nickname }}</strong>
                                    <span v-else class="grey-text text-darken-1">匿名</span>
                                </div>

                                <a v-if="!!comment.content" @click="likeComment(comment)" class="green-text" style="font-size: 1.3em;">
                                    <i :class="[comment.liked ? 'fa-thumbs-up' : 'fa-thumbs-o-up']" class="fa"></i>
                                    <span>{{ comment.likes }}</span>
                                </a>

                                <template v-if="$root.$data.user && ! comment.reply && !!comment.content">
                                    <br><br>

                                    <a @click="$set('form.subComments[$index]', {}) & $set('comment.reply', true)"><i class="fa fa-reply"></i> 回覆</a>
                                </template>
                            </div>

                            <div class="col s9 m11">
                                <blockquote class="pre-line"><!--
                                 --><span>受評教授：{{ professorsJoin(comment) }}</span><br><br><br><!--
                                 --><span v-if="!!comment.content">{{ comment.content }}</span><!--
                                 --><i v-else class="grey-text">應教授或第三方要求，此評論已被刪除</i><br><!--
                                 --><span>　</span><span class="grey-text right" style="font-style: italic;">— <span data-time-humanize="{{ comment.created_at }}"></span></span><!--
                             --></blockquote>

                                <div v-if="comment.comments.length" class="card-action" style="padding: 0;">
                                    <template v-for="subComment in comment.comments">
                                        <div class="row" style="margin-bottom: 0;">
                                            <div class="col s2 m1 center" style="padding: 0 0 0 0.75rem;">
                                                <div class="pre-line">
                                                    <strong v-if="subComment.user" class="teal-text text-darken-1">{{ subComment.user.nickname }}</strong>
                                                    <span v-else class="grey-text text-darken-1">匿名</span>
                                                </div>

                                                <a v-if="!!subComment.content" @click="likeComment(subComment)" class="green-text" style="margin-right: 0; font-size: 1.3em;">
                                                    <i :class="[subComment.liked ? 'fa-thumbs-up' : 'fa-thumbs-o-up']" class="fa"></i>
                                                    <span>{{ subComment.likes }}</span>
                                                </a>
                                            </div>

                                            <div class="col s10 m11">
                                                <blockquote class="pre-line"><!--
                                                 --><span v-if="!!subComment.content">{{ subComment.content }}</span><!--
                                                 --><i v-else class="grey-text">應教授或第三方要求，此評論已被刪除</i><br><!--
                                                 --><span>　</span><span class="grey-text right" style="font-style: italic;">— <span data-time-humanize="{{ subComment.created_at }}"></span></span><!--
                                             --></blockquote>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div v-if="$root.$data.user && comment.reply" class="card-action" style="padding: 0;">
                                    <form @submit.prevent="create(form.subComments[$index], comment.comments)" style="margin-top: 15px;">
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
                                                <label for="content-{{ $index }}">回覆</label>
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

                                                    <input
                                                        v-model="form.subComments[$index].comment_id"
                                                        type="hidden"
                                                        value="{{ comment.id }}"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn btn-large waves-effect waves-light"
                                                        style="margin-left: 35px; vertical-align: text-top;"
                                                    >
                                                        <i class="fa fa-reply"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div v-if="! comments.length" class="center">
                <h4 class="grey-text"><i class="material-icons large">chat_bubble_outline</i></h4>

                <h5>您上過這堂課嗎？</h5>

                <h5>點擊右下角的按鈕，成為第一位分享者吧！</h5>
            </div>
        </section>

        <!-- 考古題 -->
        <section id="exams" class="col s12">
        </section>
    </div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {
                courses: [],
                currentSemester: 0,
                comments: [],
                form: {
                    comment: {},
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

            professorsJoin(info) {
                let year, term;

                if (info.hasOwnProperty('code')) {
                    year = info.semester.name.substr(0, info.semester.name.length - 1);
                    term = info.semester.name.includes('上') ? 1 : 2;
                }

                return info.professors.map((professor) => {
                    if (! info.hasOwnProperty('code')) {
                        return professor.name;
                    }

                    let query = `courseno=${info.code}_${professor.pivot.class}&year=${year}&term=${term}`;

                    return `<a href="https://ecourse.ccu.edu.tw/php/Courses_Admin/guest3.php?${query}" target="_blank" style="margin-right: 0;">${professor.name}</a>`;
                }).join('、');
            },

            create(form, target) {
                this.$http.post(`/api/v1/courses/${this.$route.params.code}/comments`, form).then((response) => {
                    this.$dispatch('http-response', response);

                    target.unshift(response.data);

                    form.content = '';

                    $('textarea').trigger('autoresize');
                }, (response) => {
                    this.$dispatch('http-response', response);
                });
            },

            likeComment(comment) {
                if (! this.$root.$data.user) {
                    return;
                }

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
