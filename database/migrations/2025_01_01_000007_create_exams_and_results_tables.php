<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // EXAMS
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('section_id');
            $table->string('name');
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->boolean('is_published')->default(false);
            $table->text('note')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_exams_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_exams_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_exams_section')->references('id')->on('sections')->cascadeOnDelete();

            $table->unique(['section_id','academic_id','name'], 'exam_sect_acad_name_uq');
        });

        // EXAM SUBJECTS
        Schema::create('exam_subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->uuid('subject_id');
            $table->decimal('max_marks', 8, 2);
            $table->decimal('pass_marks', 8, 2)->nullable();
            $table->integer('order_no')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('exam_id', 'fk_exsub_exam')->references('id')->on('exams')->cascadeOnDelete();
            $table->foreign('subject_id', 'fk_exsub_subject')->references('id')->on('subjects')->cascadeOnDelete();
            $table->unique(['exam_id','subject_id'], 'exam_subject_uq');
        });

        // EXAM RESULTS
        Schema::create('exam_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->uuid('student_id');
            $table->uuid('subject_id');
            $table->decimal('marks_obtained', 5, 2);
            $table->string('grade')->nullable();
            $table->text('remarks')->nullable();
            $table->uuid('entered_by')->nullable();
            $table->timestampTz('entered_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('exam_id', 'fk_exres_exam')->references('id')->on('exams')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_exres_student')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('subject_id', 'fk_exres_subject')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('entered_by', 'fk_exres_enteredby')->references('id')->on('users')->nullOnDelete();

            $table->unique(['exam_id','student_id','subject_id'], 'exam_result_uq');
            $table->index(['exam_id','student_id'], 'exam_result_exam_student_idx');
            $table->index(['student_id'], 'exam_result_student_idx');
        });

        Schema::create('exam_grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->string('grade', 5);         // Example: A+, A, B, C, F
            $table->integer('min_mark');        // Inclusive lower bound
            $table->integer('max_mark');        // Inclusive upper bound
            $table->string('remark')->nullable(); // Optional default remark
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->cascadeOnDelete();
            $table->unique(['exam_id','grade']); // avoid duplicate grade names
        });

        Schema::create('exam_overall_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->uuid('student_id');
            $table->decimal('total_obtained', 8, 2);
            $table->decimal('total_max', 8, 2);
            $table->string('overall_grade')->nullable();
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->unique(['exam_id','student_id']);
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exam_subjects');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_overall_results');
        Schema::dropIfExists('exam_grades');
    }
};
