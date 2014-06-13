<?php
/**
 * Ideally this would contain any small static strings used elsewhere. 
 *
 * In practice, most of the small bits are dynamic, so there isn't much here.
 */

$strings = array(
    "submit" => array (
        "err_could_not_create_user" => "<p class='p'><em>The user details you supplied " .
             " could not be parsed correctly. Please check them and try again.</em>\n",
        "err_open_proj_req" => "<p class='p'><em>You already have an account request ".
             "in progress -- you cannot submit another. If you have".
             " realised that you made a significant mistake, or for".
             " any other problem with the process, please contact ".
             "rc-support@ucl.ac.uk.</em></p>\n",
        "err_could_not_send_mail" => "<h4>There was a problem mailing out approval requests. ".
             "Please contact rc-support@ucl.ac.uk mentioning this ".
             "problem, and pasting the following into your e-mail:".
             "</h4>\n",
        "success" => "<p class='p'>Successfully created requests. If you do not " .
            "receive any further information within 3 days, please contact " .
            "rc-support@ucl.ac.uk.</p>\n<p class='p'>If you are an existing user of one of ".
            " our services, please proceed to the page linked below to " .
            "submit a list of any publications and grants " .
            " you have worked on that have used them. This will help us justify the " .
            "continued funding of these services.</p>\n" .
            "<h3><a href=\"publications.php\">Submit Publications and Grants</a></h3>\n",
    ),
);
