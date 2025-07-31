<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            // Reference and Lead Source
            $table->string('reference', 100)->nullable();
            $table->enum('lead_source', ['website', 'referral', 'social_media', 'advertising', 'cold_call', 'email', 'phone'])->nullable();
            
            // Personal Information
            $table->enum('title', ['Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof', 'Sir', 'Lady'])->nullable();
            $table->string('forename', 100);
            $table->string('surname', 100);
            $table->string('alias', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('country_of_birth', 100)->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'civil_partnership', 'separated'])->nullable();
            $table->date('anniversary_date')->nullable();
            
            // Contact Information
            $table->string('email_address', 191);
            $table->string('mobile_number', 20);
            $table->string('home_phone', 20)->nullable();
            
            // Company Information
            $table->string('company_name', 255)->nullable();
            $table->string('company_number', 50)->nullable();
            
            // Address Information
            $table->string('postcode', 20)->nullable();
            $table->string('house_number', 20)->nullable();
            $table->string('address_line_1', 255)->nullable();
            $table->string('address_line_2', 255)->nullable();
            $table->string('town_city', 100)->nullable();
            $table->string('county', 100)->nullable();
            $table->string('postcode_final', 20)->nullable();
            $table->string('country', 100)->nullable();
            
            // Additional Information
            $table->text('other')->nullable();
            
            // Notes (stored as JSON for multiple notes)
            $table->json('notes')->nullable();
            
            // Soft deletes
            $table->softDeletes();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['forename', 'surname']);
            $table->index('email_address');
            $table->index('reference');
            $table->index('lead_source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
