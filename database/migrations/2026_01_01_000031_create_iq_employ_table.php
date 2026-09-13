<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_employ', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->unsignedInteger('org_id')->nullable();
            $table->unsignedInteger('division_id')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('placement_id')->nullable();
            $table->char('last_contract_id', 36)->nullable();
            $table->char('last_career_id', 36)->nullable();
            $table->unsignedInteger('tax_id')->nullable();
            $table->string('nik', 50)->nullable();
            $table->string('nickname', 100)->nullable();
            $table->string('fullname', 150)->nullable();
            $table->string('birth_place', 150)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->unsignedInteger('gender_id')->nullable();
            $table->unsignedInteger('marital_id')->nullable();
            $table->unsignedInteger('religion_id')->nullable();
            $table->date('join_date')->nullable();
            $table->unsignedInteger('leave_saldo')->nullable();
            $table->string('address', 255)->nullable();
            $table->unsignedInteger('address_city_id')->nullable();
            $table->unsignedInteger('address_province_id')->nullable();
            $table->string('address_permanent', 255)->nullable();
            $table->unsignedInteger('address_permanent_city_id')->nullable();
            $table->unsignedInteger('address_permanent_province_id')->nullable();
            $table->unsignedInteger('bank_id')->nullable();
            $table->string('bank_account', 255)->nullable();
            $table->string('bank_alias', 255)->nullable();
            $table->unsignedInteger('emergency_relation_id')->nullable();
            $table->string('emergency_contact_name', 150)->nullable();
            $table->string('emergency_contact_phone', 60)->nullable();
            $table->string('emergency_contact_address', 255)->nullable();
            $table->time('shift_start_time')->nullable();
            $table->time('shift_end_time')->nullable();
            $table->unsignedInteger('office_id')->nullable();
            $table->char('photo_id', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->index('org_id');
            $table->index('photo_id');
            $table->index('gender_id');
            $table->index('marital_id');
            $table->index('religion_id');
            $table->index('bank_id');
            $table->index('emergency_relation_id');
            $table->index('division_id');
            $table->index('company_id');
            $table->index('placement_id');
            $table->index('address_city_id');
            $table->index('address_province_id');
            $table->index('address_permanent_city_id');
            $table->index('address_permanent_province_id');
            $table->index('last_contract_id');
            $table->index('last_career_id');
            $table->index('office_id');
            $table->foreign('org_id')->references('id')->on('iq_org')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('photo_id')->references('id')->on('uploads')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('gender_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('marital_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('religion_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('bank_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('emergency_relation_id')->references('id')->on('iq_global_data')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('division_id')->references('id')->on('iq_division')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('iq_company')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('placement_id')->references('id')->on('iq_placement')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('address_city_id')->references('id')->on('iq_city')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('address_province_id')->references('id')->on('iq_city')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('address_permanent_city_id')->references('id')->on('iq_city')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('address_permanent_province_id')->references('id')->on('iq_city')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('office_id')->references('id')->on('iq_office')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_employ');
    }
};
