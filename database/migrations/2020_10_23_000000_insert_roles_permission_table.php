<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRolesPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $names = array_merge([config('awemapl-permission.super_admin_role')], config('awemapl-permission.insert_roles'));

        foreach ($names as $name){
            DB::table($tableNames['roles'])->insert(
                [
                    'name' => $name,
                    'guard_name' => config('auth.defaults.guard')
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');
        $names = array_merge([config('awemapl-permission.super_admin_role')], config('awemapl-permission.insert_roles'));

        foreach ($names as $name){
            DB::table($tableNames['roles'])
                ->where('name', $name)
                ->where('guard_name', config('auth.defaults.guard'))
                ->delete();
        }
    }
}
