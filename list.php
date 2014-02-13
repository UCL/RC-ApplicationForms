<?php

include "header.php";

$current_user = $_SERVER['PHP_AUTH_USER'];

try{
  $actor = new sql_actor();


  // Determine whether we're trying to approve something, view something, or list the things
  if ( ($action == "approve") or ( $action == "view") ) {
    $can_approve = $actor->is_approvable_by($object_id, $current_user);
    if ( ($can_approve == FALSE) or 
         ($actor->get_request_owner($object_id) != $current_user) ) 
    {
      echo "<h1> You are not authorised to perform this action. </h1>\n";
      echo "<h3> If you believe you have received this message in error, please contact rc-support. </h3>\n";
    } elseif ($action == "approve") {
      $actor->approve($object_id, $current_user);
    } elseif ($action == "view") {
      print_full_request($actor->get_request($object_id, $current_user));
      if ($can_approve == TRUE) {
        print_approve_link($object_id);
      }
    }
  } else { // Listing is default action
    $requests_with_actions = $actor->get_requests_with_actions($current_user);
    if (strlen($requests_with_actions) != 0) {
      tabulate_requests($requests_with_actions);
    } else {
      echo "<h5> No requests available to view. </h5>\n";
    }
  }

  // Send emails to consortium leaders and record that we've done so
  //bool mail ( string $to , string $subject , string $message [, string $additional_headers [, string $additional_parameters ]] );
  // Returns TRUE is the mail was successfully accepted for delivery, FALSE otherwise.
}
catch(\PDOException $ex){
  print("\n<p>" . $ex->getMessage() . "</p>\n");
}

include "footer.php";

?>
