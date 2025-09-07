<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // STUDENT ATTENDANCE SHEETS
        Schema::create('student_attendance_sheets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('section_id');
            $table->date('attendance_date');
            $table->enum('session', ['morning','afternoon']);
            $table->uuid('taken_by')->nullable();
            $table->timestampTz('taken_at')->nullable();
            $table->text('note')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_sas_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_sas_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_sas_section')->references('id')->on('sections')->cascadeOnDelete();
            $table->foreign('taken_by', 'fk_sas_takenby')->references('id')->on('users')->nullOnDelete();

            $table->unique(['section_id','academic_id','attendance_date','session'], 'stu_att_sheet_uq');
        });

        // STUDENT ATTENDANCE ENTRIES
        Schema::create('student_attendance_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sheet_id');
            $table->uuid('student_id');
            $table->enum('status', ['present','absent','late','half_day','excused']);
            $table->text('remarks')->nullable();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('sheet_id', 'fk_sae_sheet')->references('id')->on('student_attendance_sheets')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_sae_student')->references('id')->on('students')->cascadeOnDelete();
            $table->unique(['sheet_id','student_id'], 'stu_att_entry_uq');
        });

        // STAFF ATTENDANCE
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->date('attendance_date');
            $table->enum('session', ['morning','afternoon']);
            $table->uuid('user_id');
            $table->enum('status', ['present','absent','late','half_day','excused']);
            $table->text('remarks')->nullable();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_staffatt_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('user_id', 'fk_staffatt_user')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['user_id','attendance_date','session'], 'staff_att_uq');
            $table->index(['school_id','attendance_date'], 'staff_att_school_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendance');
        Schema::dropIfExists('student_attendance_entries');
        Schema::dropIfExists('student_attendance_sheets');
    }
};
