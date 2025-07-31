<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // id_document, contract_document, financial_document, other_documents
            $table->string('original_name'); // Original file name
            $table->string('file_path'); // Storage path
            $table->string('file_name'); // Generated file name
            $table->string('mime_type'); // File MIME type
            $table->bigInteger('file_size'); // File size in bytes
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['client_id', 'document_type']);
            $table->index('file_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_documents');
    }
}
