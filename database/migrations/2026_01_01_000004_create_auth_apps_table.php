<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_apps', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('name', 255)->nullable();
            $table->string('token', 255)->nullable();
            $table->longText('ip')->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('url', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_apps');
    }
};
