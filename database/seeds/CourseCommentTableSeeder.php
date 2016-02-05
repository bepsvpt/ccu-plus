<?php

use App\Ccu\Course;
use App\Ccu\General\Comment;
use App\Ccu\General\Like;
use App\Ccu\User\User;
use Illuminate\Database\Seeder;

class CourseCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        Course::all()->each(function (Course $course) use ($users) {
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
        });
    }
}
