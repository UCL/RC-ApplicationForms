<?php

$sponsor_username = $project_request->get_user_profile()->get_sponsor_username();
if ($sponsor_username == "") { $sponsor_username = "(none given)"; }

echo "<table class='silvatable grid'><tr class='odd'><td colspan=2><strong>User</strong></td></tr>"
    . table_keyval("User id",
        $project_request->get_user_profile()->get_username() .
        " (<a href=\"view_user.php?id=".$project_request->get_user_profile()->get_id()."\">".
        "id: ".$project_request->get_user_profile()->get_id().
        "</a>)"
        , 2, FALSE, FALSE) // Don't escape HTML characters
    . table_keyval("User Name",
        $project_request->get_user_profile()->get_user_forenames()." ".
        $project_request->get_user_profile()->get_user_surname())
    . table_keyval("User e-mail",
        $project_request->get_user_profile()->get_user_email())
    . table_keyval("Sponsor Username",
        $sponsor_username)

    . "</table><table class='silvatable grid'><tr class='odd'><td colspan=2><strong>Project</strong></td></tr>"
    . table_keyval("PI",
        $project_request->get_pi_email())
    . table_keyval("Research Theme",
        $project_request->get_research_theme())
    . table_keyval("Project Description",
        $project_request->get_work_description(), 1, TRUE)
    . table_keyval("Applications Required",
        $project_request->get_applications_description(), 1, TRUE)
    . table_keyval("Work Required",
        $project_request->get_formatted_work_required(), 1, TRUE)
    . table_keyval("Unusual Technical Requirements",
        $project_request->get_weird_tech_description(), 1, TRUE)
    . table_keyval("Collaboration (if any)",
        $project_request->get_formatted_collaboration(), 1, TRUE)
    . "</table>"
;
