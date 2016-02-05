<style lang="scss">
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
            <table class="bordered striped highlight centered">
                <thead>
                    <tr>
                        <th>學期</th>
                        <th>開課系所</th>
                        <th>課程代碼</th>
                        <th>課程名稱</th>
                        <th>授課教授</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in courses">
                        <td>{{ course.semester.name }}</td>
                        <td>{{ course.department.name }}</td>
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

        <br>

        <div></div>
    </div>
</template>

<script>
    export default{
        data() {
            return{
                courses: [],
                colleges: [],
                departments: [],
                form: {
                    college: '',
                    department_id: '',
                    keyword: ''
                },
                selectTouchTimes: 0
            }
        },

        methods: {
            search() {
                this.$http.get(`/api/v1/courses/search`, this.form).then((response) => {
                    this.courses = response.data;
                });
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

            $(document).on('change', '#college, #department_id', function () {
                _this.$set(`form['${$(this).attr('id')}']`, $(this).val());
            });
        }
    }
</script>