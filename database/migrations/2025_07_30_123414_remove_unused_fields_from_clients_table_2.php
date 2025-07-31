<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedFieldsFromClientsTable2 extends Migration
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
                'reference',
                'postcode_final',
                'client_type',
                'source',
                'assigned_to',
                'tags',
                'priority'
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
            $table->string('reference')->nullable();
            $table->string('postcode_final', 20)->nullable();
            $table->enum('client_type', ['individual', 'corporate', 'partnership'])->nullable();
            $table->string('source', 255)->nullable();
            $table->string('assigned_to', 255)->nullable();
            $table->string('tags', 255)->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->nullable();
        });
    }
}
