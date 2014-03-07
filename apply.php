<?php
    include "auth_user_shim.php";

    $page_title = "Request User Account";
    include "header.php";
?>
<script type="text/javascript">
    document.write("\<script src='//ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js' type='text/javascript'>\<\/script>");
</script>
<!-- Begin form -->
<?php
    include "sqlactor.php";
    $actor = new SQLActor();
    $actor->connect();
    $user_type_options        = $actor->options_from_table("User_Types", "user_type");
    $experience_level_options = $actor->options_from_table("Experience_Levels", "level_text");
    $consortium_options       = $actor->options_from_table("Consortia", "full_name");
?>

<form id="application_form" action="submit.php" method="post" enctype="multipart/form-data" id="application_form">
    <div class="section">
        <h3 class="sectionTitle">
            User Information
        </h3>
        <input type="text" value="Apply" name="submit_type" style="display:none" readonly/>
        <table> <!-- Shouldn't really use tables for layout, but I'm just copy/pasting at the moment. -->
            <tr>
                <td> <label for="username">UCL userid:</label></td> <!-- label mostly to make PHPStorm not whine at me -->
                <td>
                    <input 
                        type="text"
                        id="username"
                        name="username" 
                        title="Your existing UCL userid." 
                        readonly 
                        value="<?php echo "$current_username" ?>"
                        pattern="[A-Za-z0-9]{7}"
                    />
                </td>
                <td>
                    UPI:
                </td>
                <td> <!-- See if you can gen this. -->
                    <input 
                        type="text"
                        name="user_upi"
                        title="Your UCL UPI. (It's on your swipecard.)"
                        placeholder="UPI"
                    />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="user_type_id">User Type:</label>
                </td>
                <td>
                    <select id="user_type_id" name="user_type_id">
                        <?php echo $user_type_options; ?>
                    </select>
                </td>
                <td>
                    Surname:
                </td>
                <td>
                    <input
                        type="text"
                        name="user_surname"
                        title="Your surname (or family name)."
                        placeholder="Surname"
                    />
                </td>
            </tr>
            <tr>
                <td>
                    Forenames:
                </td>
                <td>
                    <input
                        type="text"
                        name="user_forenames"
                        title="Your forenames (or given names)."
                        placeholder="Forenames"
                    />
                </td> 
                <td>
                    Preferred name:
                </td>
                <td>
                    <input
                        type="text"
                        name="user_forename_preferred"
                        title="Your preferred name."
                        placeholder="Preferred name"
                    />
                </td>
            </tr>
            <tr>
                <td>
                    E-mail:
                </td>
                <td>
                    <input
                        type="email"
                        name="user_email"
                        title="Your UCL e-mail address. You are unable to use non-UCL e-mail address for Legion accounts."
                        placeholder="person@ucl.ac.uk"
                        pattern="[-0-9a-zA-Z.+_]+@(?:[a-z0-9.-]+.|)ucl\.ac\.uk"
                    />
                </td>
                <td>
                    Contact Number:
                </td>
                <td>
                    <input
                        type="tel"
                        name="user_contact_number"
                        title="A telephone number (or UCL internal extension) we can contact you at."
                        placeholder="00000"
                    />
                </td>
            </tr>
            <tr>
                <td>
                    Department:
                </td>
                <td>
                    <input
                        type="text"
                        name="user_dept"
                        title="The department to which you belong."
                        placeholder="Dept"
                    />
                </td>
            </tr>
            <tr>
                <td>
                    Supervisor (if applicable): <!-- in a future version this label should change with person type -->
                </td>
                <td>
                    <input
                        type="text"
                        name="supervisor_name"
                        title="Your supervisor."
                        placeholder="Dr Per Sonn"
                    /> 
                </td>
                <td>
                    Supervisor's email address:
                </td>
                <td>
                    <input
                        type="email"
                        name="supervisor_email"
                        title="Your supervisor's UCL e-mail address." 
                        placeholder="person@ucl.ac.uk"
                        pattern="[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+"
                    />
                </td>
            </tr>
    
        </table>
        <!-- <p class="note">
            Most of this information should be automatically filled when you log in. If any of it is incorrect, you should contact the ISD Service Desk.
        </p> --> <!--- Hahahahaha no -->
    </div>
    <div class="formSection">
        <h4 class="sectionTitle">
            Experience
        </h4>
        <p class="p">
            Please provide below a summary of your previous HPC/UNIX/Linux experience.
        </p>
        <textarea 
            name="experience_text" 
            rows=8 
            cols=75 
            title="Please provide a summary of your previous HPC/UNIX/Linux experience here." 
            placeholder="E.g. Spent X years using Y system to do Z at Place. Am familiar with systems A and B, and programs C and D."
        ></textarea>
        <h5>
            Support Expectations
        </h5>
        <p class="p">
            <label for="experience_level_id">Which of these options best describes your experience and support level for this service?</label>
        </p>
        <select id="experience_level_id" name="experience_level_id" title="Which of these options best describes your experience and support level for this service?">
            <?php echo $experience_level_options;?>
        </select>
    </div>
    
    <div class="section">
        <h3 class="sectionTitle">
            Research Project Details
        </h3>
        <p class="note">
            In this section we require information about the work you are requesting resources for. When you renew your account, you will be required to provide funding information (including award numbers and/or grant codes) associated with this work, not to charge you, but to allow us to justify the continued existence and maintenance of the services.
        </p>

        <div id="project_{$index}">
            <table>
                <tr>
                    <td>
                        <label for="is_funded">Is this project grant-funded?</label> <!-- this should hide/not hide depending on person type -->
                    </td>
                    <td>
                        <select id="is_funded" name="project[is_funded]"> <!-- id is for hiding and label -->
                            <option value="1"> Yes </option>
                            <option value="0"> No </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Project PI e-mail address: <!-- this should hide/not hide depending on person type -->

                    </td>
                    <td>
                        <input
                            type="email"
                            name="project[pi_email]"
                            title="Project PI's UCL e-mail address." 
                            placeholder="person@ucl.ac.uk"
                            pattern="[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+"
                        />
                    </td>
                </tr>
            </table>
            <p class="p">
                <label>
                    Consortium:
                    <select name="project[consortium_id]">
                        <?php echo $consortium_options;?>
                    </select>
                </label>
            </p>
            <h5>Types of Work</h5>
            <p class="p">
                There are several Research Computing services available at UCL, and the following will help us map your work requirements onto which services you need to have access to. If your work requirements change, please contact rc-support@ucl.ac.uk and we can ensure you have access to the services you need. If you don't know what you need and don't understand the terms used below, please ensure you give us as much information about what applications you're going to need, and we'll try to determine the most effective services for you to use.
            </p>
            <p class="p">
                Types of resource needed:
            </p>
            <ul style="list-style-type: none;">
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_basic" 
                               name="project[checkboxes][work_type_basic]"
                               collating_value="Individual single core jobs"
                         />
                        Individual single core jobs
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_array" 
                               name="project[checkboxes][work_type_array]" 
                               collating_value="Large numbers of single core jobs"
                         />
                        Large numbers (&gt;1000) of single core jobs
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_multithread" 
                               name="project[checkboxes][work_type_multithread]" 
                               collating_value="Multithreaded jobs"
                         />
                        Multithreaded jobs
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_all_the_ram" 
                               name="project[checkboxes][work_type_all_the_ram]" 
                               collating_value="Extremely large quantities of RAM"
                         />
                        Extremely large quantities of RAM (&gt;64GB)
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_small_mpi" 
                               name="project[checkboxes][work_type_small_mpi]" 
                               collating_value="Small MPI jobs (<36 cores)"
                         />
                        Small MPI jobs (&lt;36 cores)
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_mid_mpi" 
                               name="project[checkboxes][work_type_mid_mpi]" 
                               collating_value="Medium MPI jobs (36-256 cores)"
                         />
                        Medium-sized MPI jobs (36-256 cores)
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_large_mpi" 
                               name="project[checkboxes][work_type_large_mpi]" 
                               collating_value="Large MPI jobs (<256 cores)"
                         />
                        Large-sized MPI jobs (&gt;256 cores)
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_small_gpu" 
                               name="project[checkboxes][work_type_small_gpu]" 
                               collating_value=">0 GPUs"
                         />
                        At least one GPGPU
                    </label>
                </li>
                <li>
                    <label>
                        <input type="checkbox" 
                               class="work_type_checkbox"
                               id="work_type_large_gpu" 
                               name="project[checkboxes][work_type_large_gpu]" 
                               collating_value=">10 GPUs"
                         />
                        At least ten GPGPUs
                    </label>
                </li>
            </ul>
            <input type="hidden" id="work_required_collated" name="project[work_required_collated]" />
            
            <p class="p">
                If you have technical requirements that do not fit any of these categories, please describe them here:
            </p>
            <textarea
                    name="project[weird_tech_description]"
                    rows=3
                    cols=70
                    title="If you have technical requirements that do not fit any of these categories, please describe them here."
                    placeholder="E.g. I need to run 5-year-long simulations on 60 Raspberry Pis with Infiniband shields."
                ></textarea>
            
            <p class="p">
                Please provide a brief description of your project, as you would describe it to someone else in your department or general subject area. (This will be sent to the leader of the Consortium you have chosen for approval.)
            </p>
            <textarea
                    name="project[work_description]"
                    rows=8
                    cols=70
                    title="Please provide a brief description of your project, as you would describe it to someone else in your department or general subject area."
                    placeholder="E.g. I will be using technique X to predict Y about Z, in the field of A. This involves doing B and C, with a potential of D."
                ></textarea>

            <p class="p">
                Please provide a list of any software you know you'll need, with approximate versions where known.
            </p>
            <textarea
                    name="project[applications_description]"
                    rows=8
                    cols=70
                    title="Please provide a list of any software you know you'll need, with approximate versions where known."
                    placeholder="Gaussian 04.f, VASP 5.2.19, Braniac 5.0"
                ></textarea>

            <h5>Collaboration</h5>
            <p class="note">
                Statistical information on collaboration is used in reports to various funding bodies, especially in relation to the <a href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing/CfI">Centre for Innovation</a>.
            </p>
            <table>
                <tr>
                    <td>
                        <label><input class="collab_checkbox" collating_value="Bristol: " type="checkbox" name="project[checkboxes][is_collab_bristol]">
                            Bristol
                        </label>
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project[collab_bristol_name]" placeholder="Name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><input class="collab_checkbox" collating_value="Oxford: " type="checkbox" name="project[checkboxes][is_collab_oxford]" />
                            Oxford
                        </label>
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project[collab_oxford_name]" placeholder="Name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><input class="collab_checkbox" collating_value="Southampton: " type="checkbox" name="project[checkboxes][is_collab_soton]" />
                            Southampton
                        </label>
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project[collab_soton_name]" placeholder="Name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label><input class="collab_checkbox" collating_value="Other" type="checkbox" name="project[checkboxes][is_collab_other]" />
                            Other:</label> <input type="text" name="project[collab_other_institute]" placeholder="University" />
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project[collab_other_name]" placeholder="Name"/>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="project[collaboration_collated]" value="(No collaborators specified.)" />

    </div>
    <p class="p">
        <!-- Has an id to let us use $("#tandc").checked -->
        <label><input type="checkbox" id="tandc" name="tandc" value="tandc" />Please tick this to acknowledge that you have read and accepted the <a target="_new" href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing/account/Legion_account_T_Cs_June09.pdf">Research Computing Account terms and conditions</a>. <em>(This link should open in a new tab/window.)</em></label>
    </p>

    <div id="error" style="color: #cc0000;"></div>

    <input type="submit" id="form_submit_button" value="Submit" title="Submit application request." />
