<?php

namespace Tests\Model;

use App\Ccu\Course;
use App\Ccu\General\Attachment;
use App\Ccu\General\Category;
use App\Ccu\General\Comment;
use Tests\TestCase;

class CourseTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_define_semester_relationship()
    {
        $course = new Course;

        $this->assertTrue(method_exists($course, 'semester'));
        $this->assertEquals(new Category, $course->semester()->getRelated());
    }

    /**
     * @test
     */
    public function it_should_define_department_relationship()
    {
        $course = new Course;

        $this->assertTrue(method_exists($course, 'department'));
        $this->assertEquals(new Category, $course->department()->getRelated());
    }

    /**
     * @test
     */
    public function it_should_define_dimension_relationship()
    {
        $course = new Course;

        $this->assertTrue(method_exists($course, 'dimension'));
        $this->assertEquals(new Category, $course->dimension()->getRelated());
    }

    /**
     * @test
     */
    public function it_should_define_professors_relationship()
    {
        $course = new Course;

        $this->assertTrue(method_exists($course, 'professors'));
        $this->assertEquals(new Category, $course->professors()->getRelated());
    }

    /**
     * @test
     */
    public function it_should_define_comments_relationship()
    {
        $course = new Course;

        $this->assertTrue(method_exists($course, 'comments'));
        $this->assertEquals(new Comment, $course->comments()->getRelated());
    }

    /**
     * @test
     */
    public function it_should_define_exams_relationship()
    {
        $course = new Course;

        $this->assertTrue(method_exists($course, 'exams'));
        $this->assertEquals(new Attachment, $course->exams()->getRelated());
    }
}
