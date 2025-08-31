<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // PERMISSIONS CATALOG
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->text('description')->nullable();
            $table->string('group_name')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;
        });

        // ROLES
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('school_id')->nullable(); // null = system template
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('school_id', 'fk_roles_school')->references('id')->on('schools')->nullOnDelete();
            $table->unique(['school_id','name'], 'role_school_name_uq');
        });

        // ROLE PERMISSIONS
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('role_id');
            $table->uuid('permission_id');
            $table->enum('scope', ['any','own','grade','section'])->default('any');
            $table->boolean('allow')->default(true);
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('role_id', 'fk_rp_role')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('permission_id', 'fk_rp_perm')->references('id')->on('permissions')->cascadeOnDelete();
            $table->unique(['role_id','permission_id'], 'role_perm_uq');
        });

        Schema::create('default_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('permission_id');
            $table->boolean('is_active')->default(true);
            $table->unique(['name','permission_id'], 'rolename_perm_uq');

            $table->foreign('permission_id', 'fk_rnp_perm')->references('id')->on('permissions')->cascadeOnDelete();
        });

        // USER ROLES
        Schema::create('user_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('role_id');
            $table->uuid('school_id');
            $table->boolean('is_primary')->default(false);
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->timestampTz('created_at')->useCurrent();
$table->timestampTz('updated_at')->useCurrent()->useCurrentOnUpdate();;

            $table->foreign('user_id', 'fk_ur_user')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('role_id', 'fk_ur_role')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('school_id', 'fk_ur_school')->references('id')->on('schools')->cascadeOnDelete();

            $table->unique(['user_id','role_id','school_id'], 'user_role_school_uq');
            $table->index(['school_id','user_id'], 'user_roles_school_user_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
    }
};
