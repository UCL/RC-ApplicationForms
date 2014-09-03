<?php

$tprj_request_statistics = array();
$tprj_request_status_statistics = array();

$tprj_request_statistics['total_requests'] = 0;
$tprj_request_statistics['invalid_sponsors'] = 0;

$tprj_users = array();

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
        $tprj_request_status_statistics[$tprj_project_status] = 0;
    }

}

$tprj_num_unique_users = count(array_unique($tprj_users));

echo "<h6>{$tprj_request_statistics['total_requests']} total requests, {$tprj_request_statistics['invalid_sponsors']} with invalid sponsor information.</h6>\n";
echo "<h6>{$tprj_num_unique_users} unique users.</h6>\n";