</form>

    <script>

    function update_collated_fields() {
        update_work_collated();
        update_collab_collated();
    }

    function update_work_collated() {
        value_string = "";
        work_types = document.getElementsByClassName('work_type_checkbox');

        for (var i=0; i < work_types.length ; i++) {
            if (work_types[i].checked == true) {
                value_string = value_string.concat(" * ", work_types[i].attributes['collating_value'].value, "\n");
            }
        }
        document.getElementById( 'work_required_collated' ).value = value_string;
    }

    function update_collab_collated() {
        value_string = "";
        institutes = ['bristol','oxford','soton','other'];
        for (var i=0;i < institutes.length; i++) {
            checkboxes = document.getElementsByName(''.concat('project[checkboxes][is_collab_',institutes[i],']'));
            checkbox = checkboxes[0];
            console.log('Czech box: ' + checkbox);
            if (checkbox.checked == true) {
                console.log(''.concat('project[collab_',institutes[i],'_name]'));
                name_fields = document.getElementsByName(''.concat('project[collab_',institutes[i],'_name]'));
                name_field = name_fields[0];
                console.log('Name_field ' + name_field);
                if (institutes[i] != 'other') {
                    label = checkbox.attributes['collating_value'].value;
                } else {
                    institute_fields = document.getElementsByName('project[collab_other_institute]');
                    institute_field  = institute_fields[0];
                    label = ''.concat( institute_field.value, ": ");
                }
                value_string = value_string.concat(label, name_field.value, "\n");
                
            }
        }
        target_elements = document.getElementsByName('project[collaboration_collated]');
        target_elements[0].value = value_string;
    }

    $( '#application_form' ).submit( function( event ) {
        if ( $('#tandc').is( ":checked" ) != true  ) {
            $( '#error' ).text("You must accept the Terms and Conditions to apply.").show();
            event.preventDefault();
        } else {
            update_collated_fields();
            
            $( '#notice' ).text("").show();
            return;
        }
    });
    </script>

<?php
    include "footer.php";
?>
