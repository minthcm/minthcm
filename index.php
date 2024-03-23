<?php

if(!is_writable('index.php')){
  die('index.php is not writable. Please make sure that you have set correct permissions for whole directory.');
}

if(!file_exists('.htaccess')) {
  $htaccess_body = "<IfModule mod_rewrite.c>
  Options +SymLinksIfOwnerMatch
  RewriteEngine On

  RewriteRule ^api/(.*?)$ install/index.php [L]

  RewriteRule ^assets/(.*?)$ assets/$1 [L]
  RewriteRule ^favicon.ico$ favicon.ico [L]
  RewriteRule ^bg.jpg$ bg.jpg [L]
</IfModule>";
  file_put_contents('.htaccess', $htaccess_body);
  
}
unlink('index.php');
copy('vue/dist/index.html', 'index.html');
header("Refresh:0");
