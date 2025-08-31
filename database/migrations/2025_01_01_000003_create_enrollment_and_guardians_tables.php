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
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

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
            $table->enum('relation', ['father','mother','guardian','other']);
            $table->string('email')->nullable();
            $table->string('phone_e164')->nullable();
            $table->string('alt_phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('student_id', 'fk_guard_student')->references('id')->on('students')->cascadeOnDelete();
            $table->index(['student_id','is_primary'], 'guard_student_primary_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardians');
        Schema::dropIfExists('student_enrollments');
    }
};
