<?php

namespace NotifyApproved;

use SlackNotifications\Slack_Bot as Slackbot;

class NotifyApproved
{

    /**
     * Called by the plugin activation hook.
     * Checks that all the prerequisites for running
     * the plugin are met.
     *
     * @method activate
     * @return bool   true when requirements met, false otherwise
     */

    public function activate()
    {
        if (!is_plugin_active('dorzki-notifications-to-slack/slack-notifications.php')) {
            die('Slack Notifications by dorzki not installed');
        }

        return true;
    }

    /**
     * Verifies this is a job approval, and sends the proper notification.
     * @method notify
     * @param  integer $postId provided by the save_post_job hook
     */


    public function notify($metaId)
    {
        global $wpdb;
        $meta = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_id = {$metaId}");
        $slackBot = new slackBot;
        if ($meta->meta_key === '_job_expires') {
            $messageData = self::composeMessage($meta->post_id);
            $slackBot->send_message($messageData['message'], $messageData['attachments'], $messageData['args']);
        }
    }

    /**
     * Put together the message in proper Slack format
     * @method composeMessage
     * @param  integer        $postId approved job ID
     * @return array          message data to be converted by Slack Notifications plugin
     */


    protected function composeMessage($postId)
    {

        $job = get_post($postId);
        $jobMeta = get_post_meta($postId);
        $jobType = wp_get_post_terms($postId, 'job_listing_type')[0]->name;

        $messageData = [
            'message' => "New {$jobType} Job Posting!",
            'attachments' => [
                [
                    'title' => 'Job Posting',
                    'value' => '<' . get_permalink($postId) . '|' . $job->post_title . '>',
                    'short' => 'true'
                ],
                [
                    'title' => 'Company',
                    'value' => $jobMeta['_company_name'][0],
                    'short' => 'true'
                ],
                [
                    'title' => 'Location',
                    'value' => $jobMeta['_job_location'][0],
                    'short' => 'true'
                ],
                [
                    'title' => 'Description',
                    'value' => substr($jobMeta['_job_description'][0], 0, 200),
                    'short' => "false"
                ]
            ],
            'args' => [
                'color'      => '#36a64f',
                'title'      => $job->post_title,
                'thumb_url'  => isset($jobMeta['_thumbnail_id']) ? wp_get_attachment_url($jobMeta['_thumbnail_id']) : '',
            ]
        ];

        return $messageData;

    }
}
