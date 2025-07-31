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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            
            // Basic Case Information
            $table->string('case_number')->unique();
            $table->string('case_title');
            $table->enum('case_type', ['civil', 'criminal', 'family', 'commercial']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->text('description')->nullable();
            
            // Client Relationship
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            
            // Status and Category Relationships
            $table->foreignId('status_id')->nullable()->constrained('case_statuses')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('case_categories')->onDelete('set null');
            
            // Case Reference
            $table->string('case_reference')->nullable();
            
            // Creditor Information
            $table->string('creditor_name');
            $table->string('account_number')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->text('other_data')->nullable();
            
            // Document and Email Status
            $table->boolean('documents_needed')->default(false);
            $table->enum('email_status', ['pending', 'sent', 'delivered', 'failed']);
            
            // Banking Information
            $table->string('sort_code')->nullable();
            $table->string('account_reference')->nullable();
            $table->string('case_category')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
