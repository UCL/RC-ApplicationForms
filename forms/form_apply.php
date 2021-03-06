<?php
/**
 * This file contains all the form elements for use in apply.php
 *
 * I'm not *entirely* convinced that separating it out was useful, but meh.
 *
 * There's only a little PHP in here, for inserting option sets and values.
 */
?>

<form id="application_form" action="submit_application.php" method="post" enctype="multipart/form-data">
    <div class="section">
        <h3 class="sectionTitle">
User Information
</h3>
        <input type="text" value="Apply" name="submit_type" style="display:none" readonly/>
        <table> <!-- Shouldn't really use tables for layout, but I'm just copy/pasting at the moment. -->
            <tr>
                <td> <label for="username">UCL userid:</label></td>
                <td>
                    <input
                        type="text"
                        id="username"
                        name="user_profile[username]"
                        title="Your existing UCL username."
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
                        name="user_profile[user_upi]"
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
                    <select id="user_type_id" name="user_profile[user_type_id]" name="user_profile[user_type_id]">
                        <?php echo $user_type_options; ?>
</select>
</td>
<td>
    Surname:
</td>
<td>
    <input
        type="text"
        name="user_profile[user_surname]"
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
            name="user_profile[user_forenames]"
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
            name="user_profile[user_forename_preferred]"
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
            name="user_profile[user_email]"
            title="Your UCL e-mail address. You are unable to use non-UCL e-mail address for Legion accounts."
            placeholder="person@ucl.ac.uk"
            pattern="[-0-9a-zA-Z.+_]+@(?:[a-z0-9.-]+.|)ucl\.ac\.uk"
            />
    </td>
    <td>
        Contact Number (optional):
    </td>
    <td>
        <input
            type="tel"
            name="user_profile[user_contact_number]"
            title="A telephone number (or UCL internal extension) we can contact you with."
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
            name="user_profile[user_dept]"
            title="The department to which you belong."
            placeholder="Dept"
            />
    </td>
</tr>
</table>
</div>
<div class="formSection">
    <h3 class="sectionTitle">
        Sponsor
    </h3>
    <p class="p">
        To obtain an account, you must either be, or be sponsored and approved by, a permanent member of staff. Please put their 7-character UCL username (e.g. ccaanam) here.
        If you are a student or a postdoctoral researcher, this should normally be your supervisor.
        If you are a permanent member of staff, please leave this field blank.
    </p>
    <p>
        The email address corresponding to this username will be contacted to ask them to approve your application.
    </p>
    <table>
        <tr>
            <td>
                Username of Account Sponsor: <!-- in a future version this label should change with person type -->
            </td>
            <td>
                <input
                    type="text"
                    id="sponsor_username"
                    name="user_profile[sponsor_username]"
                    title="The username of a permanent member of staff who is prepared to approve your account request."
                    placeholder=""
                    pattern="[a-z0-9]{7}"
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
        name="user_profile[experience_text]"
        rows=5
        cols=70
        title="Please provide a summary of your previous HPC/UNIX/Linux experience here."
        placeholder="E.g. Spent X years using Y system to do Z at Place. Am familiar with systems A and B, and programs C and D."
        ></textarea>
    <h5>
        Support Expectations
    </h5>
    <p class="p">
        <label for="experience_level_id">Which of these options best describes your experience and support level for this service?</label>
    </p>
    <select id="experience_level_id" name="user_profile[experience_level_id]" title="Which of these options best describes your experience and support level for this service?">
        <?php echo $experience_level_options;?>
    </select>
</div>

<div class="section">
<h3 class="sectionTitle">
    Research Project Details
