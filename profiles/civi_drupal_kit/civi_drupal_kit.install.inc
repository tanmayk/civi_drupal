<?php

/**
 * @file
 * Provides functionality to be used at install time.
 */

/**
 * Implements hook_install_tasks().
 */
function schoolholidays_install_tasks(&$install_state) {
  // Generate an install task to install required modules.
  $tasks = array();
  $tasks['civi_drupal_kit_post_install'] = array();
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
