<?php
/**
 * This file contains all the form elements for submitting publication and project code info.
 *
 * As with the application form version, I'm not sure separating it out was useful, but it couldn't hurt I guess.
 */
?>
<form action="submit_publications.php" method="post" enctype="multipart/form-data">
    <p class="p">
        Publications and grants produced by our users help us justify the funding that goes towards
        providing the services we do. For this reason, our academic governance group, the
        Computational Resources Allocation Group (CRAG), request that you annually provide
        us with information about work you've used our services for, that we can present as
        evidence that the services are used and are valuable to UCL researchers.
    </p>
    <h5>Research Project Codes</h5>
    <p class="p">
        A Research Project Code is an account code allocated to a research project, like a bank account number,
        for the financial administration of the project. We link these to grants, to create
        statistics on how much grant-funded work is using the services.
    </p>
    <p class="p">
        Please add below any and all Research Project Codes associated with work you have used
        one of our services for in the past year.
    </p>
    <p class="p">
        <b>These Research Project Codes will not be charged. We do not invoice or charge for the use of any of our services.</b>
    </p>
    <p class="p">
        If you don't know what your Research Project Codes are, your PI and/or departmental administrative staff
        should be able to tell you.
    </p>
    <table id="research_project_codes_form_table">
        <tr id="research_project_codes_header_tr">
            <td>
                Enter code(s) below:
            </td>
        </tr>
        <tr id="research_project_codes_row[0]">
            <td>
                <input type="text" name="research_project_codes[0]" placeholder="code"/>
            </td>
        </tr>
        <tr id="research_project_codes_add_row_tr">
            <td colspan="2" id="research_project_codes_add_row_td">
                <a id="research_project_codes_add_row_link" title="Click to add another row" href="#" onclick="add_research_project_codes_row();return false;">Add Another Row</a>
            </td>
        </tr>
    </table>

    <h5>Publications</h5>
    <p class="p">
        If you've produced any linkable work, whether a journal paper,
        conference paper, blog post, magazine article, or similar, that used one of
        our services, we'd like to hear about it via this form.
    </p>
    <p class="p">
        If that work was especially notable or well-regarded within your field, e.g. a paper in Nature, Science,
        or The Lancet,<a href="#disclaimer">[1]</a> or a review paper, please also tick the box alongside so we can make special mention of these in our
        reports.
    </p>
    <p class="p">
        Don't worry if you think you may have already submitted a particular piece of work here -- we can
        de-duplicate these.
    </p>
    <p class="p">
        Also, if you're familiar with the <span title="Digital Object Identifier">DOI</span> system, it would be useful
        for us if you could use links that go via the DOI resolver
        <em>(e.g. <a href="http://dx.doi.org/10.1021/ic301109f">http://dx.doi.org/10.1021/ic301109f</a>)</em>,
        since they're more likely to remain valid for longer. (Journal websites are obliged to update those links as they move content around.)
    </p>


    <table id="publications_form_table">
        <tr id="publications_header_tr">
            <th>
                Highlight
            </th>
            <th>
                Link
            </th>
        </tr>
        <tr id="publications_row[0]">
            <td>
                <input type="checkbox" name="publications[0][notable]" />
            </td>
            <td>
                <input type="text" name="publications[0][url]" placeholder="link"/>
            </td>
        </tr>
        <tr id="publications_add_row_tr">
            <td colspan="2" id="publications_add_row_td">
                <a id="publications_add_row_link" title="Click to add another row" href="#" onclick="add_publications_row();return false;">Add Another Row</a>
            </td>
        </tr>
        <tr id="submit_tr" name="submit_tr">
            <td colspan="2">
                <input type="submit" name="Submit" />
            </td>
        </tr>
    </table>
</form>

<p class="p"><em><a id="disclaimer">[1]</a>: Please note: we chose these examples because we believed them to be journals in fields that we knew about that it would be a widely understood success to get a paper published in. If you know of similar journals for other fields, let us know and we'll add them.</em></p>


<script language="JavaScript">
global_old_row="";

    function add_publications_row() {
        var table = document.getElementById("publications_form_table");
        var old_row = table.rows[table.rows.length - 3];
        var new_row = table.insertRow(table.rows.length - 2);

        var old_row_num = Number(old_row.id.slice("publications_row[".length,-1));
        var new_row_num = old_row_num + 1;

        var n = String(new_row_num);
        new_row.id = ''.concat("publications_row[",n,"]");
        new_row.innerHTML = ''.concat(
                "\t\t\t<td>\n",
                "\t\t\t\t<input type=\"checkbox\" name=\"publications[", n, "][notable]\" />",
                "\t\t\t</td>",
                "\t\t\t<td>",
                "\t\t\t\t<input type=\"text\" name=\"publications[", n, "][link]\" placeholder=\"link\"/>",
                "\t\t\t</td>"
            );
    }

    function add_research_project_codes_row() {
        var table = document.getElementById("research_project_codes_form_table");
        var old_row = table.rows[table.rows.length - 3];
        var new_row = table.insertRow(table.rows.length - 2);

        var old_row_num = Number(old_row.id.slice("research_project_codes_row[".length,-1));
        var new_row_num = old_row_num + 1;

        var n = String(new_row_num);
        new_row.id = ''.concat("research_project_codes_row[",n,"]");
        new_row.innerHTML = ''.concat(
                "\t\t\t<td>",
                "\t\t\t\t<input type=\"text\" name=\"research_project_codes[", n, "]\" placeholder=\"code\"/>",
                "\t\t\t</td>"
            );
    }
</script>
