<?php

/**
 * @file
 * Enables modules and site configuration for site installation.
 */

/**
 * Implements hook_form_FORM_ID_alter() for install_configure_form().
 *
 * Allows the profile to alter the site configuration form.
 */
function civi_drupal_kit_form_install_configure_form_alter(&$form, $form_state) {
  // Hide some messages from various modules that are just too chatty!
  drupal_get_messages('status');
  drupal_get_messages('warning');

  // Pre-populate the site name with the server name.
  $form['site_information']['site_name']['#default_value'] = 'Civi Drupal Kit';

  // Use "admin" as the default username.
  $form['admin_account']['account']['name']['#default_value'] = 'admin';

  // Set the default admin password.
  $form['admin_account']['account']['pass']['#value'] = 'admin';

  // Hide Update Notifications.
  $form['update_notifications']['#access'] = FALSE;

  // Add informations about the default username and password.
  $form['admin_account']['account']['civi_drupal_kit_name'] = array(
    '#type' => 'item',
    '#title' => st('Username'),
    '#markup' => 'admin',
  );
  $form['admin_account']['account']['civi_drupal_kit_password'] = array(
    '#type' => 'item',
    '#title' => st('Password'),
    '#markup' => 'admin',
  );
  $form['admin_account']['override_account_informations'] = array(
    '#type' => 'checkbox',
    '#title' => t('Change my username and password.'),
  );
  $form['admin_account']['setup_account'] = array(
    '#type' => 'container',
    '#parents' => array('admin_account'),
    '#states' => array(
      'invisible' => array(
        'input[name="override_account_informations"]' => array('checked' => FALSE),
      ),
    ),
  );

  // Make a "copy" of the original name and pass form fields.
  $form['admin_account']['setup_account']['account']['name'] = $form['admin_account']['account']['name'];
  $form['admin_account']['setup_account']['account']['pass'] = $form['admin_account']['account']['pass'];
  $form['admin_account']['setup_account']['account']['pass']['#value'] = array('pass1' => 'admin', 'pass2' => 'admin');

  // Use "admin" as the default username.
  $form['admin_account']['account']['name']['#default_value'] = 'admin';
  $form['admin_account']['account']['name']['#access'] = FALSE;

  // Set the default admin password.
  $form['admin_account']['account']['pass']['#value'] = 'admin';

  // Make the password "hidden".
  $form['admin_account']['account']['pass']['#type'] = 'hidden';
  $form['admin_account']['account']['mail']['#access'] = FALSE;

  // Add a custom validation that needs to be trigger before the original one,
  // where we can copy the site's mail as the admin account's mail.
  array_unshift($form['#validate'], 'civi_drupal_kit_install_validate');
}

/**
 * Validate callback.
 *
 * Populate the admin account mail, user and password with custom values.
 */
function civi_drupal_kit_install_validate(&$form, &$form_state) {
  $form_state['values']['account']['mail'] = $form_state['values']['site_mail'];
  // Use our custom values only the corresponding checkbox is checked.
  if ($form_state['values']['override_account_informations'] == TRUE) {
    if ($form_state['input']['pass']['pass1'] == $form_state['input']['pass']['pass2']) {
      $form_state['values']['account']['name'] = $form_state['values']['name'];
      $form_state['values']['account']['pass'] = $form_state['input']['pass']['pass1'];
    }
    else {
      form_set_error('pass', t('The specified passwords do not match.'));
    }
  }
}

// Include only when in install mode. MAINTENANCE_MODE is defined in
// install.php and in drush_core_site_install().
if (defined('MAINTENANCE_MODE') && MAINTENANCE_MODE == 'install') {
  include_once 'civi_drupal_kit.install.inc';
}
