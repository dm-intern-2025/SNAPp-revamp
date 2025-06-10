<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

class GcsService
{
    public ?StorageClient $storageClient = null;
    public ?string $bucketName = null;

    public function __construct()
    {
        $config = config('services.google_cloud');
        
        if (empty($config['project_id']) || empty($config['key_file']) || empty($config['bucket'])) {
            Log::error('GCS Service Error: Configuration is missing in config/services.php or .env file.');
            return;
        }

        if (!file_exists($config['key_file'])) {
            Log::error('GCS Service Error: Key file not found at path: ' . $config['key_file']);
            return;
        }

        try {
            $this->storageClient = new StorageClient([
                'projectId' => $config['project_id'],
                'keyFilePath' => $config['key_file'],
            ]);
            $this->bucketName = $config['bucket'];
        } catch (\Exception $e) {
            Log::error('GCS Service Error: Failed to instantiate Storage client: ' . $e->getMessage());
        }
    }

    public function generateSignedUrl(string $objectPath): ?string
    {
        if (!$this->storageClient) {
            Log::error("GCS Service Error: Cannot generate URL. Storage client not initialized.");
            return null;
        }

        try {
            $bucket = $this->storageClient->bucket($this->bucketName);
            $object = $bucket->object($objectPath);

            if (!$object->exists()) {
                Log::info("GCS Service Info: Object not found: gs://{$this->bucketName}/{$objectPath}");
                return null;
            }

            return $object->signedUrl(new \DateTime('15 min'), ['version' => 'v4']);
        } catch (\Exception $e) {
            Log::error("GCS Service Error: Could not generate signed URL for gs://{$this->bucketName}/{$objectPath}", ['exception' => $e]);
            return null;
        }
    }
}