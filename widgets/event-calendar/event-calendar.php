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
		add_action( 'wp_enqueue_scripts' , 'frontend_enqueue_scripts' );
		add_action( 'wp_ajax_nopriv_inz_show_events', 'frontend_ajax_date_handler' );
		add_action( 'wp_ajax_inz_show_events', 'frontend_ajax_date_handler' );
		add_action( 'wp_ajax_nopriv_inz_change_month', 'frontend_ajax_month_handler' );
		add_action( 'wp_ajax_inz_change_month', 'frontend_ajax_month_handler' );
	}

	function get_template_name($instance) {
		return 'event-calendar-template';
	}

}
siteorigin_widget_register('event-calendar-widget', __FILE__, 'Widget_Event_Calendar');
?>
