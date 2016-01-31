<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('semester_id');
            $table->string('code', 10);
            $table->unsignedInteger('department_id');
            $table->string('name', 48);

            $table->index('semester_id');
            $table->index('code');
            $table->index('department_id');
            $table->index('name');

            $table->foreign('semester_id')->references('id')->on('categories')
                ->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('categories')
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
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_semester_id_foreign');
            $table->dropForeign('courses_department_id_foreign');
        });

        Schema::drop('courses');
    }
}
