<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_filemanager_user', function (Blueprint $table) {
            $table->unsignedInteger('filemanager_id')->nullable();
            $table->char('user_id', 36)->nullable();
            $table->index('user_id');
            $table->index('filemanager_id');
            $table->foreign('user_id')->references('id')->on('auth_user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('filemanager_id')->references('id')->on('iq_filemanager')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_filemanager_user');
    }
};
