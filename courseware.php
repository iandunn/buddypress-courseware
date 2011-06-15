<?php
/*
Plugin Name: BuddyPress ScholarPress Courseware
Plugin URI: http://scholarpress.github.com/buddypress-courseware/
Description: A LMS for BuddyPress.
Author: ScholarPress Dev Crew
Version: 1.0-alfa
License: GNU/GPL 2
Requires at least: WordPress 3.0, BuddyPress 1.3
Tested up to: WordPress 3.2 / BuddyPress 1.3
Author URI: http://github.com/scholarpress/
*/

define( 'BPSP_VERSION', '0.9' );
define( 'BPSP_DEBUG', false ); // This will allow you to see post types in wp-admin
define( 'BPSP_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'BPSP_WEB_URI', WP_PLUGIN_URL . '/' . basename( BPSP_PLUGIN_DIR ) );
define( 'BPSP_PLUGIN_FILE', basename( BPSP_PLUGIN_DIR ) . '/' . basename( __FILE__ ) );

/* Load the components */
require_once BPSP_PLUGIN_DIR . '/wordpress/wordpress.class.php';
require_once BPSP_PLUGIN_DIR . '/roles/roles.class.php';
require_once BPSP_PLUGIN_DIR . '/courses/courses.class.php';
require_once BPSP_PLUGIN_DIR . '/courses/courses.us.class.php';
require_once BPSP_PLUGIN_DIR . '/assignments/assignments.class.php';
require_once BPSP_PLUGIN_DIR . '/responses/responses.class.php';
require_once BPSP_PLUGIN_DIR . '/gradebook/gradebook.class.php';
require_once BPSP_PLUGIN_DIR . '/bibliography/bibliography.class.php';
require_once BPSP_PLUGIN_DIR . '/bibliography/webapis.class.php';
require_once BPSP_PLUGIN_DIR . '/schedules/schedules.class.php';
require_once BPSP_PLUGIN_DIR . '/groups/groups.class.php';
require_once BPSP_PLUGIN_DIR . '/dashboards/dashboards.class.php';
require_once BPSP_PLUGIN_DIR . '/static/static.class.php';
require_once BPSP_PLUGIN_DIR . '/activity/activity.class.php';
require_once BPSP_PLUGIN_DIR . '/notifications/notifications.class.php';

/**
 * i18n
 */
function bpsp_textdomain() {
    load_plugin_textdomain( 'bpsp', false, basename( BPSP_PLUGIN_DIR ) . '/languages' );
}
add_action( 'init', 'bpsp_textdomain' );

/**
 * Register post types and taxonomies
 */
function bpsp_registration() {
    BPSP_Courses::register_post_types();
    BPSP_Assignments::register_post_types();
    BPSP_Responses::register_post_types();
    BPSP_Gradebook::register_post_types();
    BPSP_Bibliography::register_post_types();
    BPSP_Schedules::register_post_types();
}
add_action( 'init', 'bpsp_registration' );

/**
 * On plugins load
 */
function bpsp_on_plugins_load() {
    BPSP_Groups::activate_component();
}
add_action( 'plugins_loaded', 'bpsp_on_plugins_load', 5 );

/* Initiate the componenets */
function bpsp_init() {
    new BPSP_WordPress();
    new BPSP_Roles();
    // Load Courseware behaviour
    new BPSP_Groups();
    if( get_option( 'bpsp_curriculum' ) != 'eu' )
        new BPSP_USCourses();
    else
        new BPSP_Courses();

    new BPSP_Assignments();
    new BPSP_Responses();
    new BPSP_Gradebook();
    new BPSP_Bibliography();
    new BPSP_Schedules();
    new BPSP_Dashboards();
    new BPSP_Static();
    new BPSP_Activity();
    new BPSP_Notifications();
}
add_action( 'bp_init', 'bpsp_init', 7 );

/* Activate the components */
function bpsp_activation() {
    BPSP_Roles::register_profile_fields();
}
register_activation_hook( BPSP_PLUGIN_FILE, 'bpsp_activation' );

/**
 * _d( $arg, $die = false, $wp_die = false )
 *
 * My temporary shortcut for debugging stuff. Outputs the debugging for $arg
 * @param Mixed $arg, to be outputted
 * @param Boolean $die, if die() to be called
 * @param Boolean $wp_die, if wp_die() to be called
 */
function _d( $arg, $die = false, $wp_die = false ) {
    ob_start();
        echo '<pre>' . var_dump( $arg ) . '</pre>';
    $result = ob_get_clean();
    
    if( $die )
        die( $result );
    
    if( $wp_die )
        wp_die( $result );
    
    echo $result;
}
?>
