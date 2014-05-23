<?php

echo "<table class='silvatable grid'><tr class='odd'>" .
    "<th>DB ID</th>" .
    "<th>User ID</th>" .
    "<th>Name</th>" .
    "<th>E-Mail Address</th>" .
    "<th>Sponsor</th>" .
    "</tr>";

foreach ($user_profiles as $one_user_profile) {
    echo "<tr class='even'>\n" .
        "\t<td>\n\t\t<a href=\"view_user.php?id=".$one_user_profile->get_id()."\">" .
        $one_user_profile->get_id() .
        "</a>\n\t</td>\n\t<td>\n\t\t" .
        $one_user_profile->get_username() .
        "\n\t</td>\n\t<td>\n\t\t" .
        $one_user_profile->get_user_forenames() ." ".
        $one_user_profile->get_user_surname() .
        "\n\t</td>\n\t<td>\n\t\t" .
        $one_user_profile->get_user_email() .
        "\n\t</td>\n\t<td>\n\t\t" .
        $one_user_profile->get_sponsor_username() .
        "\n\t</td>\n</tr>\n";
}

echo "</table>";
