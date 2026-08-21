<?php
/**
 * _bootstrap.php
 * ---------------------------------------------------------------------
 * Shared loader for all view files under /assets/views/.
 * Locates the project root by walking up from this file's own location
 * until it finds /config/connection_paths.php, then loads it.
 *
 * Any view file just needs one line:
 *   require_once __DIR__ . '/../_bootstrap.php';
 * ---------------------------------------------------------------------
 */

$__root = __DIR__;
while (!file_exists($__root . '/config/connection_paths.php')) {
    $__root = dirname($__root);
}
require_once $__root . '/config/connection_paths.php';