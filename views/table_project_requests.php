<?php

echo "<table class='silvatable grid'><tr class='odd'>" .
    "<th>DB ID</th>" .
    "<th>Created</th>" .
    "<th>User</th>" .
    "<th>Sponsor</th>".
    "<th>Description</th>" .
    "<th>Current Status</th>" .
    "</tr>";

/* @var ProjectRequest $one_project_request */

foreach ($project_requests as $one_project_request) {
    $work_desc_abbrev = $one_project_request->get_work_description();
    if (strlen($work_desc_abbrev) > 80) {
        $work_desc_abbrev = substr($work_desc_abbrev,0,77) . "...";
    }

    $sponsor_username = $one_project_request->get_user_profile()->get_sponsor_username();
    if (0 === preg_match( "/^[a-z0-9]{7}$/", $sponsor_username )) {
        $sponsor_username = "<span style='color:red;'>{$sponsor_username}</span>";
    } else {
        $sponsor_username = "<span>{$sponsor_username}</span>";
    }


    echo "<tr class='even'>\n" .
        "\t<td>\n\t\t<a href=\"view_project_request.php?id=".$one_project_request->get_id()."\">" .
        $one_project_request->get_id() .
        "</a>\n\t</td>\n\t<td>\n\t\t" .
        $one_project_request->get_creation_time() .
        "\n\t</td>\n\t<td>\n\t\t" .
        $one_project_request->get_user_profile()->get_username() .
        "\n\t</td>\n\t<td>\n\t\t" .
        $sponsor_username .
        "\n\t</td>\n\t<td>\n\t\t" .
        $work_desc_abbrev .
        "\n\t</td>\n\t<td>\n\t\t" .
        $one_project_request->get_last_status()->get_text() .
        " (".$one_project_request->get_last_status()->get_update_time() .")" .
        "\n\t</td>\n</tr>\n";
}

echo "</table>";
