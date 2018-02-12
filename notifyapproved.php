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

require_once __DIR__ . '/src/NotifyApproved.php';

register_activation_hook(__FILE__, ['NotifyApproved\NotifyApproved', 'activate']);
