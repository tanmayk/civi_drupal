<?php

/**
 * @file
 * Provides functionality to be used at install time.
 */

/**
 * Implements hook_install_tasks().
 */
function civi_drupal_kit_install_tasks(&$install_state) {
  // Generate an install task to install required modules.
  $tasks = array();
  $tasks['civi_drupal_kit_post_install'] = array();

  $tasks['civi_drupal_kit_civicrm_install'] = array(
    'display_name' => st('Install CiviCRM'),
    'type' => 'batch',
    'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
  );

  return $tasks;
}

/**
 * Post installation tasks.
 */
function civi_drupal_kit_post_install() {
  // Flush all caches to ensure that any full bootstraps during the installer
  // do not leave stale cached data, and that any content types or other items
  // registered by the install profile are registered correctly.
  drupal_flush_all_caches();
}

/**
 * Task callback to install and configure CiviCRM.
 */
function civi_drupal_kit_civicrm_install(&$install_state) {
  $operations = array();
  // Install CiviCRM.
  $operations[] = array('civi_drupal_kit_civicrm_install_database', array());
  $operations[] = array('civi_drupal_kit_install_civicrm', array());
  $batch = array(
    'title' => t('Install and configure CiviCRM'),
    'init_message' => t('Installing CiviCRM'),
    'operations' => $operations,
  );
  return $batch;
}

/**
 * Setup database with CiviCRM structure and data.
 */
function civi_drupal_kit_civicrm_install_database(&$context) {
  // Get path to CiviCRM.
  $civicrm_path = drupal_get_path('module', 'civicrm');
  // $civicrm_path contains /drupal which is not necesary.
  $civicrm_path = str_replace('/drupal', '', $civicrm_path);
  // Path to CiviCRM sql files.
  $sql_path = DRUPAL_ROOT . '/' . $civicrm_path . '/sql';
  // Get the constant CIVICRM_DSN from civicrm.settings.php.
  require_once DRUPAL_ROOT . '/sites/default/civicrm.settings.php';
  // Import sql files into database.
  _civi_drupal_kit_install_civicrm_source(CIVICRM_DSN, $sql_path . '/civicrm.mysql');
  // Load sample data.
  $sample_data_file = $sql_path . '/civicrm_generated.mysql';
  _civi_drupal_kit_install_civicrm_source(CIVICRM_DSN, $sample_data_file);

  $context['message'] = t('Configuring CiviCRM.');
}

/**
 * Imports sql file into database.
 */
function _civi_drupal_kit_install_civicrm_source($dsn, $fileName, $lineMode = FALSE) {
  // Check if file exists at path.
  if (!file_exists($fileName)) {
    return;
  }
  $civicrm_path = drupal_get_path('module', 'civicrm');
  // $civicrm_path contains /drupal which is not necesary.
  $civicrm_path = rtrim($civicrm_path, '/drupal');
  require_once $civicrm_path . '/packages/DB.php';

  if (!defined('DB_DSN_MODE')) {
    define('DB_DSN_MODE', 'auto');
  }

  $db = DB::connect($dsn);
  if (PEAR::isError($db)) {
    die("Cannot open $dsn: " . $db->getMessage());
  }

  $db->query("SET NAMES utf8");
  $db->query("SET NAMES utf8");

  if (!$lineMode) {
    $string = file_get_contents($fileName);
    // Change \r\n to fix windows issues.
    $string = str_replace("\r\n", "\n", $string);
    // Get rid of comments starting with # and --.
    $string = preg_replace("/^#[^\n]*$/m", "\n", $string);
    $string = preg_replace("/^(--[^-]).*/m", "\n", $string);
    $queries = preg_split('/;\s*$/m', $string);
    foreach ($queries as $query) {
      $query = trim($query);
      if (!empty($query)) {
        $res = &$db->query($query);
        if (PEAR::isError($res)) {
          die("Cannot execute $query: " . $res->getMessage());
        }
      }
    }
  }
  else {
    $fd = fopen($fileName, "r");
    while ($string = fgets($fd)) {
      $string = preg_replace("/^#[^\n]*$/m", "\n", $string);
      $string = preg_replace("/^(--[^-]).*/m", "\n", $string);
      $string = trim($string);
      if (!empty($string)) {
        $res = &$db->query($string);
        if (PEAR::isError($res)) {
          die("Cannot execute $string: " . $res->getMessage());
        }
      }
    }
  }
}

/**
 * Install CiviCRM.
 */
function civi_drupal_kit_install_civicrm(&$context) {
  // Enable CiviCRM module.
  module_enable(array('civicrm'));
  global $base_url;
  if (civicrm_initialize()) {
    $param = array();
    // Setup CiviCRM resource URL.
    $params['userFrameworkResourceURL'] = $base_url . '/' . rtrim(drupal_get_path('module', 'civicrm'), 'drupal');
    $result = civicrm_api('setting', 'create', $params + array('version' => 3));
    CRM_Core_BAO_ConfigSetting::create($params);
    // Reconstruct the config.
    $config = CRM_Core_Config::singleton(TRUE, TRUE);
    CRM_Core_BAO_Navigation::resetNavigation();
  }
  $context['message'] = t('CiviCRM installed.');
  // Create CiviCRM contact for all default usres created while Installtion.
  $all_users = entity_load('user');
  // Remove admin and anonymous user from array as admin contact is
  // created at the time of CiviCRM Installtion.
  $users = array_slice($all_users, 2);
  require_once 'CRM/Core/BAO/UFMatch.php';
  foreach ($users as $value) {
    $params = array(
      'email' => $value->mail,
      'contact_type' => 'Individual',
    );
    // Create CiviCRM contact.
    $result = civicrm_api3('contact', 'create', $params);
    // Add entry in UF match table.
    $uf_match_params = array(
      'uf_id' => $value->uid,
      'uf_name' => $value->mail,
      'contact_id' => $result['id'],
    );
    CRM_Core_BAO_UFMatch::create($uf_match_params);
  }
  // Disable Name and address profile from drupal user account.
  $query = "Delete from civicrm_uf_join where module  IN ( 'User Registration', 'User Account' ) and uf_group_id = 1";
  CRM_Core_DAO::singleValueQuery($query);
  // Enable US and AUS as default country.
  $settings_params = array(
    'domain_id' => 1,
    'countryLimit'  => array(1013, 1228),
    'provinceLimit' => array(1013, 1228),
  );
  civicrm_api3('setting', 'create', $settings_params);
}
