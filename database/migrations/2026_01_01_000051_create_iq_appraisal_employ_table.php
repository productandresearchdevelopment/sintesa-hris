<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_employ', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->unsignedInteger('period_id')->nullable();
            $table->unsignedInteger('template_id')->nullable();
            $table->char('employ_id', 36)->nullable();
            $table->float('total_point')->nullable();
            $table->unsignedInteger('grade')->nullable();
            $table->char('evaluator1_by', 36)->nullable();
            $table->dateTime('evaluator1_at')->nullable();
            $table->char('evaluator2_by', 36)->nullable();
            $table->dateTime('evaluator2_at')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('employ_id');
            $table->index('evaluator1_by');
            $table->index('evaluator2_by');
            $table->index('period_id');
            $table->index('template_id');
            $table->foreign('period_id')->references('id')->on('iq_appraisal_period_organization')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('employ_id')->references('id')->on('iq_employ')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('evaluator1_by')->references('id')->on('iq_employ')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('evaluator2_by')->references('id')->on('iq_employ')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('template_id')->references('id')->on('iq_appraisal_question_template')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_employ');
    }
};
