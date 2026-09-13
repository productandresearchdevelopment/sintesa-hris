<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_question_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable();
            $table->string('period_year', 4)->nullable();
            $table->tinyInteger('period_smt')->nullable();
            $table->unsignedInteger('division_id')->nullable();
            $table->tinyInteger('is_locked')->nullable();
            $table->tinyInteger('is_archived')->nullable();
            $table->string('description', 255)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->unique('title');
            $table->index('period_year');
            $table->index('division_id');
            $table->foreign('division_id')->references('id')->on('iq_division')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_question_template');
    }
};
