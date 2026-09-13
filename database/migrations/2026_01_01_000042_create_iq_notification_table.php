<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_notification', function (Blueprint $table) {
            $table->increments('id');
            $table->char('user_id', 36)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('module', 255)->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('is_read')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('auth_user')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_notification');
    }
};
