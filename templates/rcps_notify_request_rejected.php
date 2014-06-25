<?php
$up = $user_profile;
$pr = $project_request;
$op = $operator;

return array (
    'subject' => "AppForm Req Notify: {$up->get_username()} denied",
    'body'    => "
[This mail was automatically generated.]

An account application has been *denied* by {$op->get_username()} .

Username  : {$up->get_username()}
User name : {$up->get_forenames()} {$up->get_surnames()}

Link: http://avon.ucl.ac.uk/acct/view_project_request.php?id={$pr->get_id()}

Comments from: {$op->get_username()}
-----------------------------
{$pr->get_last_status()->get_comment()}

"
);
