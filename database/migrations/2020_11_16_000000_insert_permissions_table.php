<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $names = array_unique(array_merge(config('awemapl-permission.permissions'),
            config('temp_permission.permissions', [])));

        foreach ($names as $name){
            DB::table($tableNames['permissions'])->insert(
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
        $names = array_unique(array_merge(config('awemapl-permission.permissions'),
            config('temp_permission.permissions', [])));
        foreach ($names as $name){
            DB::table($tableNames['permissions'])
                ->where('name', $name)
                ->where('guard_name', config('auth.defaults.guard'))
                ->delete();
        }
    }
}
