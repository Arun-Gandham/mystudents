<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // SCHOOLS
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('domain');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;
        });

        // USERS
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_users_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
        });

        // ACADEMICS
        Schema::create('academics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_acad_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
            $table->unique(['school_id','name'], 'acad_school_name_uq');
        });

        // GRADES
        Schema::create('grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->string('name');
            $table->integer('ordinal')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_grades_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
            $table->unique(['school_id','name'], 'grade_school_name_uq');
            $table->unique(['school_id','ordinal'], 'grade_school_ord_uq');
        });

        // SECTIONS
        Schema::create('sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('grade_id');
            $table->string('name');
            $table->uuid('teacher_id')->nullable(); // users.id
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('grade_id', 'fk_sections_grade')
                  ->references('id')->on('grades')->cascadeOnDelete();
            $table->foreign('teacher_id', 'fk_sections_teacher')
                  ->references('id')->on('users')->nullOnDelete();
            $table->unique(['grade_id','name'], 'section_grade_name_uq');
        });

        // SUBJECTS
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_subjects_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
            $table->unique(['school_id','name'], 'subject_school_name_uq');
            $table->unique(['school_id','code'], 'subject_school_code_uq');
        });

        // STUDENTS
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->string('full_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('admission_no')->nullable();
            $table->string('gender')->nullable();
            $table->enum('status', ['accepted','rejected','no_response','withdrawn'])->default('accepted');
            $table->uuid('source_application_id')->nullable(); // FK later
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_students_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
            $table->unique(['school_id','admission_no'], 'student_school_adm_uq');
        });

        // SCHOOL DETAILS (1-1)
        Schema::create('school_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id')->unique();
            $table->string('phone_e164')->nullable();
            $table->string('alt_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country_code')->nullable();
            $table->string('principal_name')->nullable();
            $table->integer('established_year')->nullable();
            $table->string('affiliation_no')->nullable();
            $table->text('note')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_school_details_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_details');
        Schema::dropIfExists('students');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('academics');
        Schema::dropIfExists('users');
        Schema::dropIfExists('schools');
    }
};
