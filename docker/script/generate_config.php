<?php
$outputFile = '/var/www/MintHCM/configMint4';

// Retrieve environment variables
$dbHost = getenv('DB_HOST') ?: 'default_db_host';
$dbName = getenv('DB_NAME') ?: 'default_db_name';
$dbPort = getenv('DB_PORT') ?: 'default_db_port';
$dbUser = getenv('DB_USER') ?: 'default_db_user';
$dbPass = getenv('DB_PASS') ?: 'default_db_pass';
$mintUrl = getenv('MINT_URL') ?: 'default_mint_url';
$mintUser = getenv('MINT_USER') ?: 'default_mint_user';
$mintPass = getenv('MINT_PASS') ?: 'default_mint_pass';
$databaseCollation = getenv('DB_COLLATION') ?: 'utf8mb4_general_ci';
$elasticsearchHost = getenv('ELASTICSEARCH_HOST') ?: 'minthcm-es';
$elasticsearchPort = getenv('ELASTICSEARCH_PORT') ?: '9200';
$elasticsearchUsername = getenv('ELASTICSEARCH_USERNAME') ?: 'elastic';
$elasticsearchPassword = getenv('ELASTICSEARCH_PASSWORD') ?: 'changeme';
$installDemoData = getenv('INSTALL_DEMO_DATA') ?: 'no';
$ssl = getenv('SSL') ?: 'no';
$rebuildFrontend = getenv('REBUILD_FRONTEND') ?: 'no';
$applicationRootDirectory = '/var/www/MintHCM';

// Open the file in write mode or create it if it doesn't exist
$fileHandle = fopen($outputFile, 'w');

// Check if the file was opened successfully
if ($fileHandle === false) {
    die('Unable to open or create file for writing.');
}

// Write values to the file
fwrite($fileHandle, "$mintUser\n");
fwrite($fileHandle, "$mintPass\n");
fwrite($fileHandle, "$dbHost\n");
fwrite($fileHandle, "$dbPort\n");
fwrite($fileHandle, "$dbUser\n");
fwrite($fileHandle, "$dbPass\n");
fwrite($fileHandle, "$dbName\n");

// Write additional values to the file
fwrite($fileHandle, "$databaseCollation\n");
fwrite($fileHandle, "$elasticsearchHost\n");
fwrite($fileHandle, "$elasticsearchPort\n");
fwrite($fileHandle, "$elasticsearchUsername\n");
fwrite($fileHandle, "$elasticsearchPassword\n");
fwrite($fileHandle, "$installDemoData\n");
fwrite($fileHandle, "$ssl\n");
fwrite($fileHandle, "$mintUrl\n");
fwrite($fileHandle, "$applicationRootDirectory\n");
fwrite($fileHandle, "$rebuildFrontend\n");

// Close the file
fclose($fileHandle);

echo "Values have been written to $outputFile successfully.\n";
?>