</h3>
<p class="p">
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
        Research Theme <em>(these are based on REF categories)</em>:
        <select name="project[research_theme_id]">
            <?php echo $research_themes;?>
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
                   name="project[work_type_basic]"
                />
            Individual single core jobs
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_array"
                   name="project[work_type_array]"
                />
            Large numbers (&gt;1000) of single core jobs
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_multithread"
                   name="project[work_type_multithread]"
                />
            Multithreaded jobs
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_all_the_ram"
                   name="project[work_type_all_the_ram]"
                />
            Extremely large quantities of RAM (&gt;64GB)
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_small_mpi"
                   name="project[work_type_small_mpi]"
                />
            Small MPI jobs (&lt;36 cores)
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_mid_mpi"
                   name="project[work_type_mid_mpi]"
                />
            Medium-sized MPI jobs (36-256 cores)
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_large_mpi"
                   name="project[work_type_large_mpi]"
                />
            Large-sized MPI jobs (&gt;256 cores)
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_small_gpu"
                   name="project[work_type_small_gpu]"
                />
            At least one GPGPU
        </label>
    </li>
    <li>
        <label>
            <input type="checkbox"
                   class="work_type_checkbox"
                   id="work_type_large_gpu"
                   name="project[work_type_large_gpu]"
                />
            At least ten GPGPUs
        </label>
    </li>
</ul>

<p class="p">
    If you have technical requirements that do not fit any of these categories, please describe them here (optional):
</p>
<textarea
    name="project[weird_tech_description]"
    rows=3
    cols=70
    title="If you have technical requirements that do not fit any of these categories, please describe them here."
    placeholder="E.g. I need to run 5-year-long simulations on 60 Raspberry Pis with Infiniband shields."
    ></textarea>

<p class="p">
    Please provide a brief description of your project, as you would describe it to someone else in your department or general subject area. (This will be sent to your sponsor, for approval, and the operations team.)
</p>
<textarea
    name="project[work_description]"
    rows=5
    cols=70
    title="Please provide a brief description of your project, as you would describe it to someone else in your department or general subject area."
    placeholder="E.g. I will be using technique X to predict Y about Z, in the field of A. This involves doing B and C, with a potential of D."
    ></textarea>

<p class="p">
    Please provide a list of any software you know you'll need, with approximate versions where known.
</p>
<textarea
    name="project[applications_description]"
    rows=3
    cols=70
    title="Please provide a list of any software you know you'll need, with approximate versions where known."
    placeholder="E.g. Gaussian 04f, VASP 5.2.19, Braniac 5.0"
    ></textarea>
</div>
<div>
    <h5>External Collaboration</h5>
    <p class="note">
        Statistical information on collaboration with other institutions and organisations is used in reports to
        various funding bodies, especially in relation to the
        <a href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing/CfI">Centre for Innovation</a> and
        the related <a href="http://www.ses.ac.uk/">Science and Engineering South</a> (SES) consortium.
    </p>
    <datalist id="collab_org_list">
        <option value="Bristol" />
        <option value="Cambridge" />
        <option value="Imperial College" />
        <option value="King's College" />
        <option value="Oxford" />
        <option value="Southampton" />
        <option value="Warwick" />
    </datalist>

    <table id="collaborations_form_table">
        <tr id="collaborations_row[0]">
            <td>
                <label>
                    University/Organisation:
                    <input type="text" style="width:100px;margin-right:1em;" name="collaborations[0][organisation_name]" list="collab_org_list" />
                </label>
            </td>
            <td>
                <label>
                    PI/lead:
                    <input type="text" name="collaborations[0][collaborator_contact_name]" />
                </label>
            </td>
            <td>
                <label>
                    <input type="checkbox" name="collaborations[0][is_private_sector]" />
                    Industrial partner?
                </label>
            </td>
        </tr>
        <tr id="collaborations_add_row_tr">
            <td colspan="3" id="collaborations_add_row_td">
                <a id="collaborations_add_row_link" title="Click to add another row" href="#" onclick="add_collaborations_row();return false;">Add Another Row</a>
            </td>
        </tr>
    </table>
</div>
</div>
<p class="p">
    <!-- Has an id to let us use $("#tandc").checked -->
    <label>
        <input type="checkbox" id="tandc" name="tandc" value="tandc" />
            Please tick this to acknowledge that you have read and accepted the 
            <a target="_new" href="https://wiki.rc.ucl.ac.uk/wiki/Terms_and_Conditions">
                Research Computing Account terms and conditions.
            </a>
            <em>
                (This link should open in a new tab/window.)
            </em>
    </label>
</p>

