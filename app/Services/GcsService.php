<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log; // Log is still here for the catch block, but dd is primary

class GcsService
{
    public ?StorageClient $storageClient = null;
    public ?string $bucketName = null;

    public function __construct()
    {
        $config = config('services.google_cloud');
        
        if (empty($config['project_id']) || empty($config['key_file']) || empty($config['bucket'])) {
            dd('GCS Service Error: Configuration is missing in config/services.php or .env file.');
        }

        if (!file_exists($config['key_file'])) {
            dd('GCS Service Error: Key file not found at path: ' . $config['key_file']);
        }

        try {
            $this->storageClient = new StorageClient([
                'projectId' => $config['project_id'],
                'keyFilePath' => $config['key_file'],
            ]);
            $this->bucketName = $config['bucket'];
        } catch (\Exception $e) {
            dd('GCS Service Error: Failed to instantiate Storage client.', $e);
        }
    }

    public function generateSignedUrl(string $objectPath): ?string
    {
        if (!$this->storageClient) {
            dd("GCS Service Error: Cannot generate URL. Storage client was not initialized. Check your configuration.");
        }

        try {
            $bucket = $this->storageClient->bucket($this->bucketName);
            $object = $bucket->object($objectPath);

            if (!$object->exists()) {
                dd("GCS Service Info: Object not found. The connection worked, but this file does not exist in the bucket:", "gs://{$this->bucketName}/{$objectPath}");
            }

            return $object->signedUrl(new \DateTime('15 min'), ['version' => 'v4']);
        } catch (\Exception $e) {
            dd("GCS Service Error: Could not generate signed URL. This is likely a PERMISSION ERROR.", $e);
        }
    }
}