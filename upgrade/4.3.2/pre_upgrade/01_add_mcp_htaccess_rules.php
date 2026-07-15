<?php
/**
 * Pre-upgrade script: Add MCP and OAuth well-known rewrite rules to .htaccess.
 * Scripts in pre_upgrade/ are executed before git checkout.
 * Must define a run(OutputInterface $output): bool function.
 * Return true to continue, false to abort upgrade.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    $htaccess_path = __DIR__ . '/../../../.htaccess';

    if (!file_exists($htaccess_path)) {
        $output->writeln('  <error>.htaccess file not found at: ' . $htaccess_path . '</error>');
        return false;
    }

    $content = file_get_contents($htaccess_path);

    if ($content === false) {
        $output->writeln('  <error>Failed to read .htaccess file.</error>');
        return false;
    }

    $mcp_rules = "    RewriteRule ^mcp/?$ mcp/index.php [L,QSA]\n"
        . "    RewriteRule ^\\.well-known/oauth-protected-resource/mcp/?$ mcp/oauth.php [L,QSA,E=OAUTH_ENDPOINT:discovery]\n"
        . "    RewriteRule ^\\.well-known/oauth-authorization-server/mcp/?$ mcp/oauth.php [L,QSA,E=OAUTH_ENDPOINT:authorization]\n"
        . "    RewriteRule ^\\.well-known/openid-configuration/mcp/?$ mcp/oauth.php [L,QSA,E=OAUTH_ENDPOINT:authorization]\n"
        . "    RewriteRule ^\\.well-known/ - [R=404,L]\n";

    // Check if the rules already exist (idempotent)
    if (strpos($content, 'mcp/index.php [L,QSA]') !== false) {
        $output->writeln('  <info>MCP rewrite rules already present in .htaccess. Skipping.</info>');
        return true;
    }

    // Insert the new rules after the "RewriteRule ^api/(.*?)$ api/index.php [L]" line
    $anchor = "RewriteRule ^api/(.*?)$ api/index.php [L]\n";
    $pos = strpos($content, $anchor);

    if ($pos === false) {
        $output->writeln('  <error>Could not find insertion anchor ("RewriteRule ^api/...") in .htaccess.</error>');
        return false;
    }

    $insert_at = $pos + strlen($anchor);
    $new_content = substr($content, 0, $insert_at) . $mcp_rules . substr($content, $insert_at);

    if (file_put_contents($htaccess_path, $new_content) === false) {
        $output->writeln('  <error>Failed to write updated .htaccess file.</error>');
        return false;
    }

    $output->writeln('  <info>MCP and OAuth well-known rewrite rules successfully added to .htaccess.</info>');
    return true;
};