<div id="error" style="color: #cc0000;">
    <ul>
        <li id="err_all_fields" style="color: #cc0000;display:none">You must complete all text fields not marked as optional.</li>
        <li id="err_must_accept" style="color: #cc0000;display:none">You must accept the Terms and Conditions to apply.</li>
        <li id="err_sponsor_self" style="color: #cc0000;display:none">Your sponsor's username must not be the same as your username. If your application does not require approval, please leave the sponsor username field blank.</li>
        <li id="err_invalid_sponsor" style="color: #cc0000;display:none">The sponsor username provided is invalid. Please provide their UCL username; for example, ccaaeof.</li>
    </ul>
</div>

<input type="submit" id="form_submit_button" value="Submit" title="Submit application request." />
</form>

<style>
  .problem {background-color: #ff9999};
 </style>

<script>
    function add_collaborations_row() {
        var table = document.getElementById("collaborations_form_table");
        var old_row = table.rows[table.rows.length - 2];
        var new_row = table.insertRow(table.rows.length - 1);

        var old_row_num = Number(old_row.id.slice("collaborations_row[".length,-1));
        var new_row_num = old_row_num + 1;

        var n = String(new_row_num);
        new_row.id = ''.concat("collaborations_row[",n,"]");
        new_row.innerHTML = ''.concat(
            "\t\t\t<td>",
            "\t\t\t\t<label>",
            "\t\t\t\t\tUniversity/Organisation:",
            "\t\t\t\t\t<input type=\"text\" style=\"width:100px;margin-right:1em;\" name=\"collaborations[",n,"][organisation_name]\" list=\"collab_org_list\" />",
            "\t\t\t\t</label>",
            "\t\t\t</td>",
            "\t\t\t<td>",
            "\t\t\t\t<label>",
            "\t\t\t\t\tPI/lead:",
            "\t\t\t\t\t<input type=\"text\" name=\"collaborations[",n,"][collaborator_contact_name]\" />",
            "\t\t\t\t</label>",
            "\t\t\t</td>",
            "\t\t\t<td>",
            "\t\t\t\t<label>",
            "\t\t\t\t\t<input type=\"checkbox\" name=\"collaborations[",n,"][is_private_sector]\" />",
            "\t\t\t\t\tIndustrial partner?",
            "\t\t\t\t</label>",
            "\t\t\t</td>"
        );
    }

    function is_sponsor_username_valid() {
        var sponsor_field = $("#sponsor_username");

        if ( null == sponsor_field.val().match(/^(|[a-z0-9]{7})$/) ) {
            return false;
        } else {
            return true;
        }
    }

    function is_sponsor_user() {
        var sponsor_field = $("#sponsor_username");
        var user_field = $('#username').val();

        if (sponsor_field == user_field) {
            return true;
        } else {
            return false;
        }
    }

    function have_terms_been_accepted() {
        if ( $('#tandc').is(":checked") == true) {
            return true;
        } else {
            return false;
        }
    }


    $( '#application_form' ).submit( function( event ) {
        var prevent_submit = false;

        if (have_terms_been_accepted() == true) {
            $('#err_must_accept').hide();
        } else {
            $('#err_must_accept').show();
            prevent_submit = true;
        }

        if (is_sponsor_user()) {
            $('#err_sponsor_self').show();
            prevent_submit = true;
        } else {
            $('#err_sponsor_self').hide();
        }

        if (is_sponsor_username_valid()) {
            $('#err_invalid_sponsor').hide();
        } else {
            $('#err_invalid_sponsor').show();
            prevent_submit = true;
        }

        var mandatory_fields = [
            "user_profile[username]",
            "user_profile[user_upi]",
            "user_profile[user_surname]",
            "user_profile[user_forenames]",
            "user_profile[user_forename_preferred]",
            "user_profile[user_email]",
            "user_profile[user_dept]",
            "user_profile[experience_text]",
            "project[work_description]",
            "project[applications_description]"
             ];
        for (var i=0; i<mandatory_fields.length; i++) {
            var field = $("#application_form input[name='"+ mandatory_fields[i] +"']");
            if ( field.val() == "" ) {
                field.addClass("problem");
                $('#err_all_fields').show();
                prevent_submit = true;
            } else {
                field.removeClass("problem");
            }
        }

        if (prevent_submit == true) {
            event.preventDefault();
        }
    });

    //Make *absolutely* sure error boxes are hidden from the start
    $('#err_sponsor_self').hide();
    $('#err_invalid_sponsor').hide();
    $('#err_must_accept').hide();
    $('#err_all_fields').hide();
</script>
