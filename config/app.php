<?php
/**
 * The URL path this project is served from, relative to the web root.
 * Change this ONE line if you move or rename the project folder —
 * every link in the app is built from this constant instead of being
 * hardcoded, so nothing else needs to change.
 *
 * NOTE: This must match the Apache Alias defined in httpd.conf, not the
 * actual folder name on disk. The folder on disk is "lsb-internal_security_group",
 * but httpd.conf maps the URL path "/smart-portal" to that folder:
 *   Alias /smart-portal "C:/Program Tools/GITHUB PROG/04_PROJECTS_V2/lsb-internal_security_group"
 * So BASE_URL must be "/smart-portal", not the folder's real name.
 */
define('BASE_URL', '/smart-portal');


// always run apache\bin\httpd.exe