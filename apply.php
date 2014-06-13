<?php
    include_once "includes/auth_user_shim.php";
    include_once "includes/autoload_definition.php";
    include_once "includes/config.php";

    $page_title = "Request User Account";
    include_once "includes/header.php";

?>

<script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js' type='text/javascript'>\<\/script>");
</script>


<!-- Begin form -->
<?php
    $actor = new SQLActor();
    $actor->connect();
    /* Sets up options for drop-down lists. */
    $user_type_options        = $actor->options_from_table("User_Types", "user_type");
    $experience_level_options = $actor->options_from_table("Experience_Levels", "level_text");
    $research_themes          = $actor->options_from_table("Research_Themes", "full_name");

    include "forms/form_apply.php";


    include "includes/footer.php";
?>
