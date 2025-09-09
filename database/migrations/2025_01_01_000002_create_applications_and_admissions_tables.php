<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // STUDENT JOIN APPLICATIONS (pre-admission)
        Schema::create('student_join_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('preferred_grade_id')->nullable();
            $table->uuid('preferred_section_id')->nullable();
            $table->string('application_no')->nullable();

            $table->enum('status', [
                'lead','submitted','reviewing','offered','accepted','rejected','no_response','withdrawn'
            ])->default('lead');

            // child/applicant
            $table->string('child_full_name');
            $table->date('child_dob')->nullable();
            $table->string('child_gender')->nullable();
            $table->string('previous_school')->nullable();

            // guardian quick contact
            $table->string('guardian_full_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->text('address')->nullable();

            // process trail
            $table->date('visited_on')->nullable();
            $table->date('submitted_on')->nullable();
            $table->date('decided_on')->nullable();
            $table->text('remarks')->nullable();

            // link to student on acceptance
            $table->uuid('student_id')->nullable();
            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('school_id', 'fk_sja_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_sja_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('preferred_grade_id', 'fk_sja_grade')->references('id')->on('grades')->nullOnDelete();
            $table->foreign('preferred_section_id', 'fk_sja_section')->references('id')->on('sections')->nullOnDelete();
            $table->foreign('student_id', 'fk_sja_student')->references('id')->on('students')->nullOnDelete();

            $table->unique(['school_id','application_no'], 'sja_school_appno_uq');
            $table->index(['school_id','academic_id','status'], 'sja_school_acad_status_idx');
        });

        // STUDENT ADMISSIONS (per-academic record)
        Schema::create('student_admissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('student_id');

            $table->string('application_no')->nullable();
            $table->enum('status', ['pending','offered','admitted','rejected','waitlisted','cancelled'])->default('pending');

            $table->date('applied_on')->nullable();
            $table->date('offered_on')->nullable();
            $table->date('admitted_on')->nullable();

            $table->uuid('offered_grade_id')->nullable();
            $table->uuid('offered_section_id')->nullable();

            $table->string('previous_school')->nullable();
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestampsTz();
            
            $table->foreign('school_id', 'fk_stuadm_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_stuadm_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_stuadm_student')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('offered_grade_id', 'fk_stuadm_grade')->references('id')->on('grades')->nullOnDelete();
            $table->foreign('offered_section_id', 'fk_stuadm_section')->references('id')->on('sections')->nullOnDelete();

            $table->unique(['school_id','application_no'], 'stuadm_school_appno_uq');
            $table->unique(['student_id','academic_id'], 'stuadm_student_acad_uq');
        });

        // Link students.source_application_id FK now that table exists
        Schema::table('students', function (Blueprint $table) {
            $table->foreign('source_application_id', 'fk_students_sourceapp')
                  ->references('id')->on('student_join_applications')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign('fk_students_sourceapp');
        });
        Schema::dropIfExists('student_admissions');
        Schema::dropIfExists('student_join_applications');
    }
};
