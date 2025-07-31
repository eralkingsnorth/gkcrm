<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedFieldsFromClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'alias',
                'anniversary_date',
                'company_name',
                'company_number'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('alias', 100)->nullable()->after('surname');
            $table->date('anniversary_date')->nullable()->after('marital_status');
            $table->string('company_name', 255)->nullable()->after('home_phone');
            $table->string('company_number', 50)->nullable()->after('company_name');
        });
    }
}
