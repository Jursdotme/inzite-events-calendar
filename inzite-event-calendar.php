<?php
/*
Plugin Name: Inzite Event Calendar
Description: Show events in a calendar.
Author: Johnnie Berthelsen & Rasmus Jürs
Version: 1.0.1
Author URI: http://inzite.dk
*/

/**
 * Initialize the language files
 */

define( 'MY_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );

function inzite_event_calendar_lang(){
	load_plugin_textdomain('inzite_event_calendar', false, MY_PLUGIN_PATH . '/lang/');
}
add_action('plugins_loaded', 'inzite_event_calendar_lang');


if (is_admin()) {
	add_action( 'admin_enqueue_scripts', 'inz_events_admin_scripts' );
	add_action( 'add_meta_boxes', 'inz_events_metabox' );
	add_action( 'save_post', 'inz_events_save_info' );
}

include( plugin_dir_path( __FILE__ ) . 'inc/post-type.php');

function frontend_enqueue_scripts() {
  wp_enqueue_script(
      'inz-event-calendar',
      MY_PLUGIN_PATH . 'js/inz_events.js',
      array( 'jquery'),
      '1.0',
      true
  );
  wp_localize_script( 'inz-event-calendar' , 'inz_events', array('ajaxurl' => admin_url( 'admin-ajax.php'), 'img' => MY_PLUGIN_PATH . 'img/' ) );
}

function frontend_ajax_date_handler() {
  global $wpdb;
  $get_date = intval(esc_attr($_POST['date']));
  $url = site_url( '/reservationer/' );
  if ($get_date) {
    $get_date = date('Y-m-d', $get_date);
    $posts = $wpdb->get_results("SELECT p.ID, p.post_title, p.post_excerpt, DATE_FORMAT(FROM_UNIXTIME(pm.meta_value), '%d') as date_number,
      DATE_FORMAT(FROM_UNIXTIME(pm.meta_value), '%a') as date_text, DATE_FORMAT(FROM_UNIXTIME(pm.meta_value), '%M') as date_month,
      pm2.meta_value as start_time, pm3.meta_value as end_time, CONCAT( '{$url}' , p.post_name ) as post_name
      FROM $wpdb->posts as p INNER JOIN $wpdb->postmeta as pm ON pm.post_id = p.ID AND pm.meta_key = 'inz_event_start_date'
      LEFT JOIN $wpdb->postmeta as pm2 ON pm2.post_id = p.ID AND pm2.meta_key = 'inz_event_start_time'
      LEFT JOIN $wpdb->postmeta as pm3 ON pm3.post_id = p.ID AND pm3.meta_key = 'inz_event_end_time'
      WHERE p.post_type = 'inz_events' AND p.post_status = 'publish'
      AND DATE_FORMAT(FROM_UNIXTIME(pm.meta_value), '%Y-%m-%d') = '{$get_date}'
      ", OBJECT);

    echo json_encode($posts);
  }
  exit;
}

function frontend_ajax_month_handler() {
  $get_month = intval(esc_attr($_POST['month']));
  $get_year = intval(esc_attr($_POST['year']));
  $this->get_event_calendar($get_month,$get_year);
}

// Enqueue Styles and Scripts
function inz_events_admin_scripts( $hook ) {

    global $post_type;

    if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ( 'inz_events' == $post_type ) ) {
      wp_enqueue_script(
          'inz-event-calendar',
          MY_PLUGIN_PATH . 'js/inz_events_admin.js',
          array( 'jquery', 'jquery-ui-datepicker' ),
          '1.0',
          true
      );

      wp_enqueue_style(
          'jquery-ui-calendar',
          MY_PLUGIN_PATH . 'css/jquery-ui.css',
          false,
          '1.10.4',
          'all'
      );

		} else if ( ( 'edit.php' == $hook ) && ( 'inz_events' == $post_type ) ) {
          wp_enqueue_script(
              'inz_events',
              MY_PLUGIN_PATH . 'js/inz_events_admin.js',
              array( 'jquery', 'jquery-ui-datepicker' ),
              '1.0',
              true
          );
    }
}

wp_enqueue_style(
		'inzite-calendar',
		MY_PLUGIN_PATH . 'css/inzite-calendar.css',
		false,
		'1.0.1',
		'all'
);


// Add Event info metabox
function inz_events_metabox( $post_type ) {

    if ($post_type == 'inz_events') {
    add_meta_box(
        'inz_events-info-metabox',
        __( 'Datoer', 'inz_events' ),
        'inz_events_render_metabox',
        'inz_events',
        'side',
        'core'
    );
    }
}

// Add Event info metabox contents
function inz_events_render_metabox( $post ) {

  // generate a nonce field
  wp_nonce_field( basename( __FILE__ ), 'inz_event-info-nonce' );

  // get previously saved meta values (if any)
  $event_start_date = get_post_meta( $post->ID, 'inz_event_start_date', true );
  $event_start_time = get_post_meta( $post->ID, 'inz_event_start_time', true );
  $event_end_time = get_post_meta( $post->ID, 'inz_event_end_time', true );
  //$event_end_date = get_post_meta( $post->ID, 'inz_event_end_date', true );
  // if there is previously saved value then retrieve it, else set it to the current time
  $event_start_date = ! empty( $event_start_date ) ? $event_start_date : time();

  // we assume that if the end date is not present, event ends on the same day
  //$event_end_date = ! empty( $event_end_date ) ? $event_end_date : $event_start_date;


  echo'<p>';
  echo'<label for="inz_event_start_date">Arrangement start dato:</label>';
  echo'<input class="widefat" id="inz_event_start_date" type="text" name="inz_event_start_date" placeholder="Start dato" value="'.date( 'd-m-Y', $event_start_date ).'" />';
  echo'<label for="inz_event_start_time">Start tidspunkt:</label>';
  echo'<input class="widefat" id="inz_event_start_time" type="text" name="inz_event_start_time" placeholder="ex: 17:00" value="'.$event_start_time.'" />';
  echo'<label for="inz_event_end_time">Slut tidspunkt:</label>';
  echo'<input class="widefat" id="inz_event_end_time" type="text" name="inz_event_end_time" placeholder="18:00" value="'.$event_end_time.'" />';
  // echo'<label for="inz_event_end_date">Arrangement slut dato:</label>';
  // echo'<input class="widefat" id="inz_event_end_date" type="text" name="inz_event_end_date" placeholder="Slut dato" value="'.date( 'd-m-Y', $event_end_date ).'" />';
  echo'</p>';
  echo'<br>';
}

// Save meta values
function inz_events_save_info( $post_id ) {

  global $_POST;

  // checking if the post being saved is an 'event',
  // if not, then return
  if ( isset($_POST['post_type']) && 'inz_events' != $_POST['post_type'] ) {
    return;
  }

  // checking for the 'save' status
  $is_autosave = wp_is_post_autosave( $post_id );
  $is_revision = wp_is_post_revision( $post_id );
  $is_valid_nonce = ( isset( $_POST['inz_event-info-nonce'] ) && ( wp_verify_nonce( $_POST['inz_event-info-nonce'], basename( __FILE__ ) ) ) ) ? true : false;

  // exit depending on the save status or if the nonce is not valid
  if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
      return;
  }

  // checking for the values and performing necessary actions
  if ( isset( $_POST['inz_event_start_date'] ) ) {
      update_post_meta( $post_id, 'inz_event_start_date', strtotime( $_POST['inz_event_start_date'] ) );
  }

  if ( isset( $_POST['inz_event_start_time'] ) ) {
      update_post_meta( $post_id, 'inz_event_start_time', ( $_POST['inz_event_start_time'] ) );
  }

  if ( isset( $_POST['inz_event_end_time'] ) ) {
    update_post_meta( $post_id, 'inz_event_end_time', ( $_POST['inz_event_end_time'] ) );
  }

}

