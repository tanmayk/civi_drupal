<?php

/*
 * Settings metadata file
 */
return array(
  'eventcalendar_calendar_title' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_calendar_title',
    'title' => 'Calendar Title',
    'type' => 'String',
    'html_type' => 'text',
    'quick_form_type' => 'Element',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Event calendar title.',
    'help_text' => '',
    'html_attributes' => array('class' => 'huge'),
  ),
  'eventcalendar_event_past' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_past',
    'title' => 'Show Past Events',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 1,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show past events as well as current/future.',
    'help_text' => '',
  ),
  'eventcalendar_event_end_date' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_end_date',
    'title' => 'Show End Date',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 1,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show the event with start and end dates on the calendar.',
    'help_text' => '',
  ),
  'eventcalendar_event_is_public' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_is_public',
    'title' => 'Show Public Events',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 1,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show only public events, or all events.',
    'help_text' => '',
  ),
  'eventcalendar_event_month' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_month',
    'title' => 'Events by Month',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 1,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show the month parameter on calendar.',
    'help_text' => '',
  ),
  'eventcalendar_event_time' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_time',
    'title' => 'Events timings',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 1,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show the event timings on calendar.',
    'help_text' => '',
  ),
  'eventcalendar_event_from_month' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_from_month',
    'title' => 'Events from Month',
    'type' => 'String',
    'html_type' => 'text',
    'quick_form_type' => 'Element',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show events from how many months from current month.',
    'help_text' => '',
    'html_attributes' => array('class' => 'four'),
  ),
  'eventcalendar_event_type_filter' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_eventcal',
    'title' => 'Enable Event types filters',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show event types filter on calendar.',
    'help_text' => '',
  ),
  'eventcalendar_event_hebrewcal' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_hebrewcal',
    'title' => 'Hebrew Calendar Events',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show the events timings from Hebrew calendar.',
    'help_text' => '',
  ),
  'eventcalendar_event_hebrew_date' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_hebrew_date',
    'title' => 'Display hebrew dates',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show the hebrew dates.',
    'help_text' => '',
  ),
  'eventcalendar_event_jewish_holidays' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_jewish_holidays',
    'title' => 'Display Jewish Holidays Major',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Show the Jewish Holidays major events from Hebrew calendar.',
    'help_text' => '',
  ),
  'eventcalendar_event_rosh_chodesh' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_rosh_chodesh',
    'title' => 'Display Rosh Chodesh',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Display the Rosh Chodesh.',
    'help_text' => '',
  ),
  'eventcalendar_event_special_shabbatot' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_special_shabbatot',
    'title' => 'Display the special shabbatot',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Display the special shabbatot.',
    'help_text' => '',
  ),
  'eventcalendar_event_shabbat_parasha' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_shabbat_parasha',
    'title' => 'Display the parasha on Shabbat',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Display the parasha/Torah portion on Shabbat.',
    'help_text' => '',
  ),
  'eventcalendar_event_candleminute_before_sundown' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_candleminute_before_sundown',
    'title' => 'Display candlelighting times',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Display candle lighting times.',
    'help_text' => '',
  ),
  'eventcalendar_event_sunsettimes' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_sunsettimes',
    'title' => 'Display sunset times',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => ' Display sunset times.',
    'help_text' => '',
  ),
  'eventcalendar_event_candlelighting_geotype_zip' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_candlelighting_geotype_zip',
    'title' => 'Configure the location for candlelighting with Zip Code',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => '',
    'help_text' => '',
  ),
  'eventcalendar_event_country_zipcode' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_country_zipcode',
    'title' => 'ZIP code for calculating candlelighting times',
    'type' => 'String',
    'html_type' => 'text',
    'quick_form_type' => 'Element',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'ZIP code for calculating candlelighting times.',
    'help_text' => '',
  ),
  'eventcalendar_event_candlelighting_geotype_lat' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_candlelighting_geotype_lat',
    'title' => 'Configure the location for candlelighting with Lat/long.',
    'type' => 'Integer',
    'html_type' => 'checkbox',
    'quick_form_type' => 'YesNo',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => '',
    'help_text' => '',
  ),
  'eventcalendar_event_candlelighting_latitude' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_candlelighting_latitude',
    'title' => 'Latitude for calculating candlelighting times',
    'type' => 'String',
    'html_type' => 'text',
    'quick_form_type' => 'Element',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'This is required for calculation of candlelighting times.',
    'help_text' => '',
  ),
  'eventcalendar_event_candlelighting_longitude' => array(
    'group_name' => 'Event Calendar Hebrew',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_candlelighting_longitude',
    'title' => 'Longitude for calculating candlelighting times',
    'type' => 'String',
    'html_type' => 'text',
    'quick_form_type' => 'Element',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'This is required for calculation of candlelighting times.',
    'help_text' => '',
  ),
  'eventcalendar_event_types' => array(
    'group_name' => 'Event Calendar',
    'group' => 'eventcalendar',
    'name' => 'eventcalendar_event_types',
    'title' => 'Events Types',
    'type' => 'String',
    'html_type' => 'text',
    'quick_form_type' => 'Element',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Choose which event types will be displayed, and how they will be colored.',
    'help_text' => '',
  ),
);
