
<p class='p'>
    Click to widen view to:
  <button type="button" onclick="$('#isd-container').css('width','100%')">100%</button>
  <button type="button" onclick="$('#isd-container').css('width','150%')">150%</button>
  <button type="button" onclick="$('#isd-container').css('width','200%')">200%</button>
</p>

<?php // Table sorter from: http://tablesorter.com/ ?>

<style>
       th.header { 
         background-image: url('resources/U+2195.svg'); 
         background-size: 0.7em; 
         background-repeat: no-repeat; 
         background-position: right top; 
         padding-right: 0.5em;
       } 
       th.headerSortUp { 
         background-image: url('resources/U+2191.svg');
       } 
       th.headerSortDown { 
         background-image: url('resources/U+2193.svg'); 
       }
</style>

<table id='table_project_requests' class='silvatable grid tablesorter'>
    <thead>
        <tr class='odd'>
            <th>DB ID</th> 
            <th>Created</th>
            <th>User</th>
            <th>Sponsor</th>
            <th>Description</th>
            <th>REF Theme</th>
            <th class="{sorter: 'text'}">Current Status</th>
            <?php // If you don't make the sorter text, it tries to do something cunning with the dates and fails, I think. ?>
        </tr>
    </thead>
    <tbody>



<?php
//////////////////////////////////////
//  End header HTML, begin PHP Region


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

?>

    </tbody>
</table>

<script>
    if ( $ != null ) { 
        var this_table = $('#table_project_requests'); this_table.tablesorter(); 
    } 
</script>


