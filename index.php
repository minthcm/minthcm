<?php

if(!is_writable('index.php')){
  die('index.php is not writable. Please make sure that you have set correct permissions for whole directory.');
}

if(!file_exists('.htaccess')) {
  $htaccess_body = "<IfModule mod_rewrite.c>
  Options +SymLinksIfOwnerMatch
  RewriteEngine On

  RewriteRule ^api/(.*?)$ install/index.php [L]

  RewriteRule ^assets/(.*?)$ vue/dist/assets/$1 [L]
  RewriteRule ^favicon.ico$ vue/dist/favicon.ico [L]
  RewriteRule ^bg.jpg$ vue/dist/bg.jpg [L]
  RewriteRule ^oauth-handler/(.*)$ legacy/index.php?module=EAPM&action=$1 [L,QSA]
  RewriteRule ^vcal_server.php$ legacy/vcal_server.php [L]
  RewriteRule ^ical_server.php$ legacy/ical_server.php [L]

  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^ vue/dist/index.html [L]
</IfModule>";
  file_put_contents('.htaccess', $htaccess_body);
  
}
header("Refresh:0");
