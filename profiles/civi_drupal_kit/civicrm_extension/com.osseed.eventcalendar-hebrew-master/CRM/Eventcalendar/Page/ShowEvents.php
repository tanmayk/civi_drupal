<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2011
 * $Id$
 *
 */

require_once 'CRM/Core/Page.php';

class CRM_Eventcalendar_Page_ShowEvents extends CRM_Core_Page {

  function run() {
    CRM_Core_Resources::singleton()->addScriptFile('com.osseed.eventcalendar', 'js/moment.js',5);
    CRM_Core_Resources::singleton()->addScriptFile('com.osseed.eventcalendar', 'js/fullcalendar.js',10);
    CRM_Core_Resources::singleton()->addStyleFile('com.osseed.eventcalendar', 'css/civicrm_events.css');
    CRM_Core_Resources::singleton()->addStyleFile('com.osseed.eventcalendar', 'css/fullcalendar.css');

    $eventTypesFilter = array();
    $civieventTypesList = CRM_Event_PseudoConstant::eventType();

    $config = CRM_Core_Config::singleton();

    //get settings
    $settings = $this->_eventCalendar_getSettings();

    //set title from settings; allow empty value so we don't duplicate titles
    CRM_Utils_System::setTitle(ts($settings['calendar_title']));

    $whereCondition = '';
    $eventTypes = $settings['event_types'];

    if(!empty($eventTypes)) {
      $eventTypesList = implode(',', array_keys($eventTypes));
      $whereCondition .= " AND civicrm_event.event_type_id in ({$eventTypesList})";
    }
    else {
      $whereCondition .= ' AND civicrm_event.event_type_id in (0)';
    }

    //Show/Hide Past Events
    $currentDate = date("Y-m-d h:i:s", time());
    if (empty($settings['event_past'])) {
      $whereCondition .= " AND civicrm_event.start_date > '" .$currentDate . "'";
    }

    // Show events according to number of next months
    if(!empty($settings['event_from_month'])) {
      $monthEvents = $settings['event_from_month'];
      $monthEventsDate = date("Y-m-d h:i:s",
        strtotime(date("Y-m-d h:i:s", strtotime($currentDate))."+".$monthEvents." month"));
      $whereCondition .= " AND civicrm_event.start_date < '" .$monthEventsDate . "'";
    }

    //Show/Hide Public Events
    if(!empty($settings['event_is_public'])) {
      $whereCondition .= " AND civicrm_event.is_public = 1";
    }

    $query = "
      SELECT `id`, `title`, `start_date` start, `end_date` end ,`event_type_id` event_type
      FROM `civicrm_event`
      WHERE civicrm_event.is_active = 1
        AND civicrm_event.is_template = 0
    ";

    $query .= $whereCondition;
    $events['events'] = array();

    $dao = CRM_Core_DAO::executeQuery($query);
    $eventCalendarParams = array ('title' => 'title', 'start' => 'start', 'url' => 'url');

    if(!empty($settings['event_end_date'])) {
      $eventCalendarParams['end'] = 'end';
    }

    while ($dao->fetch()) {
      $eventData = array();
      $dao->url = html_entity_decode(CRM_Utils_System::url('civicrm/event/info', 'id='.$dao->id));
      foreach ($eventCalendarParams as $k) {
        $eventData[$k] = $dao->$k;
        if(!empty($eventTypes)) {
          $eventData['backgroundColor'] = "#{$eventTypes[$dao->event_type]}";
          $eventData['eventType'] = $civieventTypesList[$dao->event_type];
        }
      }
      $events['timeDisplay'] = $settings['event_time'];
      $events['isfilter'] = $settings['event_event_type_filter'];
      $events['events'][] = $eventData;
      $eventTypesFilter[$dao->event_type] = $civieventTypesList[$dao->event_type];
    }
    // If hebrew calender setting is enable then lets add the json url
    $events['events'] = array( array('events' => $events['events']));

    // Get Major holidays?
    $maj_holidays = !empty($settings['event_jewish_holidays']) ? 'on' : 'off';
    //  Get Rosh chodesh?
    $rosh_hodesh  = !empty($settings['event_rosh_chodesh']) ? 'on'  : 'off';
    //  Get Hebrew dates
    $hebrewdate = !empty($settings['event_hebrew_date'])  ? 'on'  : 'off';
    //  Get special shabbatot
    $special_shabbatot  = !empty($settings['event_special_shabbatot'])  ? 'on'  : 'off';
    //  Get shabbat parsha
    $shabbat_parasha  = !empty($settings['event_shabbat_parsha']) ? 'on'  : 'off';

    //$timezone = variable_get('date_default_timezone');   'America/New_York'
    $timezone = 'America/New_York';
    $latitude = $settings['event_candlelighting_latitude'] ;
    $longitude  = $settings['event_candlelighting_longitude'];
    $zip_code  = $settings['event_country_zipcode'];
    $is_candlelight  = $settings['event_candlelighting_minutes'];
    $is_sunsetTimes  = $settings['event_sunset_times'];
    $candlelighting_zip  = $settings['event_candlelighting_minutes_zip'];
    $candlelighting_lat  = $settings['event_candlelighting_minutes_lat'];
    $geo_api_query_string = '';
    $have_valid_location = '';

    //  Get timezone from parameters
    if(($is_sunsetTimes || $is_candlelight) && $candlelighting_lat) {
      if($latitude == 0 || $longitude ==  0) {
        $geo_api_query_string = "&geo=none";
      } else {
        if ($latitude >= -90 && $latitude <= 90  && $longitude >= -180 && $longitude <= 180 && strlen($timezone) > 0 ) {
          $have_valid_location = true;
          $geo_api_query_string = "&geo=pos&latitude=".$latitude."&longitude=".$longitude."&tzid=".$timezone;
        } else {
          $geo_api_query_string = "&geo=none";
        }
      }
    } else {
      if(($is_sunsetTimes || $is_candlelight) && $candlelighting_zip) {
        $zip_code_5 = substr( $zip_code, 0 , 5 );
        if( strlen( $zip_code_5) == 5 && is_numeric($zip_code_5)){
          $geo_api_query_string = "&geo=zip&zip=".$zip_code_5;
          $have_valid_location = true;
        }else {
          $geo_api_query_string = "&geo=none";
        }
      }
    }
    if((!empty($settings['event_candlelighting_minutes']) &&  $have_valid_location) ||
      (!empty($settings['event_sunset_times']) &&  $have_valid_location) ) {
      $candle_minutes_before_sunset = "18";
      $candle_query_str  = "&c=on&b=".$candle_minutes_before_sunset;
    } else {
      $candle_query_str  = "&c=off";
    }

    if(!empty($settings['event_event_type_filter'])) {
      $events['eventTypes'][]  = $eventTypesFilter;
      $this->assign('eventTypes', $eventTypesFilter);
    }

    $hebacal_url = "https://www.hebcal.com/hebcal/?v=1&cfg=fc&m=0&i=on".
      "&maj=".$maj_holidays.
      "&nx=" .$rosh_hodesh.
      "&d=" .$hebrewdate.
      "&ss=" .$special_shabbatot.
      "&s=" .$shabbat_parasha.$candle_query_str.$geo_api_query_string;

    $hebcal_json_data = array (
      'url'=> $hebacal_url ,
      'backgroundColor' => 'white',
      'color' => '#C9CC98',
      'textColor' => '#7A7676',
      'borderColor' => 'white',
    );

    if(!empty($settings['event_hebrewcal'])) {
      $events['events'][] = $hebcal_json_data;
      $events['sunsetDisplay'] = $settings['event_sunset_times'];
      $events['candleDisplay'] = $settings['event_candlelighting_minutes'];
    }

    $events['header']['left'] = 'month,agendaWeek,agendaDay';
    $events['header']['center'] = 'title';
    $events['header']['right'] = 'prev,next';
    $events['displayEventEnd'] = 'true';
    //send Events array to calendar.
    $this->assign('civicrm_events', json_encode($events));
    parent::run();
  }

