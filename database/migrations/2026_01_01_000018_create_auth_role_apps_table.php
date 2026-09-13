<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_role_apps', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->char('app_id', 36);
            $table->primary(['role_id', 'app_id']);
            $table->foreign('role_id')->references('id')->on('auth_role')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('app_id')->references('id')->on('auth_apps')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_role_apps');
    }
};
