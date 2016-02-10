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
            <section id="info">
                <div class="card blue-grey darken-1">
                    <div class="card-content white-text">
                        <span class="card-title">{{ info.name }}</span>
                        <p>{{ info.department.name }} {{ info.code }}</p>
                    </div>

                    <div class="card-action white-text">
                        <div class="row">
                            <div class="col s4 m2">
                                <p><i class="material-icons icon-top">info</i> 授課年度</p>
                                <p style="margin-left: 25px;">{{ info.semester.name }}</p>
                            </div>
                            <div class="col s8 m10">
                                <p><i class="material-icons icon-top">person</i> 授課教師</p>
                                <p style="margin-left: 25px;">{{ professorsJoin(info.professors) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-action white-text">
                        <p><i class="material-icons icon-top">info</i> 授課歷史資料</p>

                        <p style="margin-left: 25px;">
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
                <h5><i class="material-icons" style="vertical-align: bottom">message</i> 課程評論</h5>

                <div v-if="$parent.$data['user']">
                    <div class="row">
                        <form @submit.prevent="create()" class="col s12">
                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea
                                        v-model="form.content"
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
                                        v-model="form.professor"
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
                                            v-model="form.anonymous"
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
                                            <span>送出 <i class="material-icons right">send</i></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <template v-for="comment in comments.data">
                    <div class="card">
                        <div class="card-content comment-header">
                            <template v-if="null !== comment.user">
                                <strong class="teal-text text-darken-1">{{ comment.user.nickname }}</strong>
                            </template>
                            <template v-else>
                                <span class="grey-text text-darken-1">匿名</span>
                            </template>
                        </div>

                        <div class="card-content comment-content">
                            <blockquote class="pre-line"><span>受評教授：{{ professorsJoin(comment.professors) }}</span>


                                <span>{{ comment.content }}</span>
                            </blockquote>
                        </div>

                        <div class="card-content comment-footer">
                            <span>讚</span>
                            <span> · </span>
                            <span class="green-text"><i class="fa fa-thumbs-o-up"></i> {{ comment.likes }}</span>
                            <span> · </span>
                            <span
                                class="tooltipped"
                                data-tooltip="{{ comment.created_at }}"
                                data-time-humanize="{{ comment.created_at }}"
                            ></span>
                            <span> · </span>
                            <span>留言</span>
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
                    anonymous: false
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
                this.$http.post(`/api/v1/courses/${this.$route.params.seriesId}/comments`, this.form).then((response) => {
                    this.$dispatch('http-response', response);

                    this.comments.data.unshift(response.data);

                    this.form.content = '';
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

            this.$http.get(`/api/v1/courses/${this.$route.params.seriesId}`).then((response) => {
                this.courses = response.data;
            }, (response) => {
                this.$dispatch('http-response', response, {
                    redirect: {
                        name: 'courses.index'
                    }
                });
            });

            this.$http.get(`/api/v1/courses/${this.$route.params.seriesId}/comments`).then((response) => {
                this.comments = response.data;
            });

            $(document).on('change', '#professor', function () {
                _this.$set(`form['professor']`, $(this).val());
            });
        }
    }
</script>
