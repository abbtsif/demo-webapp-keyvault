<?php
use Azure\Identity\DefaultAzureCredential;
use Azure\Security\KeyVault\Secrets\SecretClient;

$credential = new DefaultAzureCredential();
$client = new SecretClient(
    getenv("KEY_VAULT_URL"),
    $credential
);

$secret = $client->getSecret("mySecret");
echo "Secret: " . $secret->getValue();
?>
