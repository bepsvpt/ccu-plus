<?php

use App\Ccu\Course;
use App\Ccu\General\Attachment;
use App\Ccu\User\User;
use Illuminate\Database\Seeder;

class CourseAttachmentTableSeeder extends Seeder
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
            $course->exams()->save(factory(Attachment::class)->make([
                'user_id' => $users->random()->getAttribute('id'),
            ]));
        });
    }
}
