<?php

namespace NotifyApproved;

//require_once __DIR__ . '/../vendor/autoload.php';

use Maknz\Slack\Client as SlackClient;

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
        // Nothing here for now
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

        if ($meta->meta_key === '_job_expires') {
            // @TODO give the plugin a config page for the hook, and other things
            $slackClient = new SlackClient('https://hooks.slack.com/services/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');
            $messageData = self::composeMessage($meta->post_id);
            $slackClient->to('#general')->attach($messageData)->send();
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
        $jobDescription = strlen($jobMeta['_job_description'][0]) > 200 ? substr($jobMeta['_job_description'][0], 0, 197) . '...' : $jobMeta['_job_description'][0];

        $messageData = [
            //'attachment' => [
                'pretext' => "New {$jobType} Job Posting!",
                'fallback' => "New {$jobType} Job Posting!",
                'title' => $job->post_title,
                'title_link' => get_permalink($postId),
                'text' => $jobDescription,
                'color' => '#36a64f',
                'thumb_url' => isset($jobMeta['_thumbnail_id']) ? wp_get_attachment_url($jobMeta['_thumbnail_id']) : '',
                'fields' => [
                    [
                        'title' => 'Company',
                        'value' => $jobMeta['_company_name'][0],
                        'short' => 'true'
                    ],
                    [
                        'title' => 'Location',
                        'value' => $jobMeta['_job_location'][0],
                        'short' => 'true'
                    ]
                ]
            //],
        ];

        error_log($jobMeta['_thumbnail_id']);
        //
        // error_log($jobDescription);
        // echo '<pre>';
        // var_dump($messageData);
        // die();
        return $messageData;

    }
}
