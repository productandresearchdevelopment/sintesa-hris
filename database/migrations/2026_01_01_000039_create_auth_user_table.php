<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_user', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->unsignedInteger('role_id')->nullable();
            $table->unsignedInteger('organization_id')->nullable();
            $table->char('employ_id', 36)->nullable();
            $table->string('username', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address', 255)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->char('photo_id', 36)->nullable();
            $table->tinyInteger('receive_notif')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('last_ip', 30)->nullable();
            $table->unsignedInteger('last_module')->nullable();
            $table->string('last_url', 255)->nullable();
            $table->dateTime('last_active')->nullable();
            $table->string('email_validation_code', 10)->nullable();
            $table->dateTime('email_validation_sent_at')->nullable();
            $table->dateTime('email_validation_at')->nullable();
            $table->string('remember_token', 150)->nullable();
            $table->longText('property')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->char('token_sso', 36)->nullable();
            $table->unique('username');
            $table->unique('email');
            $table->index('role_id');
            $table->index('photo_id');
            $table->index('created_by');
            $table->index('deleted_at');
            $table->index('organization_id');
            $table->index('employ_id');
            $table->foreign('role_id')->references('id')->on('auth_role')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('photo_id')->references('id')->on('uploads')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('employ_id')->references('id')->on('iq_employ')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('organization_id')->references('id')->on('iq_org')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_user');
    }
};
