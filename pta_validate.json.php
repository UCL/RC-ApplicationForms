<?php
PutEnv("TNS_ADMIN=/usr/lib/oracle/11.2/client64/network/admin");
PutEnv("ORACLE_HOME=/usr/lib/oracle/11.2/client64/network/admin");
header('Content-Type: application/json');

function connect_to_db() {
    // Enter your credentials here.
    $db_username = "UCL_RCPS";
    $db_password = "RCAW9637phnfDV";
    $tns = "EBSDEV";

    $c = oci_pconnect($db_username, $db_password, $tns);
    if (!$c) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    return $c;
}

// Searches recursively through an array, and stops if there are any values containing
//  non-numeric characters.
function detect_nonnumerics($elem)
{
    if(!is_array($elem)) {
        if ( ( preg_match("/[^0-9]/", $elem) == 1 ) ||
             ( $elem == "" ) )
        {
            die("{ \n  result: \"failure\", \n  error: \"Invalid parameters provided in URL.\" \n}\n");
        }
    } else {
        foreach ($elem as $key => $value)
            $elem[$key] = detect_nonnumerics($value);
    }
    return $elem;
}

// Goes through all the tested things to add a quick checkable "was everything I asked about good" flag.
function summarize_results($results) {
    $all_valid  = "true";
    $all_active = "true";
    foreach($results as $key => $val) {
        if ( (array_key_exists("active", $val) == FALSE) || ($val["active"] == "false")) {
             $all_active = "false";
        }
        if ( (array_key_exists("valid", $val) == FALSE) || ($val["valid"] == "false")) {
             $all_valid = "false";
        }
    }
    $results["all"]["valid"] = $all_valid;
    $results["all"]["active"] = $all_active;
    return $results;
}


// Calls DB functions to check whether a thing of type $type is valid and active.
function compacted_validate($dbc, $type, $arguments) {
    $calling_system = 'NULL';
    $argument_string = "";
    foreach($arguments as $key => $val) {
        $argument_string .= ":$key, ";
    }
    // Note: to test checks for activity easily, you can replace "SYSDATE" below with TO_DATE('3000', 'YYYY')
    $s = oci_parse($dbc,
                   "begin " .
                   ":is_valid :=  ucl_ptae_validation_pkg.ucl_validate_$type($argument_string $calling_system, :valid_error);" .
                   ":is_active := ucl_ptae_validation_pkg.ucl_active_$type($argument_string SYSDATE, $calling_system, :active_error);" .
                   "end;"
                  );
    foreach($arguments as $key => $val) {
        oci_bind_by_name($s, ":$key", $arguments[$key]);
    }
    oci_bind_by_name($s, ':valid_error', $valid_error, 200);
    oci_bind_by_name($s, ':active_error', $active_error, 200);
    oci_bind_by_name($s, ':is_valid', $is_valid, 200);
    oci_bind_by_name($s, ':is_active', $is_active, 200);

    oci_execute($s);

    $results = array();
    if ($is_valid == "Y") {
        $results["valid"] = "true";
        if ($is_active == "Y") {
            $results["active"] = "true";
        } else {
            $results["active"] = "false";
            $results["error"] = $active_error;
        }
    } else {
        $results["valid"] = "false";
        $results["error"] = $valid_error;
    }
    return $results;
}

// Done setup.


// Read in parameters and check for invalid input.
$clean_get = detect_nonnumerics($_GET); 

if ( count($_GET) == 0 ) {
    die("{ \n  result: \"failure\", \n  error: \"No parameters provided in URL. See docs.\" \n}\n");
}

$valid_parameters = array("project", "award", "task");
$valid_params_flipped = array_flip($valid_parameters);
if (count(array_merge($valid_params_flipped, $clean_get)) > count($valid_params_flipped)) {
    // Then parameters other than the ones above were passed.
    die("{ \n  result: \"failure\", \n  error: \"Invalid parameters provided in URL.\" \n}\n");
}

// By now we know we have valid parameters, *unless* we don't have the data we need for a step:
//  task number validation requires a project number
//  award number validation requires a project number and a task number

$p = array_key_exists("project", $clean_get);
$a = array_key_exists("award", $clean_get);
$t = array_key_exists("task", $clean_get);

if ($a && ( (!$p) || (!$t) ) ) {
    die("{ \n  result: \"failure\", \n  error: \"Cannot validate award number without project number *and* task number.\"\n}\n");
} 

if ($t && (!$p)) {
    die("{ \n  result: \"failure\", \n  error: \"Cannot validate task number without project number.\"\n}\n");
}

// Since we now know for sure whether we have to validate something, we can connect to the database.
$c = connect_to_db();

if ($p) { $project_number = $clean_get["project"]; }
if ($t) { $task_number    = $clean_get["task"];    }
if ($a) { $award_number   = $clean_get["award"];   }

$results = array();

if ($p) { $results["project"] = compacted_validate($c, "project", array("project_number" => "$project_number")); }
if ($t) { $results["task"]    = compacted_validate($c, "task",    array("project_number" => "$project_number", "task_number" => "$task_number")); }
if ($a) { $results["award"]   = compacted_validate($c, "award",   array("project_number" => "$project_number", "task_number" => "$task_number", "award_number" => "$award_number")); }

$results = summarize_results($results);

echo json_encode($results);

oci_close($c);


