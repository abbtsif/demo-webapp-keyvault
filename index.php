<?php
/**
 * Variant 2: Secret from Azure Key Vault using Managed Identity
 * More secure approach with audit logs and secret rotation
 */

require_once 'KeyVaultClient.php';

try {
    // Initialize Key Vault client
    $keyVaultClient = new KeyVaultClient();

    // Get the secret from Azure Key Vault
    $secret = $keyVaultClient->getSecret('app-secret');

    if (!$secret) {
        throw new Exception('Secret not found in Key Vault');
    }

} catch (Exception $e) {
    http_response_code(500);
    die('ERROR: ' . htmlspecialchars($e->getMessage()));
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Variant 2 - Azure Key Vault</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { color: #004085; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Variant 2: Azure Key Vault mit Managed Identity</h1>

        <div class="success">
            ✓ Secret erfolgreich aus Azure Key Vault geladen
        </div>

        <div class="info">
            <strong>Methode:</strong> Azure Key Vault mit Managed Identity<br>
            <strong>Secret geladen:</strong> <?php echo 'Ja - ' . strlen($secret) . ' Zeichen'; ?><br>
            <strong>Sicherheitsstufe:</strong> Hoch (Production-Ready)
        </div>

        <h2>Features:</h2>
        <ul>
            <li>✓ Secret sicher in Azure Key Vault gespeichert</li>
            <li>✓ Managed Identity (keine Credentials nötig)</li>
            <li>✓ Audit-Logs aller Zugriffe</li>
            <li>✓ Secret Rotation Support</li>
            <li>✓ Verschlüsselung in Transit und at Rest</li>
            <li>✓ Keine Secrets im Code oder Umgebungsvariablen</li>
        </ul>

        <h2>Architektur:</h2>
        <ul>
            <li>App Service mit System-Assigned Managed Identity</li>
            <li>RBAC-Zugriff auf Key Vault</li>
            <li>Token automatisch bereitgestellt durch Azure</li>
            <li>Keine manuellen Credentials nötig</li>
        </ul>

        <hr>
        <p><small>Diese Webapp läuft auf Azure App Service mit Managed Identity und greift auf Azure Key Vault zu</small></p>
    </div>
</body>
</html>