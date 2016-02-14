<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassAndCreditAndSyllabusColumnsToCourseProfessorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_professor', function (Blueprint $table) {
            $table->char('class', 2);
            $table->unsignedTinyInteger('credit');
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
            $table->dropColumn(['class', 'credit']);
        });
    }
}
