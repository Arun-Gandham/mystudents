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

            $table->foreign('school_id', 'fk_feehead_school')
                  ->references('id')->on('schools')->cascadeOnDelete();

            $table->unique(['school_id','name'], 'feehead_school_name_uq');
            $table->unique(['school_id','code'], 'feehead_school_code_uq');
        });

        // SECTION FEES (defaults per section + academic)
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

            $table->foreign('school_id', 'fk_secfee_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_secfee_acad')
                  ->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('section_id', 'fk_secfee_section')
                  ->references('id')->on('sections')->cascadeOnDelete();
            $table->foreign('fee_head_id', 'fk_secfee_feehead')
                  ->references('id')->on('fee_heads')->cascadeOnDelete();

            $table->unique(['section_id','academic_id','fee_head_id'], 'section_fee_uq');
        });

        // STUDENT FEE ITEMS (student-level fee allocation)
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

            $table->foreign('school_id', 'fk_stufee_school')
                  ->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id', 'fk_stufee_acad')
                  ->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('student_id', 'fk_stufee_student')
                  ->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('fee_head_id', 'fk_stufee_feehead')
                  ->references('id')->on('fee_heads')->cascadeOnDelete();

            $table->index(['student_id','academic_id'], 'stu_fee_student_acad_idx');
            $table->index(['school_id','academic_id'], 'stu_fee_school_acad_idx');
        });

        // STUDENT FEE RECEIPTS (master receipt)
        Schema::create('student_fee_receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id');
            $table->uuid('academic_id');
            $table->uuid('student_id');
            
            $table->decimal('total_amount', 12, 2); // amount paid in this transaction
            $table->date('paid_on');
            $table->string('method')->nullable();       // Cash, Card, UPI, etc.
            $table->string('reference_no')->nullable(); // Transaction / receipt number
            $table->string('payer_name')->nullable();   // Who paid
            $table->string('payer_phone')->nullable();  // Their mobile
            $table->string('payer_relation')->nullable(); // Relation to student (Father, Mother, Guardian)
            $table->text('note')->nullable();

            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('school_id')->references('id')->on('schools')->cascadeOnDelete();
            $table->foreign('academic_id')->references('id')->on('academics')->cascadeOnDelete();
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();

            $table->index(['student_id','academic_id'], 'receipt_student_acad_idx');
        });

        // STUDENT FEE PAYMENTS (line-items linked to receipt + fee item)
        Schema::create('student_fee_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('receipt_id');        // NEW: link to student_fee_receipts
            $table->uuid('student_fee_item_id');
            $table->decimal('paid_amount', 12, 2);

            $table->softDeletes();
            $table->timestampsTz();

            $table->foreign('receipt_id')->references('id')->on('student_fee_receipts')->cascadeOnDelete();
            $table->foreign('student_fee_item_id', 'fk_sfp_item')
                  ->references('id')->on('student_fee_items')->cascadeOnDelete();

            $table->index(['student_fee_item_id'], 'sfp_item_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee_payments');
        Schema::dropIfExists('student_fee_receipts');
        Schema::dropIfExists('student_fee_items');
        Schema::dropIfExists('section_fees');
        Schema::dropIfExists('fee_heads');
    }
};
