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
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('delete_flag')->default(false);
            $table->timestamps();
        });

        // Create table for storing modules
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->boolean('is_active')->default(true);
            $table->boolean('delete_flag')->default(false);
            $table->timestamps();
        });

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('module_permission_user', function (Blueprint $table) {
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
        Schema::create('module_permission_role', function (Blueprint $table) {
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
            '--class' => 'tenant_role',
            '--force' => true,  // In case you are running migrations in non-interactive environments
        ]);

        Artisan::call('db:seed', [
            '--class' => 'common_permission',
            '--force' => true,  // In case you are running migrations in non-interactive environments
        ]);

        Artisan::call('db:seed', [
            '--class' => 'tenant_module',
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
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
}
