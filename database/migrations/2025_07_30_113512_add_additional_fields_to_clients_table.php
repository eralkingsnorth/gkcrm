<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('client_status', ['active', 'inactive', 'pending', 'suspended'])->nullable()->after('notes');
            $table->enum('client_type', ['individual', 'corporate', 'partnership'])->nullable()->after('client_status');
            $table->string('source', 255)->nullable()->after('client_type');
            $table->string('assigned_to', 255)->nullable()->after('source');
            $table->string('tags', 255)->nullable()->after('assigned_to');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('tags');
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
            $table->dropColumn(['client_status', 'client_type', 'source', 'assigned_to', 'tags', 'priority']);
        });
    }
}
