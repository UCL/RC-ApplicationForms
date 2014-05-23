<?php

echo "<table class='silvatable grid'><tr class='odd'><th>Field</th><th>Entry</th></tr>"
    . table_keyval("Database id",
        $user_profile->get_id())
    . table_keyval("User id",
        $user_profile->get_username())
    . table_keyval("User Name",
        $user_profile->get_user_forenames()." ".
        $user_profile->get_user_surname())
    . table_keyval("User e-mail",
        $user_profile->get_user_email())
    . table_keyval("Sponsor Username",
        $user_profile->get_sponsor_username())
    . table_keyval("User UPI",
        $user_profile->get_user_upi())
    . table_keyval("User Dept",
        $user_profile->get_user_dept())
    . table_keyval("Experience Level",
        $user_profile->get_experience_level())
    . table_keyval("Relevant Experience",
        $user_profile->get_experience_text())
    . table_keyval("Profile Created",
        $user_profile->get_creation_time())
    . "</table>"
;
