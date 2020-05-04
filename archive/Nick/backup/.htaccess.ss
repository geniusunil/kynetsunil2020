
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase ss_slash_site_path/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . ss_slash_site_path/index.php [L]
</IfModule>

# END WordPress
