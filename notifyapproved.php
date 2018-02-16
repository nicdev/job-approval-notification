<?php

/*
 * Plugin Name: Notify Approved
 * Plugin URI: https://tech404.io
 * Description: Piggy back off of dorzki notifications plugin to send an alert when a job is approved.
 * Author: Nic Rosental
 * Version: 0.1
 * Author URI: https://github.com/nicdev
 * License: GPL2+
 */


/**
 * No direct access
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

// Hook into the job approval meta update
add_action('added_post_meta', ['NotifyApproved\NotifyApproved', 'notify']);

// Add a submenu for settings
add_action('init', ['NotifyApproved\NotifyApproved', 'hookMenu']);
