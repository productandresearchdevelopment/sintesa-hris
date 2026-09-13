<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->nullable();
            $table->string('alias', 10)->nullable();
            $table->unsignedInteger('home')->nullable();
            $table->char('color', 6)->nullable();
            $table->string('description', 50)->nullable();
            $table->longText('property')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->index('home');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_role');
    }
};
