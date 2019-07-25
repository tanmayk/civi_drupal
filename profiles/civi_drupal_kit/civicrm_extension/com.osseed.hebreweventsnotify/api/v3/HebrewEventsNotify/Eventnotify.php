<?php
use CRM_Hebreweventsnotify_ExtensionUtil as E;

/**
 * HebrewEventsNotify.Eventnotify API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
// function _civicrm_api3_hebrew_events_notify_Eventnotify_spec(&$spec) {
//   $spec['magicword']['api.required'] = 1;
// }

/**
 * HebrewEventsNotify.Eventnotify API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_hebrew_events_notify_Eventnotify($params) {
  if (array_key_exists('message_temp', $params) && array_key_exists('roles', $params)) {
    // Get message template data from passed parameters.
    $messageTemplate = civicrm_api3('MessageTemplate', 'get', [
      'sequential' => 1,
      'id' => $params['message_temp'],
    ]);

    if (!empty($messageTemplate['values'])) {
      $subject = $messageTemplate['values'][0]['msg_subject'];
      $text_message = $messageTemplate['values'][0]['msg_text'];
      $html_message = $messageTemplate['values'][0]['msg_html'];

      $fieldResult = civicrm_api3('CustomField', 'get', [
        'sequential' => 1,
        'return' => ["id"],
        'custom_group_id' => "constituent_information",
        'name' => "Marriage_Date",
      ]);
      $marriagefieldId = $fieldResult['values'][0]['id'];

      $anniversaryList = "SELECT civicrm_contact.id,civicrm_contact.display_name,civicrm_value_constituent_information_1.marriage_date_$marriagefieldId,civicrm_relationship.contact_id_b
                          FROM civicrm_contact INNER JOIN civicrm_value_constituent_information_1 ON civicrm_contact.id = civicrm_value_constituent_information_1.entity_id
                          INNER JOIN civicrm_relationship ON civicrm_contact.id	= civicrm_relationship.contact_id_a
                          WHERE MONTH(marriage_date_$marriagefieldId) = MONTH(CURRENT_DATE()) ORDER by id";

      $anniversaryDao = CRM_Core_DAO::executeQuery($anniversaryList);
      $anniversaryListHTML = "<tr>";

      while($anniversaryDao->fetch()) {
        $anvdate = 'marriage_date_' . $marriagefieldId;
        $anvdat = $anniversaryDao->$anvdate;
        $datefield = new DateTime($anvdat);
        $anniversaryDate = $datefield->format('Y-m-d');

        $URL = CRM_Utils_System::baseURL() ."civicrm/contact/view?reset=1&cid=" .$anniversaryDao->id;
        $anniversaryListHTML .= "<tr><td><a href=" . $URL . ">" . $anniversaryDao->display_name . "</a></td><td>&nbsp".$anniversaryDate."</td></tr>";
      }

      $anniversaryListHTML .=  "</tr>";

      $customResult = civicrm_api3('CustomField', 'get', [
        'sequential' => 1,
        'return' => ["id"],
        'name' => "Hebrew_Date_of_Birth",
        'custom_group_id' => "Hebrew_Birth_Dates",
      ]);

      $birthfieldId = $customResult['values'][0]['id'];

      $birthdateList= "SELECT civicrm_contact.id,civicrm_contact.display_name,civicrm_contact.birth_date,civicrm_value_hebrew_birth_dates.hebrew_date_of_birth_$birthfieldId
                       FROM civicrm_contact INNER JOIN civicrm_value_hebrew_birth_dates ON civicrm_contact.id = civicrm_value_hebrew_birth_dates.entity_id
                       WHERE MONTH(birth_date) = MONTH(CURRENT_DATE()) ORDER by id";

      $birthdateDao= CRM_Core_DAO::executeQuery($birthdateList);
      $birthdayListHTML = "<tr>";

      while($birthdateDao->fetch()){
        $URL = CRM_Utils_System::baseURL() ."civicrm/contact/view?reset=1&cid=" .$birthdateDao->id;
        $birthdayListHTML .= "<tr><td><a href=" . $URL . ">" . $birthdateDao->display_name . "</a></td><td>&nbsp".$birthdateDao->birth_date."</td></tr>";
      }
      $birthdayListHTML .=  "</tr>";

      // Get from email value.
      $fromEmailValues = CRM_Core_BAO_Email::getFromEmail();
      if (is_numeric(key($fromEmailValues))) {
        $defaultEmail = civicrm_api3('email', 'getsingle', array('id' => key($fromEmailValues)));
        $fromEmail = $defaultEmail['email'];
      }

      // Check Higher authorities roles are available from passed parameters.
      if (!empty($params['roles'])) {
        $roleLabeles = explode(', ', $params['roles']);
        foreach ($roleLabeles as $roleLabel) {
          $roleResults = civicrm_api3('OptionValue', 'get', [
            'sequential' => 1,
            'label' => $roleLabel,
            'option_group_id' => "acl_role",
          ]);
          if (!empty($roleResults['values'])) {
            $aclRolesQuery = 'SELECT `entity_id` from civicrm_acl_entity_role where `acl_role_id` = ' . $roleResults['values'][0]['value'];
            $aclRolesDAO = CRM_Core_DAO::executeQuery($aclRolesQuery);
            while($aclRolesDAO->fetch()) {
              $groupId = $aclRolesDAO->entity_id;
            }
            if (!empty($groupId)) {
              $contactResult = civicrm_api3('Contact', 'get', [
                'sequential' => 1,
                'return' => ["email","display_name"],
                'group' => $groupId,
                'do_not_email' => 0,
                'options' => ['limit' => 0],
              ]);
              if (!empty($contactResult['values'])) {
                foreach ($contactResult['values'] as $contactValue) {
                  $heightAuthoriesMails[$contactValue['contact_id']] = $contactValue['email'];
                }
              }
            }
          }
        }

        if (!empty($contactResult) && $contactResult['count'] > 0) {
          $contactEmail = [];
          // Replace contact tokens from message template.
          $html_message = str_replace("{contact.birth_date_list}", $birthdayListHTML, $html_message);
          $html_message = str_replace("{contact.anniversary_list}", $anniversaryListHTML, $html_message);
          $text_message = str_replace("{contact.birth_date_list}", $birthdayListHTML, $text_message);
          $text_message = str_replace("{contact.anniversary_list}", $anniversaryListHTML, $text_message);

          foreach ($contactResult['values'] as $key => $value) {
            if ($fromEmail == $value['email']) {
              continue;
            }

            $contactEmail[$value['contact_id']] = $value['email'];

            $toEmail = $value['email'];
            $toDisplayName = $value['display_name'];

            // Create the params array.
            $mailParams = array(
              'groupName' => 'Hebrew Event notify Email Sender',
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

            //Send emails to each contact.
            if (!CRM_Utils_Mail::send($mailParams)) {
              return FALSE;
            }
          }
          return civicrm_api3_create_success($contactEmail, $params, NULL, NULL);
        }
        else {
          throw new API_Exception("No any contacts found for sending emails.", 1234);
        }
      }

    }
    else {
        throw new API_Exception("Sorry, not found message template with id " . $params['message_temp'] . " , Please check passed parameters.", 1234);
    }
  }
}
