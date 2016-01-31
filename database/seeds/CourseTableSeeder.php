<?php

use App\Ccu\Course;
use App\Ccu\General\Attachment;
use App\Ccu\General\Category;
use App\Ccu\General\Comment;
use App\Ccu\General\Like;
use App\Ccu\User;
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
                $course->professors()->saveMany($professors->random(mt_rand(2, 3)));

                // 課程評論
                $course->comments()->saveMany(factory(Comment::class, mt_rand(2, 5))->make([
                    'user_id' => $users->random()->getAttribute('id'),
                ]))->each(function (Comment $comment) use ($users) {
                    // 評論的評論
                    $comment->comments()->save(factory(Comment::class)->make([
                        'user_id' => $users->random()->getAttribute('id'),
                    ]));

                    // 評論按讚
                    $likes = mt_rand(2, 5);

                    $comment->likes()->saveMany(factory(Like::class, $likes)->make([
                        'user_id' => $users->random()->getAttribute('id'),
                    ]));

                    $comment->update(['likes' => $likes]);
                });

                // 附件
                $course->exams()->save(factory(Attachment::class)->make([
                    'user_id' => $users->random()->getAttribute('id'),
                ]));
            });
    }
}
