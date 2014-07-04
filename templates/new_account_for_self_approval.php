<?php
$up = $user_profile;
$pr = $project_request;
$op = $operator;

$base_url = SiteSettings::$base_url;

return array (
    'subject' => "RCPS: New Account Request - {$up->get_user_forenames()} {$up->get_user_surname()}",
    'body'    =>
        "
[This mail was automatically generated.]

You have been mailed to confirm your application for an account using Research Computing resources.

Please check the information below is correct, and visit the page below to confirm or cancel the request.

  Confirm/Cancel Request: {$base_url}/view_project_request.php?id={$pr->get_id()}

  Name:  {$up->get_user_forenames()} {$up->get_user_surname()}
  Email: {$up->get_user_email()}
  Dept:  {$up->get_user_dept()}

  Project Information
  ===================

  PI: {$pr->get_pi_email()}

  Research Theme: {$pr->get_research_theme()}

-- Project Description
  {$pr->get_work_description()}

-- Applications Required
  {$pr->get_applications_description()}

-- Types of Work Required
  {$pr->get_formatted_work_required()}

-- Unusual Technical Requirements
  {$pr->get_weird_tech_description()}

-- Collaboration
  {$pr->get_formatted_collaborations()}

"
);
