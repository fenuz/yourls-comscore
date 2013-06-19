<?php
/*
Plugin Name: ComScore 
Plugin URI: http://www.kennisnet.nl
Description: Log Shorturl redirects in ComScore
Version: 0.1
Author: Frank Matheron <frankmatheron@gmail.com>
Author URI: https://github.com/fenuz
*/

if (defined('COMSCORE_URL')) {

    if (!defined('COMSCORE_COOKIE_FILE')) {
        define('COMSCORE_COOKIE_FILE', __DIR__.'/../../comscore.cookies');
    }
    
    // Attempt to create a file to store the cookies in.
    if (!file_exists(COMSCORE_COOKIE_FILE)) {
        if (touch(COMSCORE_COOKIE_FILE, time())){
            if (!chmod(COMSCORE_COOKIE_FILE, 0660)) {
                error_log('Could not set read/write permissions to ComScore cookie file: ' . COMSCORE_COOKIE_FILE);
            }            
        } else {
            error_log('Could not create ComScore cookie file: ' . COMSCORE_COOKIE_FILE);
        }
    }
    
    /**
     * Action for 'redirect_shorturl' that requests a comScore tracking URL.
     * 
     * @param type $args
     */
    function _comscore_log_redirect($args) {
        $url = sprintf(COMSCORE_URL, urlencode($args[1]), time());
        
        $ch = curl_init($url);        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // ComScore uses redirects to track clients with cookies disabled, or 
        // for first time visits (when you do not have cookies yet).
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        // We enable cookies. When we have a cookie stored ComScore no longer 
        // redirects us, saving us a request.
        if (is_writable(COMSCORE_COOKIE_FILE)) {
            // We pretend that we never close our browser
            curl_setopt($ch, CURLOPT_COOKIESESSION, false);
            // Store the cookies in COMSCORE_COOKIE_FILE
            curl_setopt($ch, CURLOPT_COOKIEFILE, COMSCORE_COOKIE_FILE);
            curl_setopt($ch, CURLOPT_COOKIEJAR, COMSCORE_COOKIE_FILE);
        } else {
            error_log('Cannot write to ComScore cookie jar: ' . COMSCORE_COOKIE_FILE);
        }
        
        // Prevent requests taking too long
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        
        if(curl_exec($ch) === false) {
            error_log('cURL error on ComScore request: ' . curl_error($ch));
        }
        curl_close($ch);
    }
    yourls_add_action('redirect_shorturl', '_comscore_log_redirect');

}