<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('game_id')->nullable();
            $table->string('game_name')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('participants')->nullable();
            $table->integer('pool_price')->nullable();
            $table->integer('winning_price')->nullable();
            $table->integer('game_commission')->nullable();
            $table->integer('final_price')->nullable();
            $table->enum('status', ['0', '1'])->default('0')->comment('0=>deactive,1=>active');
            $table->enum('challenge_status', ['0', '1'])->default('0')->comment('0=>running,1=>close');
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
        Schema::dropIfExists('challenges');
    }
}
