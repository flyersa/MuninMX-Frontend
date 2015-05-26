<?php

/* CsrfProtect

This class adds CSRF protection to existing PHP applications that make use
of PHP's session support for authentication. If you are writing an
application from scratch you shouldn't use this; it's designed to protect
legacy apps that were not built with CSRF in mind.

To use, add the following somewhere near the top of your PHP application:

$csrf_protect = new CsrfProtect();
$csrf_protect->enable();

If you want to customise its behaviour in some way (for example a different
CSRF error message) you can subclass CsrfProtect before using it:

class MyCsrfProtect extends CsrfProtect {
    function error($msg) {
        die('My custom CSRF error page goes here');
    }
}
$csrf_protect = new MyCsrfProtect();
$csrf_protect->enable();

*/

class CsrfProtect {
   
    var $csrf_form_field = '_csrf_protect_token';
   
    function generate_token() {
        return sha1(session_id() . ':csrf');
    }
   
    function error($msg) {
        // This should probably be logged
        die('Your form session has expired. Please hit BACK and try again.');
        // $msg is not HTML escaped, so be careful if you decide to display it
    }
   
    function should_protect() {
        // Should CSRF protection apply to this request?
        # Only protect if the user has a session
       $session_id = session_id();
        if (empty($session_id)) {
            return false;
        }
        # If the request came in via Ajax, don't run CSRF protection
       if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && trim(
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])
            ) == 'xmlhttprequest') {
            return false;
        }
        return true;
    }
   
    function enable() {
        if (!$this->should_protect()) {
            return;
        }
        // CSRF token is derived from session ID:
        $csrf_token = $this->generate_token();
        // If it's a POST, check the token matches
        if (!empty($_POST)) {
            if (empty($_POST[$this->csrf_form_field])) {
                $this->error("No {$this->csrf_form_field} in POST");
                return;
            }
            $form_token = $_POST[$this->csrf_form_field];
            if ($form_token != $csrf_token) {
                $this->error("$form_token != $csrf_token");
                return;
            }
        }
        // On POST or GET, we still need to add the token to any forms
        ob_start(array($this, 'ob_callback'));
    }
   
    function should_rewrite_forms($html) {
        // We only rewrite pages served with an HTML content type
        $sent_headers = headers_list();
        foreach ($sent_headers as $header) {
            // Search for a content-type header that is NOT HTML
            // Note the Content-Type header will not be included in
            // headers_list() unless it has been explicitly set from PHP.
            if (preg_match('/^Content-Type:/i', $header) &&
                strpos($header, 'text/html') === false) {
                return false;
            }
        }
        return true;
    }
   
    function is_xhtml($html) {
        // Check for XHTML doctype
        return strpos(
            $html, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML'
        ) !== false;
    }
   
    function ob_callback($html) {
        // Rewrites any forms on the page to include the CSRF token
        if (!$this->should_rewrite_forms($html)) {
            return $html;
        }
       
        $token = $this->generate_token();
       
        $hidden = '<div style="display: none;">';
        $hidden .= '<input type="hidden" name="';
        $hidden .= $this->csrf_form_field . '" value="' . $token;
        $hidden .= '"' . ($this->is_xhtml($html) ? ' />' : '>');
        $hidden .= '</div>';
       
        // Find only POST forms
        return preg_replace(
            '/(<form\W[^>]*\bmethod=(\'|"|)POST(\'|"|)\b[^>]*>)/i',
            '\\1'.$hidden,
            $html
        );
    }
}

?>