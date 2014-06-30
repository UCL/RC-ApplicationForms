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

  * The Legion service is most suitable for serial work, large numbers of serial jobs, and small multinode jobs (using e.g. MPI). We recommend using Legion for any use not explicitly covered by another service.

  * The Iridis service is a general-purpose machine shared with Southampton and Oxford universities under the SES consortium. It is quite similar to Legion, but set-up to be most suitable for larger multinode jobs (using e.g. MPI). We recommend using Iridis if you intend to use more than 36 cores per job.

  * The Emerald service is intended and suitable for work using GPUs, and is shared with Southampton and Oxford under the same scheme as Iridis. Any work using GPUs is recommended to be performed on Emerald if possible.

  More information on these services is available from the Research Computing website, at: http://www.ucl.ac.uk/isd/staff/research_services/research-computing


We create accounts on the Legion service for all applying researchers regardless of work type, but due to the shared nature of the other two services, there is an additional account creation step for Iridis and Emerald. You do not need to complete this step if you do not intend to use these services.

 To complete Iridis account creation, use the following link: https://cfi.soton.ac.uk/signup/

 To complete Emerald account creation, use the following link: https://www.emerald.rl.ac.uk/

"
);
