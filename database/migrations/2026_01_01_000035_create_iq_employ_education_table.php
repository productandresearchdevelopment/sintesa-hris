<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_employ_education', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('employ_id', 36)->nullable();
            $table->unsignedInteger('education_id')->nullable();
            $table->unsignedInteger('major_id')->nullable();
            $table->string('institution', 255)->nullable();
            $table->unsignedInteger('graduate')->nullable();
            $table->float('ipk')->nullable();
            $table->string('description', 255)->nullable();
            $table->char('file_id', 36)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('employ_id');
            $table->index('education_id');
            $table->index('major_id');
            $table->index('file_id');
            $table->foreign('employ_id')->references('id')->on('iq_employ')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('education_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('major_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('file_id')->references('id')->on('uploads')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_employ_education');
    }
};
