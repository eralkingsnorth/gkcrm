<?php

namespace Database\Factories;

use App\Models\ClientDocument;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $documentTypes = ['id_document', 'contract_document', 'financial_document', 'other_documents'];
        $fileExtensions = ['pdf', 'doc', 'docx', 'jpg', 'png', 'xlsx'];
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        
        $extension = $this->faker->randomElement($fileExtensions);
        $originalName = $this->faker->words(3, true) . '.' . $extension;
        $fileName = time() . '_' . $this->faker->regexify('[A-Za-z0-9]{10}') . '.' . $extension;
        $yearMonth = now()->format('Y-m');
        $clientId = Client::factory()->create()->id;
        
        return [
            'client_id' => $clientId,
            'document_type' => $this->faker->randomElement($documentTypes),
            'original_name' => $originalName,
            'file_path' => "client-documents/{$yearMonth}/{$clientId}/{$fileName}",
            'file_name' => $fileName,
            'mime_type' => $mimeTypes[$extension] ?? 'application/octet-stream',
            'file_size' => $this->faker->numberBetween(1024, 5 * 1024 * 1024), // 1KB to 5MB
            'description' => $this->faker->optional(0.7)->sentence(),
        ];
    }
}
