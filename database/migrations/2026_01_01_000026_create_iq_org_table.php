<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_org', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('division_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('position_id')->nullable();
            $table->string('alias', 255)->nullable();
            $table->string('path', 255)->nullable();
            $table->string('name', 100)->nullable();
            $table->unsignedInteger('authorized1')->nullable();
            $table->unsignedInteger('authorized2')->nullable();
            $table->string('description', 255)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('parent_id');
            $table->index('company_id');
            $table->index('division_id');
            $table->index('authorized1');
            $table->index('authorized2');
            $table->index('position_id');
            $table->foreign('parent_id')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('iq_company')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('division_id')->references('id')->on('iq_division')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('authorized1')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('authorized2')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('position_id')->references('id')->on('iq_global_data')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_org');
    }
};
