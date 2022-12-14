<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->string('url');
            $table->string('accept')->nullable();
            $table->string('accept_encoding')->nullable();
            $table->string('connection')->nullable();
            $table->string('content_type')->nullable();
            $table->string('content_length')->nullable();
            $table->string('host')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignIdFor(User::class,'user_id')->nullable();
            $table->string('body')->nullable();
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
        Schema::dropIfExists('request_logs');
    }
};
