<?php

date_default_timezone_set("UTC");

$tprj_request_statistics = array();
$tprj_request_status_statistics = array();

$tprj_request_statistics['total_requests'] = 0;
$tprj_request_statistics['invalid_sponsors'] = 0;

$tprj_request_statistics['unapproved_self_sponsored_apps'] = 0;
$tprj_request_statistics['unapproved_self_sponsored_apps_older_than_three_days'] = 0;

$tprj_users = array();

/** @var ProjectRequest $one_project_request */

foreach ($project_requests as $one_project_request) {
    $tprj_request_statistics['total_requests'] += 1;

    $sponsor_username = $one_project_request->get_user_profile()->get_sponsor_username();
    if (0 === preg_match( "/^([a-z0-9]{7}|)$/", $sponsor_username )) {
        $tprj_request_statistics['invalid_sponsors'] += 1;
    }

    array_push($tprj_users, $one_project_request->get_user_profile()->get_username());

    $tprj_project_status = $one_project_request->get_last_status()->get_text();
    if (array_key_exists($tprj_project_status, $tprj_request_status_statistics)) {
        $tprj_request_status_statistics[$tprj_project_status] += 1;
    } else {
        $tprj_request_status_statistics[$tprj_project_status] = 1;
    }

    if ($one_project_request->get_user_profile()->get_sponsor_username() == "") {
        if ($one_project_request->get_last_status()->get_text() != "submitted") {
            $tprj_request_statistics['unapproved_self_sponsored_apps'] += 1;

            if (strtotime($one_project_request->get_last_status()->get_update_time()) < time() - 60*60*24*3) {
                $tprj_request_statistics['unapproved_self_sponsored_apps_older_than_three_days'] += 1;
            }

        }
    }

}

$tprj_num_unique_users = count(array_unique($tprj_users));

echo "<h6>{$tprj_request_statistics['total_requests']} total requests, {$tprj_request_statistics['invalid_sponsors']} with invalid sponsor information.</h6>\n";
echo "<h6>{$tprj_num_unique_users} unique users.</h6>\n";
echo "<h6>{$tprj_request_statistics['unapproved_self_sponsored_apps']} unapproved self-sponsored applications, " .
     "{$tprj_request_statistics['unapproved_self_sponsored_apps_older_than_three_days']} older than three days.</h6>\n";

echo "<table class='silvatable grid'><tr class='odd'>" .
    "<th>Status</th>" .
    "<th>Number</th>" .
    "</tr>";
foreach ($tprj_request_status_statistics as $tprj_status => $tprj_status_count) {
    echo "<tr><td>{$tprj_status}</td><td>$tprj_status_count</td></tr>\n";
}
echo "</table>";

