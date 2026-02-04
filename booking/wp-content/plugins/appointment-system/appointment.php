<?php 
error_reporting(0);
/*
* Plugin Name: Appointment System
* Plugin URI: http://www.thinkbizsolutions.com
* Description: Appointment System
* Version: 1.0.0
* Author:Umakant Yadav
* Author URI: http://www.thinkbizsolutions.com
* License: GPL2
* Text Domain: Appointment System
* Domain Path: /languages
*/
//Define Constants
if ( !defined('AP_PLUGIN_DIR')) {
   define('AP_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
}
if ( !defined('AP_PLUGIN_DIR_PATH')) {
   define('AP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ));
}
if ( !defined('AP_PLUGIN_VERSION')) {
   define('AP_PLUGIN_VERSION', '1.0.1');
}
//echo $_SERVER['HTTP_REFERER'];
//die;

function ap_enqueue_assets() {
   $version = AP_PLUGIN_VERSION;

   wp_enqueue_style('ap-bootstrap', AP_PLUGIN_DIR . 'assets/css/bootstrap.min.css', array(), $version);
   wp_enqueue_style('jquery-ui.css', AP_PLUGIN_DIR . 'assets/css/jquery-ui.css', array(), $version);
   wp_enqueue_style('gijgo.min.css', AP_PLUGIN_DIR . 'assets/css/gijgo.min.css', array(), $version);
   wp_enqueue_style('jquery.dataTables.min.css', AP_PLUGIN_DIR . 'assets/css/jquery.dataTables.min.css', array(), $version);
   wp_enqueue_style('ap-css', AP_PLUGIN_DIR . 'assets/css/ap-style.css', array(), $version);

   wp_enqueue_script('jquery');
   wp_enqueue_script('popper.min.js', AP_PLUGIN_DIR . 'assets/js/popper.min.js', array('jquery'), $version, true);
   wp_enqueue_script('bootstrap.min.js', AP_PLUGIN_DIR . 'assets/js/bootstrap.min.js', array('jquery', 'popper.min.js'), $version, true);
   wp_enqueue_script('gijgo.min.js', AP_PLUGIN_DIR . 'assets/js/gijgo.min.js', array('jquery'), $version, true);
   wp_enqueue_script('jquery-ui.js', AP_PLUGIN_DIR . 'assets/js/jquery-ui.js', array('jquery'), $version, true);
   wp_enqueue_script('jquery.dataTables.min.js', AP_PLUGIN_DIR . 'assets/js/jquery.dataTables.min.js', array('jquery'), $version, true);
   wp_enqueue_script('jquery.sweetalert.min.js', AP_PLUGIN_DIR . 'assets/js/sweetalert.min.js', array('jquery'), $version, true);
}

function ap_should_enqueue_frontend_assets() {
   if (is_admin()) {
      return false;
   }

   $post = get_post();
   if (!$post) {
      return false;
   }

   $shortcodes = array('new_appointment', 'new_appointment_payment', 'appointment_payment_sucess');
   foreach ($shortcodes as $shortcode) {
      if (has_shortcode($post->post_content, $shortcode)) {
         return true;
      }
   }

   return false;
}

function ap_enqueue_frontend_assets() {
   if (ap_should_enqueue_frontend_assets()) {
      ap_enqueue_assets();
   }
}
add_action('wp_enqueue_scripts', 'ap_enqueue_frontend_assets');

function ap_enqueue_admin_assets($hook) {
   $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';
   $allowed_pages = array(
      'enviro-appointment-system',
      'appointment-system-location',
      'appointment-system-services',
      'appointment-system-connection',
      'appointment-system-connection-price',
      'appointment-system-engineer',
      'appointment-system-addnew',
      'ap-view-appointment',
      'ap-view-appointment-location',
      'ap-pay-appointment',
   );

   if ($page && in_array($page, $allowed_pages, true)) {
      ap_enqueue_assets();
   }
}
add_action('admin_enqueue_scripts', 'ap_enqueue_admin_assets');
register_activation_hook( __FILE__, 'crudOperationsTable');
require AP_PLUGIN_DIR_PATH. 'ap-db.php';
require AP_PLUGIN_DIR_PATH. 'ap-header.php';
require AP_PLUGIN_DIR_PATH. 'ap-menu.php';
require AP_PLUGIN_DIR_PATH. 'ap-location.php';
require AP_PLUGIN_DIR_PATH. 'ap-services.php';
require AP_PLUGIN_DIR_PATH. 'ap-connection-price.php'; 
require AP_PLUGIN_DIR_PATH. 'ap-connection.php'; 
require AP_PLUGIN_DIR_PATH. 'ap-appointment.php';
require AP_PLUGIN_DIR_PATH. 'ap-engineer.php';
require AP_PLUGIN_DIR_PATH. 'ap_add_appointment.php';
require AP_PLUGIN_DIR_PATH. 'view_appointment.php';
require AP_PLUGIN_DIR_PATH. 'view_appointment_location.php';
require AP_PLUGIN_DIR_PATH. 'ap_admin_add_appointment.php';
require AP_PLUGIN_DIR_PATH. 'wps_theme_func_pay_appointment.php';
require AP_PLUGIN_DIR_PATH. 'success.php';
function ap_appointment(){
   wps_theme_func_addnew();
}
add_shortcode('new_appointment','ap_appointment');
function ap_appointment_pay(){
   wps_theme_func_pay_appointment();
}
add_shortcode('new_appointment_payment','ap_appointment_pay');
function ap_appointment_pay_sucess(){
   wps_theme_func_pay_sucess();
}
add_shortcode('appointment_payment_sucess','ap_appointment_pay_sucess');
