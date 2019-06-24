<?php
use CRM_Sponsorshipsmails_ExtensionUtil as E;

/**
 * SponsorshipEmails.Sponsorshipemails API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_sponsorship_emails_Sponsorshipemails($params) {
  if (array_key_exists('message_temp', $params)) {
    // Get message template data from passwd parameters.
    $messageTemplate = civicrm_api3('MessageTemplate', 'get', [
      'sequential' => 1,
      'id' => $params['message_temp'],
    ]);

    if (!empty($messageTemplate['values'])) {
      $subject = $messageTemplate['values'][0]['msg_subject'];
      $text_message = $messageTemplate['values'][0]['msg_text'];
      $html_message = $messageTemplate['values'][0]['msg_html'];

      // Get upcoming events from current month.
      $currentDay = date('Y-m-d');
      $lastDayOfThisMonth = date("Y-m-t", strtotime(date('Y-m-d H:i:s')));
      $events = civicrm_api3('Event', 'get', [
        'sequential' => 1,
        'return' => ["title", "start_date"],
        'is_online_registration' => 1,
        'start_date' => ['BETWEEN' => [$currentDay, $lastDayOfThisMonth]],
        'is_active' => 1,
      ]);

      $eventListHTML = "<ul>";
      foreach ($events['values'] as $eventValues) {
        $URL = CRM_Utils_System::baseURL() . "civicrm/event/register?reset=1&id=" . $eventValues['id'];
        $eventListHTML .= "<li><a href=" . $URL . ">" . $eventValues['event_title'] . "</a></li>";
      }
      $eventListHTML .= "</ul>";

      // Replace event list token with event list.
      $text_message = str_replace("{event.upcoming_event_list}", $eventListHTML, $text_message);
      $html_message = str_replace("{event.upcoming_event_list}", $eventListHTML, $html_message);

      // Get from email value.
      $fromEmailValues = CRM_Core_BAO_Email::getFromEmail();
      if (is_numeric(key($fromEmailValues))) {
        $defaultEmail = civicrm_api3('email', 'getsingle', array('id' => key($fromEmailValues)));
        $fromEmail = $defaultEmail['email'];
      }

      // Get all contacts emails.
      $contacts = civicrm_api3('Contact', 'get', [
        'sequential' => 1,
        'options' => ['limit' => 0],
        'contact_type' => "Individual",
        'do_not_email' => 0,
        'email' => ['IS NOT NULL' => 1],
      ]);

      if (!empty($contacts) && $contacts['count'] > 0) {
        $contactEmail = [];
        foreach ($contacts['values'] as $key => $value) {
          if ($fromEmail == $value['email']) {
            continue;
          }

          $contactEmail[$value['contact_id']] = $value['email'];

          $toEmail = $value['email'];
          $toDisplayName = $value['display_name'];

          // Replace contact tokens from message template.
          foreach ($value as $fieldName => $fieldValue) {
            $text_message = str_replace("{contact." . $fieldName . "}", $fieldValue, $text_message);
            $html_message = str_replace("{contact." . $fieldName . "}", $fieldValue, $html_message);
          }

          // Create the params array.
          $mailParams = array(
            'groupName' => 'Sponsorship Email Sender',
            'from' => $fromEmail,
            'toName' => $toDisplayName,
            'toEmail' => $toEmail,
            'subject' => $subject,
            'cc' => NULL,
            'bcc' => NULL,
            'text' => $text_message,
            'html' => $html_message,
            'attachments' => NULL,
          );
          // Send emails to each contact.
          if (!CRM_Utils_Mail::send($mailParams)) {
            return FALSE;
          }
        }
        return civicrm_api3_create_success($contactEmail, $params, NULL, NULL);
      }
    }
    else {
      throw new API_Exception("Sorry, not found message template with id " . $params['message_temp'] . " , Please check passed parameters.", 1234);
    }
  }
}