  /*
   * retrieve and reconstruct extension settings
   */
  function _eventCalendar_getSettings() {
    $settings = array(
      'calendar_title' => Civi::settings()->get('eventcalendar_calendar_title'),
      'event_past' => Civi::settings()->get('eventcalendar_event_past'),
      'event_end_date' => Civi::settings()->get('eventcalendar_event_end_date'),
      'event_is_public' => Civi::settings()->get('eventcalendar_event_is_public'),
      'event_month' => Civi::settings()->get('eventcalendar_event_month'),
      'event_from_month' => Civi::settings()->get('eventcalendar_event_from_month'),
      'event_time' => Civi::settings()->get('eventcalendar_event_time'),
      'event_hebrewcal' =>  Civi::settings()->get('eventcalendar_event_hebrewcal'),
      'event_jewish_holidays'  =>  Civi::settings()->get('eventcalendar_event_jewish_holidays'),
      'event_rosh_chodesh'  =>  Civi::settings()->get('eventcalendar_event_rosh_chodesh'),
      'event_hebrew_date' =>  Civi::settings()->get('eventcalendar_event_hebrew_date'),
      'event_special_shabbatot' =>  Civi::settings()->get('eventcalendar_event_special_shabbatot'),
      'event_candlelighting_latitude' =>  Civi::settings()->get('eventcalendar_event_candlelighting_latitude'),
      'event_candlelighting_longitude' =>  Civi::settings()->get('eventcalendar_event_candlelighting_longitude'),
      'event_candlelighting_minutes' =>  Civi::settings()->get('eventcalendar_event_candleminute_before_sundown'),
      'event_country_zipcode' =>  Civi::settings()->get('eventcalendar_event_country_zipcode'),
      'event_shabbat_parsha' =>  Civi::settings()->get('eventcalendar_event_shabbat_parasha'),
      'event_candlelighting_minutes_zip' =>  Civi::settings()->get('eventcalendar_event_candlelighting_geotype_zip'),
      'event_candlelighting_minutes_lat' =>  Civi::settings()->get('eventcalendar_event_candlelighting_geotype_lat'),
      'event_sunset_times' =>  Civi::settings()->get('eventcalendar_event_sunsettimes'),
      'event_event_type_filter' =>  Civi::settings()->get('eventcalendar_event_type_filter'),
    );

    $eventTypes = Civi::settings()->get('eventcalendar_event_types');
    $eventTypes = json_decode($eventTypes);
    foreach ($eventTypes as $eventType) {
      $settings['event_types'][$eventType->id] = $eventType->color;
    }

    /*Civi::log()->debug('_eventCalendar_getSettings', array(
      'eventTypes' => $eventTypes,
      'settings' => $settings,
    ));*/

    return $settings;
  }
}
