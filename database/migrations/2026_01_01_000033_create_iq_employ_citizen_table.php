<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_employ_citizen', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('employ_id', 36)->nullable();
            $table->unsignedInteger('citizen_id')->nullable();
            $table->char('file_id', 36)->nullable();
            $table->string('value', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->index('citizen_id');
            $table->index('file_id');
            $table->index('employ_id');
            $table->foreign('employ_id')->references('id')->on('iq_employ')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('citizen_id')->references('id')->on('iq_global_data')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('file_id')->references('id')->on('uploads')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_employ_citizen');
    }
};
