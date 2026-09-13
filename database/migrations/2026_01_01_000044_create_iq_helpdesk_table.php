<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_helpdesk', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('organization_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('last_answer_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->longText('message')->nullable();
            $table->string('status', 255)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->index('organization_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index('category_id');
            $table->index('last_answer_id');
            $table->foreign('organization_id')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('iq_helpdesk_category')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_helpdesk');
    }
};
