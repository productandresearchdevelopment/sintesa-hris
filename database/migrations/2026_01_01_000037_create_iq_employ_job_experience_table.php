<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_employ_job_experience', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('employ_id', 36)->nullable();
            $table->string('name', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('job_title', 255)->nullable();
            $table->string('job_description', 255)->nullable();
            $table->unsignedInteger('salary')->nullable();
            $table->string('reason_leaving', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->index('employ_id');
            $table->foreign('employ_id')->references('id')->on('iq_employ')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_employ_job_experience');
    }
};
