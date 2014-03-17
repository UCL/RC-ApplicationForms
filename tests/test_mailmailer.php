<?php

include "mailmailer.php";
$mailer = new MailMailer();

$test_info = array(
    'created_id'=>1000,
    'consortium_name'=>'Logology',
    'username'=>"uccaiki",
    'user_forenames'=>"Ian Jane",
    'user_surname'=>"Kirker",
    'user_email'=>"a.n.other@ucl.ac.uk",
    'user_dept'=>"Chemistry",
    'supervisor_name'=>"Dr A Person",
    'supervisor_email'=>"a.person@ucl.ac.uk",
    'project' => array(
        'pi_email' =>"pi@ucl.ac.uk",
        'work_description'=>"Doing some work stuff.",
        'applications_description'=>"Gaussian 50000",
        'weird_tech_description'=>"5000 processing hats",
        'work_required_collated'=>"Some jobs",
        'collaboration_collated'=>"Some guys, some places"
    )
);

$mailer->send_mail('new_account_for_approval', array('i.kirker+providedemail@ucl.ac.uk'), $test_info);

?>
