<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->char('sha256', 64);
            $table->string('info', 190);
            $table->string('path', 128);
            $table->unsignedSmallInteger('downloads')->default(0);
            $table->unsignedInteger('attachmentable_id');
            $table->string('attachmentable_type', 180);
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();

            $table->unique('sha256');

            $table->index('user_id');
            $table->index('downloads');
            $table->index(['attachmentable_id', 'attachmentable_type']);
            $table->index('deleted_at');

            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign('attachments_user_id_foreign');
        });

        Schema::drop('attachments');
    }
}
