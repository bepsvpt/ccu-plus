<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseProfessorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_professor', function (Blueprint $table) {
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('professor_id');

            $table->primary(['course_id', 'professor_id']);

            $table->foreign('course_id')->references('id')->on('courses')
                ->onUpdate('cascade');
            $table->foreign('professor_id')->references('id')->on('categories')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_professor', function (Blueprint $table) {
            $table->dropForeign('course_professor_course_id_foreign');
            $table->dropForeign('course_professor_professor_id_foreign');
        });

        Schema::drop('course_professor');
    }
}
