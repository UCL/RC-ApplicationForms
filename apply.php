<?php
    include "header.php";
?>

<!-- Begin form -->
<?php
    include "includes_nodb.php";
    $current_user = $_SERVER['PHP_AUTH_USER'];
?>

<form action="submit.php" method="post" enctype="multipart/form-data" id="application_form">
    <div class="section">
        <h3 class="sectionTitle">
            User Information
        </h3>
        <input type="text" value="Apply" name="submit_type" style="display:none" readonly/>
        <table> <!-- Shouldn't really use tables for layout, but I'm just copy/pasting at the moment. -->
            <tr>
                <td> UCL userid: </td>
                <td>
                    <input 
                        type="text" 
                        name="username" 
                        title="Your existing UCL userid." 
                        readonly 
                        value="<?php echo "$current_user" ?>"
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
                    User Type:
                </td>
                <td>
                    <select name="user_type_id">
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
            Which of these options best describes your experience and support level for this service?
        </p>
        <select name="experience_level_id" title="Which of these options best describes your experience and support level for this service?">
            <?php echo $experience_level_options; ?>
        </select>
    </div>
    
    <div class="section">
        <h3 class="sectionTitle">
            Research Project Details
        </h3>
        <p class="note">
            In this section we require information about the work you are requesting resources for. When you renew your account, you will be required to provide funding information (including award numbers and/or grant codes) associated with this work, not to charge you, but to allow us to justify the continued existence and maintenance of the services.
        </p>

        <?php
            $consortia_options = options_from_consortia();
        ?>
        <div id="project_{$index}">
            <table>
                <tr>
                    <td>
                        Is this project grant-funded? <!-- this should hide/not hide depending on person type -->
                    </td>
                    <td>
                        <select name="project['is_funded']">
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
                            name="pi_email"
                            title="Project PI's UCL e-mail address." 
                            placeholder="person@ucl.ac.uk"
                            pattern="[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+"
                        />
                    </td>
                </tr>
            </table>
            <p class="p">
                Consortium:
                <select name="project['consortium_id']">
                    <?php echo $consortia_options;?>
                </select>
            </p>
            <h5>Types of Work</h5>
            <p class="p">
                There are several Research Computing services available at UCL, and the following will help us map your work requirements onto which services you need to have access to. If your work requirements change, please contact rc-support@ucl.ac.uk and we can ensure you have access to the services you need. If you don't know what you need and don't understand the terms used below, please ensure you give us as much information about what applications you're going to need, and we'll try to determine the most effective services for you to use.
            </p>
            <p class="p">
                Types of resource needed:
            </p>
            <ul style="list-style-type: none;">
                <li><input type="checkbox" name="project['work_type']['basic']" />Individual single core jobs</li>
                <li><input type="checkbox" name="project['work_type']['array']" />Large numbers (&gt;1000) of single core jobs</li>
                <li><input type="checkbox" name="project['work_type']['multithread']" />Multithreaded jobs</li>
                <li><input type="checkbox" name="project['work_type']['all_the_ram']" />Extremely large quantities of RAM (&gt;64GB)</li>
                <li><input type="checkbox" name="project['work_type']['small_mpi']" />Small MPI jobs (8-36 cores)</li>
                <li><input type="checkbox" name="project['work_type']['mid_mpi']" />Medium-sized MPI jobs (36-256 cores)</li>
                <li><input type="checkbox" name="project['work_type']['large_mpi']" />Large-sized MPI jobs (&gt;256 cores)</li>
                <li><input type="checkbox" name="project['work_type']['small_gpu']" />At least one GPGPU</li>
                <li><input type="checkbox" name="project['work_type']['large_gpu']" />At least ten GPGPUs</li>
            </ul>
            
            <p class="p">
                If you have technical requirements that do not fit any of these categories, please describe them here:
            </p>
            <textarea
                    name="project['weird_tech_description']"
                    rows=3
                    cols=70
                    title="If you have technical requirements that do not fit any of these categories, please describe them here."
                    placeholder="E.g. I need to run 5-year-long simulations on 60 Raspberry Pis with Infiniband shields."
                ></textarea>
            
            <p class="p">
                Please provide a brief description of your project, as you would describe it to someone else in your department or general subject area. (This will be sent to the leader of the Consortium you have chosen for approval.)
            </p>
            <textarea
                    name="project['work_description']"
                    rows=8
                    cols=70
                    title="Please provide a brief description of your project, as you would describe it to someone else in your department or general subject area."
                    placeholder="E.g. I will be using technique X to predict Y about Z, in the field of A. This involves doing B and C, with a potential of D."
                ></textarea>

            <p class="p">
                Please provide a list of any software you know you'll need, with approximate versions where known.
            </p>
            <textarea
                    name="project['applications_description']"
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
                        <input type="checkbox" name="project['is_collab_bristol']">
                            Bristol
                        </input>
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project['collab_bristol_name']" placeholder="Name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="project['is_collab_oxford']" />
                            Oxford
                        </input>
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project['collab_oxford_name']" placeholder="Name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="project['is_collab_soton']" />
                            Southampton
                        </input>
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project['collab_soton_name']" placeholder="Name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="project['is_collab_other']" />
                            Other: <input type="text" name="project['collab_other_institute']" placeholder="University" />
                        </input>
                    </td>
                    <td>
                        PI/lead CoI:
                        <input type="text" name="project['collab_other_name']" placeholder="Name"/>
                    </td>
                </tr>
            </table>

    </div>
    <p class="p">
        <!-- Has an id to let us use $("#tandc").checked -->
        <input type="checkbox" id="tandc" name="tandc" value="tandc">Please tick this to acknowledge that you have read and accepted the <a target="_new" href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing/account/Legion_account_T_Cs_June09.pdf">Research Computing Account terms and conditions</a>. <em>(This link should open in a new tab/window.)</em></input>
    </p>

    <input type="submit" value="Submit" title="Submit application request." />
</form>


<?php
    include "footer.php";
?>
