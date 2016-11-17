<?php
/*
Widget Name: Event Calendar
Description: Show events in a calendar.
Author: Inzite
Author URI: http://inzite.dk
*/

class Widget_Event_Calendar extends SiteOrigin_Widget {
	function __construct() {
		parent::__construct(
			'event-calendar-widget',
			__('Event kalender', 'event-calendar-widget-text-domain'),
			array(
				'description' => __('Show event calendar.', 'event-calendar-widget-text-domain'),
			),
			array(
			),
			array(
			),
			plugin_dir_path( __FILE__ ) . ''
		);
	}

	function get_template_name($instance) {
		return 'event-calendar-template';
	}

}
siteorigin_widget_register('event-calendar-widget', __FILE__, 'Widget_Event_Calendar');
?>
