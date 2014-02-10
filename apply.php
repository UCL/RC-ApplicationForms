<?php
    include "header.php";
?>

<!-- Begin form -->
<?php
    include "includes_nodb.php";
    $username_from_ldap = $_SERVER['PHP_AUTH_USER'];
?>

<form action="submit.php" method="post" enctype="multipart/form-data" id="application_form">
    <div class="section">
        <h4 class="sectionTitle">
            User Information
        </h4>
        <table> <!-- Shouldn't really use tables for layout, but I'm just copy/pasting at the moment. -->
            <tr>
                <td> UCL userid: </td>
                <td>
                    <input 
                        type="text" 
                        name="userid" 
                        title="Your existing UCL userid." 
                        readonly 
                        value="<?php echo "$username_from_ldap" ?>"
                    />
                </td>
                <td>
                    UPI:
                </td>
                <td> <!-- See if you can gen this. -->
                    <input 
                        type="text"
                        name="upi"
                        title="Your UCL UPI. (It's on your swipecard.)"
                        default="UPI"
                    />
                </td>
            </tr>
            <tr>
                <td>
                    User Type:
                </td>
                <td>
                    <select name="usertype">
                        <option value="research">Research Staff </option>
                        <option value="postgrad">Postgraduate Student </option>
                        <option value="undergrad">Undergraduate Student </option>
                        <option value="support">Support Staff </option>
                        <option value="other">Other </option>
                    </select>
                </td>
                <td>
                    Surname:
                </td>
                <td>
                    <input
                        type="text"
                        name="surname"
                        title="Your surname (or family name)."
                        default="Surname"
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
                        name="forenames"
                        title="Your forenames (or given names)."
                        default="Forenames"
                    />
                </td> 
                <td>
                    Preferred name:
                </td>
                <td>
                    <input
                        type="text"
                        name="name_preferred"
                        title="Your preferred name."
                        default="Preferred name"
                    />
                </td>
            </tr>
            <tr>
                <!-- Hopefully we can autograb this, too, and possibly perform some validation. (e.g. no Gmail addresses, toolbags) -->
                <td>
                    E-mail:
                </td>
                <td>
                    <input
                        type="email"
                        name="email"
                        title="Your UCL e-mail address. You are unable to use non-UCL e-mail address for Legion accounts."
                        default="person@ucl.ac.uk"
                    />
                </td>
                <td>
                    Contact Number:
                </td>
                <td>
                    <input
                        type="tel"
                        name="phone"
                        title="A telephone number (or UCL internal extension) we can contact you at."
                        default="00000"
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
                        name="dept"
                        title="The department to which you belong."
                        default="Dept"
                    />
                </td>
            </tr>
        </table>
        <p class="note">
            Most of this information should be automatically filled when you log in. If any of it is incorrect, you should contact the ISD Service Desk.
        </p>
    </div>
    <div class="formSection">
        <h4 class="sectionTitle">
            Experience
        </h4>
        <p class="p">
            Please provide below a summary of your previous HPC/UNIX/Linux experience.
        </p>
        <textarea 
            name="hpc_experience" 
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
        <select name="support_level" title="Which of these options best describes your experience and support level for this service?">
            <option value="Novice with No Local Support">I am a novice user with no local support</option>
            <option value="Novice with Local Support">I am a novice user with local support</option>
            <option value="Linux experience but no HPC">I am experienced with UNIX/Linux but not HPC</option>
            <option value="Linux and HPC experience">I am experience with UNIX/Linux and HPC</option>
        </select>
    </div>
    
    <div class="section">
        <h4 class="sectionTitle">
            Research Project Details
        </h4>
        <p class="note">
            In this section we require information about your funding sources. This information is not used to charge for the service, but to allow us to track what types of research users are being funded to carry out.
        </p>
        <p class="p">
            How many projects are you planning to use Research Computing Platforms for over the next year?
        </p>
        <select name="number_of_projects" title="How many projects are you planning to use Research Computing Platforms for over the next year?">
            <option value="0">0</option>    
            <option value="1">1</option>    
            <option value="2">2</option>    
            <option value="3">3</option>    
            <option value="4">4</option>    
            <option value="5">5</option>    
            <option value="6">6</option>    
            <option value="7">7</option>    
            <option value="8">8</option>    
            <option value="9">9</option>    
            <option value="10">10</option>    
        </select>

        <div id="projects">
            <!-- We *could* dynamically generate these, but putting them in explicitly will make it much easier to save and reload data later, I think. -->
            <?php
                function make_project_section($index) {
                    $consortia_options = options_from_consortia();
                    echo <<<HEREDOC
            <div id="project_{$index}">
                <h4> Project {$index} </h4>
                <input type="checkbox" style="display: none" name="project[{$index}]['is_used']" checked/> <!-- If we start modifying the visibility of these project sections, this is to tell the submission handler whether they're being used. -->
                <table>
                    <tr>
                        <td>
                            Is this project grant-funded?
                        </td>
                        <td>
                            <select name="project[{$index}]['is_funded']">
                                <option value="1"> Yes </option>
                                <option value="0"> No </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Research Project Account Code:
                        </td>
                        <td>
                            <input 
                                type="text"
                                name="project[{$index}]['project_account_code']"
                                placeholder="e.g. XXX0 or XX00"
                                title="Project Account Code."
                            />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Project PI Username:
                        </td>
                        <td>
                            <input
                                type="text"
                                name="project[{$index}]['pi_userid']"
                                placeholder="e.g. ccaaxxx"
                                title="The PI of this project."
        	                />
                        </td>
                    </tr>
                </table>
                <p class="p">
                    Consortium:
                    <select name="project[{$index}]['consortium']">
                        $consortia_options
                    </select>
                </p>
                <h5>Services Required</h5>
                <p class="p">
                    There are three services available. The Standard UCL Research Computing Services include Legion, Unity, Condor, and magical bean stalks. You may also apply for access to the CfI Iridis and Emerald services, which are facilities shared with a number of other universities.
                </p>
                <ul style="list-style-type: none;">
                    <li><input type="checkbox" name="project[${index}]['requires_rcps']" />UCL Research Computing Services</li>
                    <li><input type="checkbox" name="project[${index}]['requires_iridis']" />Iridis</li>
                    <li><input type="checkbox" name="project[${index}]['requires_emerald']" />Emerald</li>
                </ul>

                <p class="p">
                    Please provide details of the computational work that you wish to use the CfI facilities for, in terms of job types, and approximate length and size.
                </p>

                <textarea 
                    name="project[${index}]['cfi_details']" 
                    rows=8 
                    cols=75 
                    title="Please provide details of the computational work that you wish to use the CfI facilities for; job types, length and size."
                >
                </textarea>

                <p class="p">
                    Please explain why it is more appropriate for this work to be performed on the requested CfI facilities, rather than on the Legion service or the national HECTOR or ARCHER services.
                </p>

                <textarea 
                    name="project[${index}]['cfi_justify']" 
                    rows=8 
                    cols=75 
                    title="Please explain why it is more appropriate for this work to be performed on the requested CfI facilities, rather than on the Legion service or the national HECTOR or ARCHER services."
                >
                </textarea>

                <p class="p">
                    With references to your responses above, please explain how this project will make use of the CfI facilities to deliver world-class research outcomes that would not otherwise be possible.
                </p>

                <textarea 
                    name="project[${index}]['cfi_lie_through_your_teeth']" 
                    rows=8 
                    cols=75 
                    title="With references to your responses above, please explain how this project will make use of the CfI facilities to deliver world-class research outcomes that would not otherwise be possible."
                >
                </textarea>

                <h5>Collaboration</h5>
                <p class="note">
                    This information is used to justify CfI funding across the partner institutions.
                </p>
                <table>
                    <tr>
                        <td>
                            <input type="checkbox" name="project[{$index}]['is_collab_bristol']">
                                Bristol
                            </input>
                        </td>
                        <td>
                            PI/lead CoI:
                            <input type="text" name="project[{$index}]['collab_bristol_name']" placeholder="Name"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="project[{$index}]['is_collab_oxford']" />
                                Oxford
                            </input>
                        </td>
                        <td>
                            PI/lead CoI:
                            <input type="text" name="project[{$index}]['collab_oxford_name']" placeholder="Name"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="project[{$index}]['is_collab_soton']" />
                                Southampton
                            </input>
                        </td>
                        <td>
                            PI/lead CoI:
                            <input type="text" name="project[{$index}]['collab_soton_name']" placeholder="Name"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="project[{$index}]['is_collab_other']" />
                                Other: <input type="text" name="project[{$index}]['collab_other_institute']" placeholder="University" />
                            </input>
                        </td>
                        <td>
                            PI/lead CoI:
                            <input type="text" name="project[{$index}]['collab_other_name']" placeholder="Name"/>
                        </td>
                    </tr>
                </table>

            </div>
HEREDOC;
                };
            make_project_section(0);
            ?>
        </div>
    </div>
    <p class="p">
        If you need additional resources beyond those normally available, you may make a request through the <a href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing/services/legion-upgrade/resource_allocation/#requests">CRAG</a>.
    </p>
    <p class="p">
        <input type="checkbox" name="tandc" value="tandc">I have read and accept the <a href="http://www.ucl.ac.uk/isd/staff/research_services/research-computing/account/Legion_account_T_Cs_June09.pdf">Research Computing Account terms and conditions</a>.</input>
    </p>

    <input type="submit" value="Submit" title="Submit application request." />
</form>


<?php
    include "footer.php";
?>
