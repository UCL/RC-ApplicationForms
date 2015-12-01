<?php
$up = $user_profile;
$pr = $project_request;
$op = $operator;

$recommendation = $pr->get_recommended_services();
if ($recommendation == "") {
    $recommendation = "Unfortunately, you did not provide any information about your workload we could use, so you will be granted a Legion account. To discuss more specialised requirements or if you're not sure what you need, please contact rc-support@ucl.ac.uk .";
} else {
    $recommendation = "This has recommended: $recommendation";
}

return array (
    'subject' => "RCPS: Request Approved",
    'body'    => "
Your request for Research Computing resources has been approved.

We have a number of services available for users, and the approval process automatically recommends services based on the list of work types on the application form.

$recommendation

Services
--------

  * The Legion service is most suitable for serial work, large numbers of serial jobs, and small multi-node jobs (using e.g. MPI). We recommend using Legion for any use not explicitly covered by another service.

  * The Grace service is intended for multi-node parallel jobs, typically with core counts >= 32. Jobs smaller than this are not permitted access to the majority of the cluster nodes.
  
  * The Emerald service is intended and suitable for work using GPUs, and is shared with and operated by the STFC. Any work using GPUs is recommended to be performed on Emerald if possible.


  More information on these services is available from the Research Computing website, at: http://wiki.rc.ucl.ac.uk/


  We create accounts on the Legion service for all applying researchers regardless of work type, but access to Grace will be granted for users whose jobs require it. If you have been recommended Grace above, you will be granted access, but if you require it later, send a mail to rc-support@ucl.ac.uk to request it.
  
  Because Emerald is operated by the STFC, there is an additional account creation step for users intending to use it. You do not need to complete this step if you do not intend to use Emerald. (And can perform it later if this changes.)

 Note that if you already have an account on Emerald, you _shouldn't_ perform this step -- it is _only_ for the creation of new accounts.

 To complete Emerald account creation, use the following link: https://cfi.soton.ac.uk/signup/



"
);
