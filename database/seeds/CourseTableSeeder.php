<?php

use App\Ccu\Course;
use App\Ccu\General\Category;
use App\Ccu\User\User;
use Illuminate\Database\Seeder;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $professors = Category::getCategories('professor');

        $dimensions = Category::getCategories('course.dimension');

        $generalDepartmentId = Category::getCategories('department', '通識中心', true);

        // 課程
        factory(Course::class, mt_rand(30, 80))
            ->create()
            ->each(function (Course $course) use ($users, $professors, $dimensions, $generalDepartmentId) {
                // 通識課程
                if ($course->getAttribute('department_id') === $generalDepartmentId) {
                    $course->dimension()->save($dimensions->random());
                }

                // 課程教授
                //$course->professors()->saveMany($professors->random(mt_rand(2, 3)));
            });
    }
}
