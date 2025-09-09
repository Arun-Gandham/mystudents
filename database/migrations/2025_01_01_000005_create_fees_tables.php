<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // FEE HEADS
        Schema::create('fee_heads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('school_id', 'fk_feehead_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->unique(['school_id','name'], 'feehead_school_name_uq');
            $table->unique(['school_id','code'], 'feehead_school_code_uq');
        });

        // SECTION FEES (defaults)
        Schema::create('section_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('section_id');
            $table->uuid('fee_head_id');
            $table->decimal('base_amount', 12, 2);
            $table->boolean('is_optional')->default(false);
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('school_id', 'fk_secfee_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_secfee_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_secfee_section')->references('id')->on('sections')->cascadeOnDelete();
            $table->foreign('fee_head_id', 'fk_secfee_feehead')->references('id')->on('fee_heads')->cascadeOnDelete();

            $table->unique(['section_id','academic_id','fee_head_id'], 'section_fee_uq');
        });

        // STUDENT FEE ITEMS
        Schema::create('student_fee_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('student_id');
            $table->uuid('fee_head_id');

            $table->decimal('base_amount', 12, 2);
            $table->enum('discount_kind', ['none','percent','flat'])->default('none');
            $table->decimal('discount_value', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2);

            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('school_id', 'fk_stufee_school')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_stufee_acad')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_stufee_student')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('fee_head_id', 'fk_stufee_feehead')->references('id')->on('fee_heads')->cascadeOnDelete();

            $table->index(['student_id','academic_id'], 'stu_fee_student_acad_idx');
            $table->index(['school_id','academic_id'], 'stu_fee_school_acad_idx');
        });

        // STUDENT FEE PAYMENTS
        Schema::create('student_fee_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_fee_item_id');
            $table->decimal('paid_amount', 12, 2);
            $table->date('paid_on');
            $table->string('method')->nullable();
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestampsTz();
            
            $table->foreign('student_fee_item_id', 'fk_sfp_item')
                  ->references('id')->on('student_fee_items')->cascadeOnDelete();
            $table->index(['student_fee_item_id'], 'sfp_item_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee_payments');
        Schema::dropIfExists('student_fee_items');
        Schema::dropIfExists('section_fees');
        Schema::dropIfExists('fee_heads');
    }
};
