<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 =>
  array (
    'name' => 'Cron:SponsorshipEmails.Sponsorshipemails',
    'entity' => 'Job',
    'params' =>
    array (
      'version' => 3,
      'name' => 'Sponsorship Emails API',
      'description' => 'Send out scheduled mails for all contacts for upcoming events. Add message_temp as parameter for sending mails from message from message template, like message_temp=1 as 1 is the id of message template.',
      'run_frequency' => 'Monthly',
      'api_entity' => 'SponsorshipEmails',
      'api_action' => 'Sponsorshipemails',
      'parameters' => '',
    ),
  ),
);
