<?php

namespace MintHCM\MintCLI\Installer;

class ServerConfigManager
{

    public function configureServer()
    {
        
        $server_software = $_SERVER["SERVER_SOFTWARE"];
        if (strpos((string) $server_software, 'Microsoft-IIS') !== false) {
            installLog("calling handleWebConfig()");
            $this->handleWebConfig();
        } else {
            installLog("calling handleHtaccess()");
            $this->handleHtaccess();
        }
    }

    function handleHtaccess()
    {
        global $mod_strings, $sugar_config;

        $ignoreCase = (substr_count(strtolower($_SERVER['SERVER_SOFTWARE']), 'apache/2') > 0) ? '(?i)' : '';
        $htaccess_file = ".htaccess";
        $contents = '';
        $basePath = parse_url((string) $sugar_config['site_url'], PHP_URL_PATH);
        if (empty($basePath))
            $basePath = '/';
        $restrict_str = <<<EOQ
     
     # BEGIN SUGARCRM RESTRICTIONS
     # MINTHCM #110405 START
     Header always edit Set-Cookie ^(.*)$ $1;HttpOnly;Secure;SameSite=None;
     # MINTHCM #110405 END
     EOQ;
        if (ini_get('suhosin.perdir') !== false && strpos(ini_get('suhosin.perdir'), 'e') !== false) {
            $restrict_str .= "php_value suhosin.executor.include.whitelist upload\n";
        }
        $restrict_str .= <<<EOQ
     RedirectMatch 403 {$ignoreCase}.*\.log$
     RedirectMatch 403 {$ignoreCase}/+not_imported_.*\.txt
     RedirectMatch 403 {$ignoreCase}/+(soap|cache|xtemplate|data|examples|include|log4php|metadata|modules|vendor)/+.*\.(php|tpl|phar)
     RedirectMatch 403 {$ignoreCase}/+emailmandelivery\.php
     RedirectMatch 403 {$ignoreCase}/+upload
     RedirectMatch 403 {$ignoreCase}/+custom/+blowfish
     RedirectMatch 403 {$ignoreCase}/+cache/+diagnostic
     RedirectMatch 403 {$ignoreCase}/+files\.md5$
     # END SUGARCRM RESTRICTIONS
     EOQ;

        $cache_headers = <<<EOQ
     
     <IfModule mod_rewrite.c>
         Options +SymLinksIfOwnerMatch
         RewriteEngine On
         RewriteBase {$basePath}
         RewriteRule ^cache/jsLanguage/(.._..).js$ index.php?entryPoint=jslang&modulename=app_strings&lang=$1 [L,QSA]
         RewriteRule ^cache/jsLanguage/(\w*)/(.._..).js$ index.php?entryPoint=jslang&modulename=$1&lang=$2 [L,QSA]
     
         # --------- DEPRECATED --------
         RewriteRule ^api/(.*?)$ lib/API/public/index.php/$1 [L]
         RewriteRule ^api/(.*)$ - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
         # -----------------------------
     
         RewriteRule ^Api/access_token$ Api/index.php/access_token [L]
         RewriteRule ^Api/V8/(.*?)$ Api/index.php/V8/$1 [L]
         RewriteRule ^Api/(.*)$ - [env=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
         # MintHCM #110041 START
         RewriteRule ^oauth-handler/(.*)$ index.php?module=EAPM&action=$1 [L,QSA]
         # MintHCM #110041 END
     </IfModule>
     <FilesMatch "\.(jpg|png|gif|js|css|ico)$">
             <IfModule mod_headers.c>
                     Header set ETag ""
                     Header set Cache-Control "max-age=2592000"
                     Header set Expires "01 Jan 2112 00:00:00 GMT"
             </IfModule>
     </FilesMatch>
     <IfModule mod_headers.c>
         Header set X-Robots-Tag "noindex, nofollow"
     </IfModule>
     <IfModule mod_expires.c>
             ExpiresByType text/css "access plus 1 month"
             ExpiresByType text/javascript "access plus 1 month"
             ExpiresByType application/x-javascript "access plus 1 month"
             ExpiresByType image/gif "access plus 1 month"
             ExpiresByType image/jpg "access plus 1 month"
             ExpiresByType image/png "access plus 1 month"
     </IfModule>
     <IfModule mod_rewrite.c>
             RewriteEngine On
             RewriteCond %{REQUEST_FILENAME} !-d
             RewriteCond %{REQUEST_URI} (.+)/$
             RewriteRule ^ %1 [R=301,L]
     </IfModule>
     EOQ;
        if (file_exists($htaccess_file)) {
            $fp = fopen($htaccess_file, 'r');
            $skip = false;
            while ($line = fgets($fp)) {

                if (preg_match("/\s*#\s*BEGIN\s*SUGARCRM\s*RESTRICTIONS/i", $line)) {
                    if (!$skip)
                        $contents .= $line;
                    $skip = true;
                    if (preg_match("/\s*#\s*END\s*SUGARCRM\s*RESTRICTIONS/i", $line))
                        $skip = false;
                }
                if (!$skip) {
                    $contents .= $line;
                }
                if (preg_match("/\s*#\s*END\s*SUGARCRM\s*RESTRICTIONS/i", $line)) {
                    $skip = false;
                }
            }
        }
        $status = file_put_contents($htaccess_file, $contents . $restrict_str . $cache_headers);
        if (!$status) {
            echo "<p>{$mod_strings['ERR_PERFORM_HTACCESS_1']}<span class=stop>{$htaccess_file}</span> {$mod_strings['ERR_PERFORM_HTACCESS_2']}</p>\n";
            echo "<p>{$mod_strings['ERR_PERFORM_HTACCESS_3']}</p>\n";
            echo $restrict_str;
        }

        return $status;
    }


    function handleWebConfig()
    {
        if (!isset($_SERVER['IIS_UrlRewriteModule'])) {
            return;
        }

        global $setup_site_log_dir;
        global $setup_site_log_file;
        global $sugar_config;

        // Bug 36968 - Fallback to using $sugar_config values when we are not calling this from the installer
        if (empty($setup_site_log_file)) {
            $setup_site_log_file = $sugar_config['log_file'];
            if (empty($sugar_config['log_file'])) {
                $setup_site_log_file = 'minthcm.log';
            }
        }
        if (empty($setup_site_log_dir)) {
            $setup_site_log_dir = $sugar_config['log_dir'];
            if (empty($sugar_config['log_dir'])) {
                $setup_site_log_dir = '.';
            }
        }

        $prefix = $setup_site_log_dir . empty($setup_site_log_dir) ? '' : '/';


        $config_array = array(
            array('1' => $prefix . str_replace('.', '\\.', (string) $setup_site_log_file) . '\\.*', '2' => 'log_file_restricted.html'),
            array('1' => $prefix . 'install.log', '2' => 'log_file_restricted.html'),
            array('1' => $prefix . 'upgradeWizard.log', '2' => 'log_file_restricted.html'),
            array('1' => $prefix . 'emailman.log', '2' => 'log_file_restricted.html'),
            array('1' => 'not_imported_.*.txt', '2' => 'log_file_restricted.html'),
            array('1' => 'XTemplate/(.*)/(.*).php', '2' => 'index.php'),
            array('1' => 'data/(.*).php', '2' => 'index.php'),
            array('1' => 'examples/(.*).php', '2' => 'index.php'),
            array('1' => 'include/(.*).php', '2' => 'index.php'),
            array('1' => 'include/(.*)/(.*).php', '2' => 'index.php'),
            array('1' => 'log4php/(.*).php', '2' => 'index.php'),
            array('1' => 'log4php/(.*)/(.*)', '2' => 'index.php'),
            array('1' => 'metadata/(.*)/(.*).php', '2' => 'index.php'),
            array('1' => 'modules/(.*)/(.*).php', '2' => 'index.php'),
            array('1' => 'soap/(.*).php', '2' => 'index.php'),
            array('1' => 'emailmandelivery.php', '2' => 'index.php'),
            array('1' => 'cron.php', '2' => 'index.php'),
            array('1' => $sugar_config['upload_dir'] . '.*', '2' => 'index.php'),
        );


        $xmldoc = new \XMLWriter();
        $xmldoc->openURI('web.config');
        $xmldoc->setIndent(true);
        $xmldoc->setIndentString(' ');
        $xmldoc->startDocument('1.0', 'UTF-8');
        $xmldoc->startElement('configuration');
        $xmldoc->startElement('system.webServer');
        $xmldoc->startElement('rewrite');
        $xmldoc->startElement('rules');
        $config_arrayCount = count($config_array);
        for ($i = 0; $i < $config_arrayCount; $i++) {
            $xmldoc->startElement('rule');
            $xmldoc->writeAttribute('name', "redirect$i");
            $xmldoc->writeAttribute('stopProcessing', 'true');
            $xmldoc->startElement('match');
            $xmldoc->writeAttribute('url', $config_array[$i]['1']);
            $xmldoc->endElement();
            $xmldoc->startElement('action');
            $xmldoc->writeAttribute('type', 'Redirect');
            $xmldoc->writeAttribute('url', $config_array[$i]['2']);
            $xmldoc->writeAttribute('redirectType', 'Found');
            $xmldoc->endElement();
            $xmldoc->endElement();
        }
        $xmldoc->endElement();
        $xmldoc->endElement();
        $xmldoc->startElement('caching');
        $xmldoc->startElement('profiles');
        $xmldoc->startElement('remove');
        $xmldoc->writeAttribute('extension', ".php");
        $xmldoc->endElement();
        $xmldoc->endElement();
        $xmldoc->endElement();
        $xmldoc->startElement('staticContent');
        $xmldoc->startElement("clientCache");
        $xmldoc->writeAttribute('cacheControlMode', 'UseMaxAge');
        $xmldoc->writeAttribute('cacheControlMaxAge', '30.00:00:00');
        $xmldoc->endElement();
        $xmldoc->endElement();
        $xmldoc->endElement();
        $xmldoc->endElement();
        $xmldoc->endDocument();
        $xmldoc->flush();
    }
}