function get_event_calendar(  $monthnum = '', $year='' ) {

  global $wpdb, $m, $wp_locale, $posts;
  $key = md5( $m . $monthnum . $year );
  // Quick check. If we have no posts at all, abort!
  if ( isset( $_GET['w'] ) ) {
    $w = (int) $_GET['w'];
  }
  // week_begins = 0 stands for Sunday
  $week_begins = (int) get_option( 'start_of_week' );
  $ts = current_time( 'timestamp' );

  // Let's figure out when we are
  if ( ! empty( $monthnum ) && ! empty( $year ) ) {
    $thismonth = zeroise( intval( $monthnum ), 2 );
    $thisyear = (int) $year;
  } elseif ( ! empty( $w ) ) {
    // We need to get the month from MySQL
    $thisyear = (int) substr( $m, 0, 4 );
    //it seems MySQL's weeks disagree with PHP's
    $d = ( ( $w - 1 ) * 7 ) + 6;
    $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
  } elseif ( ! empty( $m ) ) {
    $thisyear = (int) substr( $m, 0, 4 );
    if ( strlen( $m ) < 6 ) {
      $thismonth = '01';
    } else {
      $thismonth = zeroise( (int) substr( $m, 4, 2 ), 2 );
    }
  } else {
    $thisyear = gmdate( 'Y', $ts );
    $thismonth = gmdate( 'm', $ts );
  }

  $unixmonth = mktime( 0, 0 , 0, $thismonth, 1, $thisyear );
  $last_day = date( 't', $unixmonth );


  if ($thismonth == '12') {
    $nextmonth = zeroise( intval( 1 ), 2 );
    $nextyear = ($thisyear)+1;
    $prevmonth = zeroise( (intval($thismonth)-1), 2 );;
    $prevyear = ($thisyear);
  } else if ($thismonth == '01') {
    $nextmonth = ($thismonth)+1;
    $nextyear = ($thisyear);
    $prevmonth = 12;
    $prevyear = ($thisyear)-1;
  } else {
    $nextmonth = zeroise( (intval($thismonth)+1) , 2 );
    $nextyear = ($thisyear);
    $prevmonth = zeroise( (intval($thismonth)-1) , 2 );
    $prevyear = ($thisyear);
  }

  /* translators: Calendar caption: 1: month name, 2: 4-digit year */
  $calendar_output = '<table class="inz-event-calendar">
  <thead>
  <tr>
  ';

  $calendar_output .= "\n\t\t".'<td colspan="2" class="prev_month"><a href="javascript:inz_change_month('.$prevmonth.','.$prevyear.');">&laquo; Forrige</a></td>';
  $calendar_output .= "\n\t\t".'<td colspan="3" class="this_month">'. $wp_locale->get_month( $thismonth ) . '<div class="this_year">' . date( 'Y', $unixmonth ).'</div></td>';
  $calendar_output .= "\n\t\t".'<td colspan="2" class="next_month"><a href="javascript:inz_change_month('.$nextmonth.','.$nextyear.');">Næste &raquo;</a></td>';

  $calendar_output .= '
  </tr>
  </thead>

  <tbody>
  <tr>';

  $daywithpost = array();
  $calendar_info = "";

  // Get days with posts //DAYOFMONTH
  $dayswithposts = $wpdb->get_results("SELECT DAYOFMONTH(FROM_UNIXTIME(pm.meta_value)), p.ID
    FROM $wpdb->posts as p INNER JOIN $wpdb->postmeta as pm ON pm.post_id = p.ID AND pm.meta_key = 'inz_event_start_date'
    WHERE p.post_type = 'inz_events' AND p.post_status = 'publish'
    AND FROM_UNIXTIME(pm.meta_value) <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'
    AND FROM_UNIXTIME(pm.meta_value) >= '{$thisyear}-{$thismonth}-01 00:00:00'
    ", ARRAY_N);
    // ,pm2.meta_value, pm3.meta_value
    // LEFT JOIN $wpdb->postmeta as pm2 ON pm2.post_id = p.ID AND pm2.meta_key = 'inz_event_start_time'
    // LEFT JOIN $wpdb->postmeta as pm3 ON pm3.post_id = p.ID AND pm3.meta_key = 'inz_event_end_time'

  // echo '<pre>';
  if ( $dayswithposts ) {
    foreach ( (array) $dayswithposts as $daywith ) {
      if ( $daywithpost[$daywith[0]] ) {
        array_push($daywithpost[$daywith[0]], $daywith[1]);
      } else {
        $daywithpost[$daywith[0]] = array($daywith[1]);
      }

    }
  }
  // print_r($daywithpost);
  // echo '</pre>';
  // See how much we should pad in the beginning
  $pad = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );
  if ( 0 != $pad ) {
    $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr( $pad ) .'" class="pad">&nbsp;</td>';
  }

  $newrow = false;
  $daysinmonth = (int) date( 't', $unixmonth );

  for ( $day = 1; $day <= $daysinmonth; ++$day ) {
    if ( isset($newrow) && $newrow ) {
      $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
    }
    $newrow = false;

    if ( $day == gmdate( 'j', $ts ) && $thismonth == gmdate( 'm', $ts ) && $thisyear == gmdate( 'Y', $ts ) ) {
      $calendar_output .= '<td class="date active">';
    } else {
      $calendar_output .= '<td class="date">';
    }
    if ( $daywithpost[$day] ) {
      // any posts today?
      $date_format = strtotime( "{$day}-{$thismonth}-{$thisyear}" );
      $label = sprintf( 'Vis %d arrangementer', count($daywithpost[$day]));
      $calendar_output .= sprintf(
        '<a title="%s" data-date="%s">%s</a>',
          esc_attr( $label ),
          $date_format,
          $day
        );

    } else {
      $calendar_output .= '<span>' . $day . '</span>';
    }
    $calendar_output .= '</td>';

    if ( 6 == calendar_week_mod( date( 'w', mktime(0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins ) ) {
      $newrow = true;
    }
  }

  $pad = 7 - calendar_week_mod( date( 'w', mktime( 0, 0 , 0, $thismonth, $day, $thisyear ) ) - $week_begins );
  if ( $pad != 0 && $pad != 7 ) {
    $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr( $pad ) .'">&nbsp;</td>';
  }
  $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

  echo $calendar_output;
  if ( ! empty( $monthnum ) && ! empty( $year ) ) {
    exit;
  } else {
    return;
  }

}

function inzite_events_calendar_widget_collection($folders){
	$folders[] = plugin_dir_path(__FILE__).'widgets/';
	return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'inzite_events_calendar_widget_collection');

//pannel Group

function inzite_event_calendar_group($tabs) {
    $tabs[] = array(
        'title' => __('Event Calendar Widgets', 'inzite_event_calendar'),
        'filter' => array(
            'groups' => array('inzite_event_calendar')
        )
    );

    return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'inzite_event_calendar_group', 20);


// Add roles
function event_manager_role() {
	add_role(
		'event_manager',
		'Event Manager',
		array(
		  'read' => true,
		  'edit_posts' => false,
		  'delete_posts' => false,
		  'publish_posts' => false,
		  'upload_files' => true,
		)
	);
}
register_activation_hook( __FILE__, 'event_manager_role' );

// add capabilities
if (get_role( 'event_manager' )) { // Check if role exists
	add_action('admin_init','event_add_role_caps',999);
}

function event_add_role_caps() {

	// Add the roles you'd like to administer the custom post types
	$roles = array('event_manager','editor','administrator');

	// Loop through each role and assign capabilities
	foreach($roles as $the_role) {
		$role = get_role($the_role);

			$role->add_cap( 'read' );
		  $role->add_cap( 'read_event');
		  $role->add_cap( 'read_private_events' );
		  $role->add_cap( 'edit_event' );
		  $role->add_cap( 'edit_events' );
		  $role->add_cap( 'edit_others_events' );
		  $role->add_cap( 'edit_published_events' );
		  $role->add_cap( 'publish_events' );
		  $role->add_cap( 'delete_others_events' );
		  $role->add_cap( 'delete_private_events' );
		  $role->add_cap( 'delete_published_events' );
	}
};

/* Filter the single_template with our custom function*/
function get_custom_post_type_template($single_template) {
     global $post;


		 if ($post->post_type == 'inz_events') {
			 if (file_exists(get_template_directory() . '/templates/single-inz_events.php')) {
				 $single_template = get_template_directory() . '/templates/single-inz_events.php';
			 } else {
				 $single_template = dirname( __FILE__ ) . '/single-inz_events.php';
			 }
     }
     return $single_template;
}
add_filter( 'single_template', 'get_custom_post_type_template' );
