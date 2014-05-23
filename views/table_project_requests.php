<?php

echo "<table class='silvatable grid'><tr class='odd'>" .
    "<th>DB ID</th>" .
    "<th>Created</th>" .
    "<th>Description</th>" .
    "<th>Current Status</th>" .
    "</tr>";

foreach ($project_requests as $one_project_request) {
    $work_desc_abbrev = $one_project_request->get_work_description();
    if (strlen($work_desc_abbrev) > 80) {
        $work_desc_abbrev = substr($work_desc_abbrev,0,77) . "...";
    }
    echo "<tr class='even'>\n" .
        "\t<td>\n\t\t<a href=\"view_project_request.php?id=".$one_project_request->get_id()."\">" .
        $one_project_request->get_id() .
        "</a>\n\t</td>\n\t<td>\n\t\t" .
        $one_project_request->get_creation_time() .
        "\n\t</td>\n\t<td>\n\t\t" .
        $work_desc_abbrev .
        "\n\t</td>\n\t<td>\n\t\t" .
        $one_project_request->get_last_status()->get_text() .
        " (".$one_project_request->get_last_status()->get_update_time() .")" .
        "\n\t</td>\n</tr>\n";
}

echo "</table>";
