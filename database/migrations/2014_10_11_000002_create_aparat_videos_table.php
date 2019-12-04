<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAparatVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aparat_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('username');
            $table->unsignedBigInteger('userid');
            $table->unsignedBigInteger('visit_cnt')->default(0);
            $table->string('uid')->index()->unique();
            $table->string('process');
            $table->string('sender_name');
            $table->string('big_poster');
            $table->string('small_poster');
            $table->string('profilePhoto');
            $table->unsignedBigInteger('duration');
            $table->string('sdate');
            $table->string('frame');
            $table->string('official');
            $table->text('tags');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('cat_id');
            $table->string('cat_name');
            $table->boolean('autoplay');
            $table->boolean('is_360d');
            $table->string('has_comment');
            $table->string('has_comment_txt');
            $table->unsignedBigInteger('size');
            $table->boolean('can_download');
            $table->unsignedBigInteger('like_cnt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aparat_videos');
    }
}
