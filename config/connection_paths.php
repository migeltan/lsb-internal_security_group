<?php
/**
 * connection_paths.php
 * ---------------------------------------------------------------------
 * Single source of truth for every folder in this project.
 *
 * If you move, rename, or restructure ANY folder (views, assets, admin,
 * uploads, etc.), you only need to update the matching line below.
 * Every other file should reference these constants instead of typing
 * out paths by hand.
 *
 * Two kinds of constants are defined for each folder:
 *   - *_PATH  = a filesystem path, for require/include statements
 *   - *_URL   = a browser-facing URL, for href/src/action attributes
 *
 * This file must live in /config/connection_paths.php. It works out ROOT_PATH
 * automatically from its own location, so it does not break if the
 * whole project folder is renamed or moved elsewhere on disk.
 * ---------------------------------------------------------------------
 */

require_once __DIR__ . '/app.php'; // defines BASE_URL (the URL alias, e.g. /smart-portal)

// ---- Filesystem root -----------------------------------------------
// config/ is one level under the project root, so go up one from here.
define('ROOT_PATH', dirname(__DIR__));

// ---- Filesystem paths (use these in require_once / include_once) ---
define('CONFIG_PATH',   ROOT_PATH . '/config');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('ASSETS_PATH',   ROOT_PATH . '/assets');
define('CSS_PATH',      ASSETS_PATH . '/css');
define('JS_PATH',       ASSETS_PATH . '/js');
define('VIEWS_PATH',    ASSETS_PATH . '/views');
define('ADMIN_PATH',    ROOT_PATH . '/admin');
define('SQL_PATH',      ROOT_PATH . '/sql');
define('TEMPLATES_PATH',ROOT_PATH . '/templates');
define('UPLOADS_PATH',  ROOT_PATH . '/uploads');

// ---- URL paths (use these in href="", src="", action="") -----------
define('ASSETS_URL',    BASE_URL . '/assets');
define('CSS_URL',       ASSETS_URL . '/css');
define('JS_URL',        ASSETS_URL . '/js');
define('VIEWS_URL',     ASSETS_URL . '/views');
define('FEAT1_URL',     ASSETS_URL . '/views/feat1_access-pass');
define('FEAT2_URL',     ASSETS_URL . '/views/feat2_vehicle-sticker');
define('FEAT3_URL',     ASSETS_URL . '/views/feat3_status-module');
define('ADMIN_URL',     BASE_URL . '/admin');
define('UPLOADS_URL',   BASE_URL . '/uploads');
define('TEMPLATES_URL', BASE_URL . '/templates');
define('IMG_URL', BASE_URL . '/public/img');