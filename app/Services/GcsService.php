<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

class GcsService
{
    private ?StorageClient $storageClient = null;
    private array $config;
    private bool $isInitialized = false;

    /**
     * The constructor now ONLY stores the config. 
     * It does NOT try to connect to Google, which fixes the startup crash.
     */
    public function __construct()
    {
        $this->config = config('services.google_cloud');
    }

    /**
     * This private method initializes the GCS client ONCE, the first time it's needed.
     * This is the "lazy loading" part.
     */
    private function initializeClient(): bool
    {
        // If we have already tried to connect, don't try again.
        if ($this->isInitialized) {
            return !is_null($this->storageClient);
        }

        // Mark that we are now attempting to initialize.
        $this->isInitialized = true;

        if (empty($this->config['project_id']) || empty($this->config['key_file']) || empty($this->config['bucket'])) {
            Log::critical('GCS FATAL ERROR: Configuration keys are missing in config/services.php or .env file.');
            return false;
        }

        try {
            // The actual connection to Google happens HERE.
            // If the key file path is invalid, this will throw a clear exception.
            $this->storageClient = new StorageClient([
                'projectId' => $this->config['project_id'],
                'keyFilePath' => $this->config['key_file'],
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::critical('GCS FATAL ERROR: Failed to instantiate Storage client. Check key file path and permissions.', [
                'key_file_path' => $this->config['key_file'],
                'exception_message' => $e->getMessage()
            ]);
            $this->storageClient = null;
            return false;
        }
    }

    /**
     * Generates a temporary signed URL for a given GCS object path.
     */
    public function generateSignedUrl(string $objectPath): ?string
    {
        // First, ensure the client is ready by calling our new helper method.
        if (!$this->initializeClient()) {
            Log::error("GCS Error: Cannot generate URL because client failed to initialize.");
            return null;
        }

        try {
            $bucket = $this->storageClient->bucket($this->config['bucket']);
            $object = $bucket->object($objectPath);

            if (!$object->exists()) {
                Log::info("GCS Info: Object not found: gs://{$this->config['bucket']}/{$objectPath}");
                return null;
            }

            return $object->signedUrl(new \DateTime('15 min'), ['version' => 'v4']);
        } catch (\Exception $e) {
            Log::error("GCS Error: Could not generate signed URL. This is likely a PERMISSION ERROR (missing Service Account Token Creator role).", [
                'object_path' => "gs://{$this->config['bucket']}/{$objectPath}",
                'exception' => $e->getMessage()
            ]);
            return null;
        }
    }
}
