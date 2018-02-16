<?php

    // variables for the field and option names
    $slackHook = get_option('tech404_slack_hook');

    if( isset($_POST['tech404_slack_hook'])) {
        $slackHook = $_POST['tech404_slack_hook'];
        update_option('tech404_slack_hook', $slackHook);

?>
    <div class="updated"><p><strong>New hook value set</strong></p></div>
<?php } ?>

<div class="wrap">
    <h2>Tech404 Job Approval Notifier</h2>

    <form name="tech404_slack_hook_form" method="post" action="">
        <p><label for="tech404_slack_hook">Slack Hook
            <input type="text" name="tech404_slack_hook" value="<?php echo $slackHook ?>" />
        </p>
        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
        </p>
    </form>
</div>
