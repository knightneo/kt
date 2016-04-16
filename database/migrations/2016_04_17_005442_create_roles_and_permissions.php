<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('is_deleted')->default(0);
            $table->integer('created_at')->unsigned();
            $table->integer('updated_at')->unsigned();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('is_deleted')->default(0);
            $table->integer('created_at')->unsigned();
            $table->integer('updated_at')->unsigned();
        });

        Schema::create('permission_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->tinyInteger('is_deleted')->default(0);
            $table->integer('created_at')->unsigned();
            $table->integer('updated_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
        Schema::drop('permissions');
        Schema::drop('permission_roles');
    }
}
