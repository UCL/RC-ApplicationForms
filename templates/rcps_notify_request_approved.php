<?php
$up = $user_profile;
$pr = $project_request;
$op = $operator;

$base_url = SiteSettings::$base_url;

return array (
    'subject' => "AppForm Req Notify: {$up->get_username()} approved",
    'body'    => "
[This mail was automatically generated.]

An account application has been approved by {$op->get_username()} .

Username  : {$up->get_username()}
User name : {$up->get_user_forenames()} {$up->get_user_surname()}

Link: {$base_url}/view_project_request.php?idp={$pr->get_id()}

Comments from: {$op->get_username()}
-----------------------------
{$pr->get_last_status()->get_comment()}

"
);
