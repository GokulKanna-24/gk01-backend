<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan; // Import Artisan to run seeders

class LaratrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('delete_flag')->default(false);
            $table->bigInteger('created_by')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('delete_flag')->default(false);
            $table->bigInteger('created_by')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        // Create table for storing modules
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('route_name');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('delete_flag')->default(false);
            $table->bigInteger('created_by')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        // Create table for storing tenant modules
        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('route_name');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('delete_flag')->default(false);
            $table->bigInteger('created_by')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        // Create table for associating modules to permissions and teams (Many To Many Polymorphic)
        Schema::create('module_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('module_id')->references('id')->on('modules')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'module_id']);
        });

        // Create table for associating modules to permissions and teams (Many To Many Polymorphic)
        Schema::create('tenant_module_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_module_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('tenant_module_id')->references('id')->on('tenant_modules')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'tenant_module_id']);
        });

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary('user_id');
        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('user_module_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            // Unique constraint instead of composite primary key
            $table->unique(['permission_id', 'module_id', 'user_id'], 'module_permission_user_unique');
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('role_module_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            // Unique constraint instead of composite primary key
            $table->unique(['permission_id', 'module_id', 'role_id'], 'module_permission_role_unique');
        });

        Artisan::call('db:seed', [
            '--class' => 'landlord_role',
            '--force' => true,  // In case you are running migrations in non-interactive environments
        ]);

        Artisan::call('db:seed', [
            '--class' => 'common_permission',
            '--force' => true,  // In case you are running migrations in non-interactive environments
        ]);

        Artisan::call('db:seed', [
            '--class' => 'landlord_module',
            '--force' => true,  // In case you are running migrations in non-interactive environments
        ]);

        Artisan::call('db:seed', [
            '--class' => 'landlord_tenant_module',
            '--force' => true,  // In case you are running migrations in non-interactive environments
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_permission_user');
        Schema::dropIfExists('module_permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('tenant_module');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
}
