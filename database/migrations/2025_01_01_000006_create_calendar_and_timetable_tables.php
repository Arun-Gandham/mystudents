<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // SCHOOL HOLIDAYS
        Schema::create('school_holidays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->date('date');
            $table->string('name');
            $table->boolean('is_full_day')->default(true);
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->boolean('repeats_annually')->default(false);
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_holiday_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_holiday_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->unique(['school_id','academic_id','date'], 'holiday_school_acad_date_uq');
        });

        // SECTION DAY TIMETABLES
        Schema::create('section_day_timetables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('section_id');
            $table->enum('day', ['mon','tue','wed','thu','fri','sat','sun']);
            $table->string('title')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_sdt_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_sdt_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_sdt_section')->references('id')->on('sections')->cascadeOnDelete();

            $table->unique(['section_id','academic_id','day'], 'sect_day_tt_uq');
        });

        // SECTION DAY PERIODS
        Schema::create('section_day_periods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('day_timetable_id');
            $table->integer('period_no');
            $table->time('starts_at');
            $table->time('ends_at');
            $table->uuid('subject_id');
            $table->uuid('teacher_id')->nullable();
            $table->string('room')->nullable();
            $table->text('note')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('day_timetable_id', 'fk_sdp_daytt')->references('id')->on('section_day_timetables')->cascadeOnDelete();
            $table->foreign('subject_id', 'fk_sdp_subject')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('teacher_id', 'fk_sdp_teacher')->references('id')->on('users')->nullOnDelete();

            $table->unique(['day_timetable_id','period_no'], 'sect_day_period_uq');
            $table->index(['day_timetable_id','starts_at'], 'sect_day_period_start_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_day_periods');
        Schema::dropIfExists('section_day_timetables');
        Schema::dropIfExists('school_holidays');
    }
};
