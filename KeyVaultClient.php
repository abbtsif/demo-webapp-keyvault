<?php
/**
 * Azure Key Vault Client using Managed Identity
 * Requires: composer require microsoft/azure-identity microsoft/azure-keyvault
 */

use Azure\Identity\DefaultAzureCredential;
use Azure\Security\KeyVault\KeyVaultClient as AzureKeyVaultClient;

class KeyVaultClient
{
    private $client;
    private $vaultUrl;
    private $cache = [];
    private $cacheExpiry = [];
    private $cacheTTL = 3600; // 1 hour cache TTL

    public function __construct()
    {
        // Get Key Vault URL from environment variable
        $this->vaultUrl = getenv('AZURE_KEYVAULT_URL');

        if (!$this->vaultUrl) {
            throw new Exception('AZURE_KEYVAULT_URL environment variable is not set');
        }

        // Initialize with Managed Identity
        // DefaultAzureCredential automatically uses the Managed Identity token
        try {
            $credential = new DefaultAzureCredential();
            $this->client = new AzureKeyVaultClient($credential);
        } catch (Exception $e) {
            throw new Exception('Failed to initialize Key Vault client: ' . $e->getMessage());
        }
    }

    /**
     * Get a secret from Key Vault with caching
     *
     * @param string $secretName The name of the secret
     * @param bool $bypassCache Skip cache and fetch fresh value
     * @return string|null The secret value
     * @throws Exception
     */
    public function getSecret(string $secretName, bool $bypassCache = false): ?string
    {
        // Check cache first
        if (!$bypassCache && isset($this->cache[$secretName])) {
            if (time() < $this->cacheExpiry[$secretName]) {
                return $this->cache[$secretName];
            } else {
                unset($this->cache[$secretName]);
                unset($this->cacheExpiry[$secretName]);
            }
        }

        try {
            // Fetch from Key Vault
            $secret = $this->client->getSecret($this->vaultUrl, $secretName);

            if (!$secret) {
                throw new Exception("Secret '{$secretName}' not found in Key Vault");
            }

            // Cache the secret
            $this->cache[$secretName] = $secret->getValue();
            $this->cacheExpiry[$secretName] = time() + $this->cacheTTL;

            return $secret->getValue();
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve secret '{$secretName}': " . $e->getMessage());
        }
    }

    /**
     * Clear all cached secrets
     */
    public function clearCache(): void
    {
        $this->cache = [];
        $this->cacheExpiry = [];
    }

    /**
     * Check if Key Vault is accessible (health check)
     */
    public function healthCheck(): bool
    {
        try {
            // Try to get the vault properties as a connectivity test
            // This will fail gracefully if not accessible
            $this->client->getSecret($this->vaultUrl, 'health-check', ['version' => null]);
            return true;
        } catch (Exception $e) {
            // Secret doesn't need to exist, we're just testing connectivity
            return false;
        }
    }
}
