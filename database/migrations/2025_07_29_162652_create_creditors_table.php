<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('creditors', function (Blueprint $table) {
            $table->id();
            
            // Basic Creditor Information
            $table->string('name');
            $table->string('email')->nullable();
            $table->enum('creditor_type', ['bank', 'credit_card', 'utility', 'loan', 'mortgage', 'debt_collection', 'other'])->nullable();
            
            // Category Relationship
            $table->foreignId('category_id')->nullable()->constrained('creditor_categories')->onDelete('set null');
            
            // Address Information
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('town_city')->nullable();
            $table->string('county')->nullable();
            $table->string('country')->default('United Kingdom');
            $table->string('postcode')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditors');
    }
};
