<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_question', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('template_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->string('group_kpi', 255)->nullable();
            $table->text('question')->nullable();
            $table->text('formula_description')->nullable();
            $table->float('weight')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('category_id');
            $table->foreign('category_id')->references('id')->on('iq_appraisal_question_category')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_question');
    }
};
