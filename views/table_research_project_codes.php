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

<table id='table_research_project_codes' class='silvatable grid tablesorter'>
    <thead>
    <tr class='odd'>
        <th>DB ID</th>
        <th>Username</th>
        <th>Sponsor</th>
        <th>Project Code</th>
        <th>Time Added</th>
    </tr>
    </thead>
    <tbody>



    <?php
    //////////////////////////////////////
    //  End header HTML, begin PHP Region


    /* @var ResearchProjectCode $one_project_code */

    foreach ($project_codes as $one_project_code) {
        $user = UserProfile::from_db($one_project_code->get_user_profile_id());
        $username = $user->get_username();
        $user_profile_id = $user->get_id();
        $sponsor_username = $user->get_sponsor_username();


        echo "<tr class='even'>\n" .
            "\t<td>\n\t\t" .
            $one_project_code->get_id() .
            "</a>\n\t</td>\n\t<td>\n\t\t" .
            "<a href=\"view_user.php?id=$user_profile_id\">$username (id: $user_profile_id)</a>" .
            "\n\t</td>\n\t<td>\n\t\t" .
            $sponsor_username .
            "\n\t</td>\n\t<td>\n\t\t" .
            $one_project_code->get_code() .
            "\n\t</td>\n\t<td>\n\t\t" .
            $one_project_code->get_creation_time() .
            "\n\t</td>\n</tr>\n";
    }

    ?>

    </tbody>
</table>

<script>
    if ( $ != null ) {
        var this_table = $('#table_research_project_codes'); this_table.tablesorter();
    }
</script>