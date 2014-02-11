<html>
<body>
<pre>

<?php

$referrer_file_name = array_pop(explode("/", $_SERVER["HTTP_REFERER"])); 

$valid_referrers = array("apply.php", "renew.php");

if ( (! in_array($referrer_file_name, $valid_referrers, TRUE)) or
     ( $_SERVER['REQUEST_METHOD'] != "POST") )
{
  exit ("It is not permitted to use this page in this way.\n");
}

echo "referrer_file_name = $referrer_file_name\n";

$arr = get_defined_vars();
print_r($arr["_SERVER"]);
print_r($arr["_REQUEST"]);
print_r($arr["_POST"]);

print_r(array_keys(get_defined_vars()));

?>
</pre>
</body>
</html>
