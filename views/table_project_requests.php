<?php

echo "<p class='p'>".
    "  <a href=\"javascript:void( $('#isd-container').css('width','100%') );\">".
    "    (click to widen view to 100%)".
    "  </a>".
    "  <a href=\"javascript:void( $('#isd-container').css('width','150%') );\">".
    "    (150%)".
    "  </a>".
    "  <a href=\"javascript:void( $('#isd-container').css('width','200%') );\">".
    "    (200%)".
    "  </a>".
    "</p>\n\n";

// Table sorter from: http://tablesorter.com/

echo "<style>\n".
     "  th.header { \n".
     "    background-image: url('resources/U+2195.svg'); \n".
     "    background-size: 0.7em; \n".
     "    background-repeat: no-repeat; \n".
     "    background-position: right top; \n".
     "    padding-right: 0.5em; \n".
     "  } \n".
     "  th.headerSortUp { \n".
     "    background-image: url('resources/U+2191.svg'); \n".
     "  } \n".
     "  th.headerSortDown { \n".
     "    background-image: url('resources/U+2193.svg'); \n".
     "  } \n".
     "</style>\n\n";

echo "<table id='table_project_requests' class='silvatable grid tablesorter'><thead><tr class='odd'>" .
    "<th>DB ID</th>" .
    "<th>Created</th>" .
    "<th>User</th>" .
    "<th>Sponsor</th>".
    "<th>Description</th>".
    "<th>REF Theme</th>".
    "<th class=\"{sorter: 'text'}\">Current Status</th>" . //If you don't make the sorter text, it tries to do something cunning with the dates and fails, I think.
    "</tr></thead><tbody>";

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
        $one_project_request->get_research_theme() .
        "\n\t</td>\n\t<td>\n\t\t" .
        $one_project_request->get_last_status()->get_text() .
        " (".$one_project_request->get_last_status()->get_update_time() .")" .
        "\n\t</td>\n</tr>\n";
}

echo "</tbody></table>";

echo "\n\n";
echo "<script>\n  if ( $ != null ) { var this_table = $('#table_project_requests'); this_table.tablesorter(); } \n</script>\n";


