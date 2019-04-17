<?php

/**
 * Content Management System (CMS) Datasource:
 *
 * Update this setting with your CMS (Drupal or Joomla) database username, server and DB name. Comment it out if using CiviCRM standalone.
 * Datasource (DSN) format:
 *      define('CIVICRM_UF_DSN', 'mysql://cms_db_username:cms_db_password@db_server/cms_database?new_link=true');
 */
define('CIVICRM_UF_DSN', "mysql://???:???@localhost/????new_link=true");


/**
 * CiviCRM Database Settings
 *
 * Database URL (CIVICRM_DSN) for CiviCRM Data:
 * Database URL format:
 *      define('CIVICRM_DSN', 'mysql://crm_db_username:crm_db_password@db_server/crm_database?new_link=true');
 *
 * Drupal and CiviCRM can share the same database, or can be installed into separate databases.
 *
 * EXAMPLE: Drupal and CiviCRM running in the same database...
 *      DB Name = drupal, DB User = drupal
 *      define('CIVICRM_DSN', 'mysql://drupal:YOUR_PASSWORD@localhost/drupal?new_link=true' );
 *
 * EXAMPLE: Drupal and CiviCRM running in separate databases...
 *      Drupal  DB Name = drupal, DB User = drupal
 *      CiviCRM DB Name = civicrm, CiviCRM DB User = civicrm
 *      define('CIVICRM_DSN', 'mysql://civicrm:YOUR_PASSWORD@localhost/civicrm?new_link=true' );
 *
 */
define('CIVICRM_DSN', "mysql://???:???@localhost/????new_link=true");

/**
 * File System Paths:
 *
 * $civicrm_root is the file system path on your server where the civicrm
 * code is installed. Use an ABSOLUTE path (not a RELATIVE path) for this setting.
 *
 * CIVICRM_TEMPLATE_COMPILEDIR is the file system path where compiled templates are stored.
 * These sub-directories and files are temporary caches and will be recreated automatically
 * if deleted.
 *
 * IMPORTANT: The COMPILEDIR directory must exist,
 * and your web server must have read/write access to these directories.
 */

global $civicrm_root;

$civicrm_root = '/YOUR/PATH/TO/WEBROOT/PATH/TO/civicrm/';

define('CIVICRM_TEMPLATE_COMPILEDIR', '/YOUR/PATH/TO/WEBROOT/sites/default/files/civicrm/templates_c/');

/**
 * Site URLs:
 *
 * This section defines absolute and relative URLs to access the host CMS (Drupal or Joomla) resources.
 *
 * IMPORTANT: Trailing slashes should be used on all URL settings.
 */

define('CIVICRM_UF_BASEURL', 'http://' . $_SERVER['HTTP_HOST'] .'/' );
