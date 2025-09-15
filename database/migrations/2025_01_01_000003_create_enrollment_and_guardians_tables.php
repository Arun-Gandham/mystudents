<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // STUDENT ENROLLMENTS (year-wise snapshot)
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->uuid('academic_id');
            $table->uuid('grade_id');
            $table->uuid('section_id')->nullable();
            $table->string('roll_no')->nullable();
            $table->date('joined_on')->nullable();
            $table->date('left_on')->nullable();
            $table->text('promotion_note')->nullable();
            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('student_id', 'fk_enrl_student')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_enrl_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('grade_id', 'fk_enrl_grade')->references('id')->on('grades')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_enrl_section')->references('id')->on('sections')->nullOnDelete();

            $table->unique(['student_id','academic_id'], 'enrol_student_acad_uq');
            $table->index(['academic_id','section_id','student_id'], 'enrol_acad_section_student_idx');
        });

        // STUDENT GUARDIANS
        Schema::create('student_guardians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('full_name');
            $table->string('relation', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('phone_e164')->nullable();
            $table->string('alt_phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->softDeletes();
            $table->timestampsTz();
            
            $table->foreign('student_id', 'fk_guard_student')->references('id')->on('students')->cascadeOnDelete();
            $table->index(['student_id','is_primary'], 'guard_student_primary_idx');
        });
        Schema::create('student_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('state');
            $table->string('pincode', 10);
            $table->enum('address_type', ['permanent','current'])->default('current');
            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });

        Schema::create('student_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->enum('doc_type', [
                'aadhaar','birth_certificate','transfer_certificate','caste_certificate','passport_photo','other'
            ]);
            $table->string('file_path');
            $table->date('issued_on')->nullable();
            $table->date('verified_on')->nullable();
            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('student_addresses');
        Schema::dropIfExists('student_documents');
        Schema::dropIfExists('student_guardians');
        Schema::dropIfExists('student_enrollments');
    }
};
