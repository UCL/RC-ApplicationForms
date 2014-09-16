<?php

$sponsor_username = $project_request->get_user_profile()->get_sponsor_username();
if ($sponsor_username == "") { $sponsor_username = "(none given)"; }

$work_description         = $project_request->get_work_description();
$work_required            = $project_request->get_formatted_work_required();
$applications_description = $project_request->get_applications_description();
$tech_description         = $project_request->get_weird_tech_description();
$collaborations           = $project_request->get_formatted_collaborations("", ": ");

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
        $work_description, 1)
    . table_keyval("Applications Required",
        $applications_description, 1)
    . table_keyval("Work Required",
        $work_required, 1)
    . table_keyval("Unusual Technical Requirements",
        $tech_description, 1)
    . table_keyval("Collaboration (if any)",
        $collaborations, 1)
    . "</table>"
;
