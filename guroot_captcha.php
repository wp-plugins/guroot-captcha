<?php

/*
  Plugin Name: Guroot Captcha
  Plugin URI:
  Description: Simple Captcha 
  Version: 0.2 
  Author: Jonathan Fleury
  Author URI: http://www.guroot.com
  License: GPL2
 */

define('WP_DEBUG', true);
include __DIR__ . DIRECTORY_SEPARATOR . 'helper.php';

function GURCAPTCHA_loader($class_name) { 
    $parts = explode("_", $class_name);
    $prefix = $parts[0];
    if ($prefix === 'GURCAPTCHA')
        include __DIR__ . DIRECTORY_SEPARATOR . $class_name . '.class.php';
}

spl_autoload_register('GURCAPTCHA_loader');

/** Activation and Deactivation of the plugin **/
register_activation_hook(__FILE__, array(GURCAPTCHA_install::getInstance(), 'activate'));
register_deactivation_hook(__FILE__, array(GURCAPTCHA_install::getInstance(), 'deactivate'));

/** Bootstrap for displaying images from database **/
function bootstrapForImageDisplay(){
    global $wpdb;    
    if(isset($_GET['GURCAPTCHA_image'])){
        // Just for testing get the first image in db
        $imageClass = new GURCAPTCHA_image();
        $order = GURCAPTCHA_cookie::getInstance()->getServerSideData(GURCAPTCHA_cookie::getInstance()->getId(), 'order');           
        $imageClass->generateImage($order[intval($_GET['GURCAPTCHA_image'])]);
        $imageClass->output();
        die();
    }
}

add_action('init', 'bootstrapForImageDisplay'); 



    add_action('init',function(){
        // Init cookie manager
        $cookieMgr = GURCAPTCHA_cookie::getInstance();
    });
    add_action('wp_print_scripts', '_enqueue_jQuery');
    add_action('wp_print_scripts', '_enqueue_jQuery_UI');    


add_action('comment_form', array(GURCAPTCHA_captcha::getInstance(), 'process'));
add_filter('preprocess_comment',array(GURCAPTCHA_captcha::getInstance(),'preprocess')); 


?>