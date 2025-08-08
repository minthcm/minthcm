<?php

/**
 * OAuth Login Page
 */
if (!defined('sugarEntry')) define('sugarEntry', true);

// Initialize Sugar environment
chdir('../../legacy/');
require_once 'include/entryPoint.php';
chdir('../mcp/');

if (!isset($_SESSION)) session_start();

$oauthParams = $_SESSION['oauth_params'] ?? [];
if (empty($oauthParams)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_request', 'error_description' => 'No OAuth parameters found']);
    exit;
}

$error = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (authenticateUser($username, $password)) {
        header('Location: authorize?' . http_build_query($oauthParams));
        exit;
    }

    $error = 'Invalid username or password';
}

/**
 * Authenticate user with Sugar credentials
 * 
 * @param string $username Username
 * @param string $password Password
 * @return bool True if authentication successful
 */
function authenticateUser(string $username, string $password): bool
{
    global $current_user;

    chdir('../legacy/');
    $authController = new \AuthenticationController();
    $authResult = $authController->login($username, $password);
    chdir('../mcp/');

    if ($authResult && !empty($current_user) && !empty($current_user->id)) {
        $_SESSION['authenticated_user_id'] = $current_user->id;
        return true;
    }

    return false;
}

// Prepare data for the template
$clientName = htmlspecialchars($oauthParams['client_name'] ?? 'MCP Client');
$scope = htmlspecialchars($oauthParams['scope'] ?? 'mcp:read mcp:write');
$scopeList = explode(' ', $scope);
$scopeLabels = [
    'mcp:read' => 'Read your data',
    'mcp:write' => 'Modify your data',
    'openid' => 'Access your identity',
    'profile' => 'Access your profile information'
];

// Render login form
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MintHCM MCP Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script type="importmap">{"imports":{"@material/web/":"https://esm.run/@material/web/"}}</script>
    <script type="module">
        import '@material/web/all.js';
        import {
            styles as typescaleStyles
        } from '@material/web/typography/md-typescale-styles.js';
        document.adoptedStyleSheets.push(typescaleStyles.styleSheet);
    </script>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: url('../assets/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo img {
            height: 33px;
            margin-bottom: 0.5rem;
        }

        .client-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .scope-list {
            margin-top: 1rem;
            text-align: left;
        }

        .scope-item {
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            color: #555;
        }

        .error {
            background: #fee;
            color: #c33;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #fcc;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        md-outlined-text-field,
        md-filled-button {
            width: 100%;
        }

        md-outlined-text-field {
            --md-outlined-text-field-outline-color: #e0e0e0;
            --md-outlined-text-field-label-text-color: #9e9e9e;
            --md-outlined-text-field-focus-outline-color: rgb(0, 101, 78);
            --md-outlined-text-field-focus-label-text-color: rgb(0, 101, 78);
            --md-outlined-text-field-caret-color: rgb(0, 101, 78);
        }

        md-filled-button {
            --md-filled-button-container-color: rgb(0, 101, 78);
            --md-filled-button-label-text-color: white;
        }

        md-filled-button:hover {
            --md-filled-button-container-color: rgb(0, 101, 78);
            filter: brightness(0.95);
        }

        md-filled-button:active {
            --md-filled-button-container-color: rgb(0, 101, 78);
            filter: brightness(0.90);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="../assets/mint_logo.png" alt="MintHCM Logo">
        </div>
        <div class="client-info">
            <h3>Application Authorization</h3>
            <p><strong><?= $clientName ?></strong> is requesting access to your MintHCM account.</p>
            <div class="scope-list">
                <p><strong>Permissions requested:</strong></p>
                <?php foreach ($scopeList as $s): ?>
                    <div class="scope-item">✓ <?= $scopeLabels[$s] ?? $s ?></div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="on">
            <md-outlined-text-field
                label="Username"
                name="username"
                id="username"
                required
                autocomplete="username"
                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </md-outlined-text-field>
            <md-outlined-text-field
                label="Password"
                name="password"
                id="password"
                type="password"
                required
                autocomplete="current-password">
            </md-outlined-text-field>
            <md-filled-button type="submit">Complete</md-filled-button>
        </form>
    </div>
</body>

</html>