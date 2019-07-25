<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 =>
  array (
    'name' => 'Cron:HebrewEventsNotify.Eventnotify',
    'entity' => 'Job',
    'params' =>
    array (
      'version' => 3,
      'name' => 'Hebrew Events Notify API',
      'description' => 'Sending scheduled mails for upcoming birthdays & $anniversaries for all contacts.',
      'run_frequency' => 'Monthly',
      'api_entity' => 'HebrewEventsNotify',
      'api_action' => 'Eventnotify',
      'parameters' => '',
    ),
  ),
);
