<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLeadSourceFieldInClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            // Drop the enum constraint and change to string
            $table->string('lead_source', 100)->nullable()->change();
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
            // Revert back to enum
            $table->enum('lead_source', ['website', 'referral', 'social_media', 'advertising', 'cold_call', 'email', 'phone'])->nullable()->change();
        });
    }
}
