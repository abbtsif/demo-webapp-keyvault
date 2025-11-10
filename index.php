<?php

$cs_key = getenv('APP_PASS');
$cs_name = getenv('APP_USER');

if (substr($cs_key, 0, 20) === '@Microsoft.KeyVault(' or substr($cs_name, 0, 20) === '@Microsoft.KeyVault(')
{
    // Key Vault reference failed to resolve.
        header('HTTP/1.1 500 Internal Server Error');
        echo '<h1>HTTP/1.1 500: Error resolving Key Vault references!</h1>';
        exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Key-Vault</title>
</head>
<body>
<h3>Key-Vault</h3>

<!-- Umgebungsvariablen anzeigen -->
<div style="background-color: #f0f0f0; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc;">
    <h4>Umgebungsvariablen:</h4>
    <p><strong>SECURE_PASS:</strong> <?php echo htmlspecialchars($cs_key); ?></p>
    <p><strong>SECURE_USER:</strong> <?php echo htmlspecialchars($cs_name); ?></p>
</div>
<br/>
</body>
</html>
