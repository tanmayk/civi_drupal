<?php

/**
 * @file
 * Drupal site-specific configuration file.
 */

global $conf;

/**
 * Access control for update.php script.
 *
 * If you are updating your Drupal installation using the update.php script but
 * are not logged in using either an account with the "Administer software
 * updates" permission or the site maintenance account (the account that was
 * created during installation), you will need to modify the access check
 * statement below. Change the FALSE to a TRUE to disable the access check.
 * After finishing the upgrade, be sure to open this file again and change the
 * TRUE back to a FALSE!
 */
$update_free_access = FALSE;

/**
 * Salt for one-time login links and cancel links, form tokens, etc.
 *
 * This variable will be set to a random value by the installer. All one-time
 * login links will be invalidated if the value is changed. Note that if your
 * site is deployed on a cluster of web servers, you must ensure that this
 * variable has the same value on each server. If this variable is empty, a hash
 * of the serialized database credentials will be used as a fallback salt.
 *
 * For enhanced security, you may set this variable to a value using the
 * contents of a file outside your docroot that is never saved together
 * with any backups of your Drupal files and database.
 *
 * Example:
 *   $drupal_hash_salt = file_get_contents('/home/example/salt.txt');
 *
 */
$drupal_hash_salt = 'iIMcB1q9y1BWHZmnCGmCRXT5Bm2HGR6prZeIKP8bn4E';

/**
 * PHP settings:
 */
@ini_set('max_execution_time', 240);
@ini_set('session.gc_probability', 1);
@ini_set('session.gc_divisor', 100);
@ini_set('session.gc_maxlifetime', 200000);
@ini_set('session.cache_expire', 200000);
@ini_set('session.cookie_lifetime', 2000000);

/**
 * Fast 404 pages:
 *
 * Drupal can generate fully themed 404 pages. However, some of these responses
 * are for images or other resource files that are not displayed to the user.
 * This can waste bandwidth, and also generate server load.
 *
 * The options below return a simple, fast 404 page for URLs matching a
 * specific pattern:
 * - 404_fast_paths_exclude: A regular expression to match paths to exclude,
 *   such as images generated by image styles, or dynamically-resized images.
 *   The default pattern provided below also excludes the private file system.
 *   If you need to add more paths, you can add '|path' to the expression.
 * - 404_fast_paths: A regular expression to match paths that should return a
 *   simple 404 page, rather than the fully themed 404 page. If you don't have
 *   any aliases ending in htm or html you can add '|s?html?' to the expression.
 * - 404_fast_html: The html to return for simple 404 pages.
 *
 * Add leading hash signs if you would like to disable this functionality.
 */
$conf['404_fast_paths_exclude'] = '/\/(?:styles)|(?:system\/files)\//';
$conf['404_fast_paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$conf['404_fast_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

/**
 * The default list of directories that will be ignored by Drupal's file API.
 *
 * By default ignore node_modules and bower_components folders to avoid issues
 * with common frontend tools and recursive scanning of directories looking for
 * extensions.
 *
 * @see file_scan_directory()
 */
$conf['file_scan_ignore_directories'] = array(
  'node_modules',
  'bower_components',
);

// Hard set the files directory.
$conf['file_public_path'] = 'sites/default/files';
$conf['file_private_path'] = 'sites/default/files/private';

/**
 * All Pantheon Environemnts.
 */
if (defined('PANTHEON_ENVIRONMENT') && !defined('MAINTENANCE_MODE')) {
  $settings = json_decode($_SERVER['PRESSFLOW_SETTINGS'], TRUE);
}

/**
 * Add local settings from settings.local.php.
 */
if (!defined('PANTHEON_ENVIRONMENT')) {

  // Use /tmp for temporary files.
  $conf['file_temporary_path'] = '/tmp';

  # Load in additional site configuration settings.
  $local_settings = dirname(__FILE__) . '/settings.local.php';
  if (file_exists($local_settings)) {
    include $local_settings;
  }
}

/**
 * Override contrib module's settings.
 */

/**
 * Adminimal menu: Shortcut rendering method.
 */
$conf['adminimal_admin_menu_render'] = 'hidden';

/**
 * Add SSL redirect.
 */
if (defined('PANTHEON_ENVIRONMENT') && file_exists($redirect_settings)) {
  $redirect_settings = dirname(__FILE__) . '/settings.redirect.php';
  include $redirect_settings;
}