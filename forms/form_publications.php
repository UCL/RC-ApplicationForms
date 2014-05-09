<?php
/**
 * Created by PhpStorm.
 * User: ik
 * Date: 2014-05-07
 * Time: 16:30
 */
?>

<p class="p">
Publications produced by our users help us justify the funding that goes towards
    providing the services we do. If you've produced any linkable work, whether a journal paper,
    conference paper, blog post, magazine article, or similar, that used one of
    our services, we request that you give us a link to it using this form.
</p>
<p class="p">
    If that work was especially notable or well-regarded within your field, e.g. a paper in Nature, Science,
    The Lancet, or a review paper, please also tick the box alongside so we can make special mention of these in our
    reports to our governance groups.
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


<form action="submit_publications.php" method="post" enctype="multipart/form-data">
    <table id="form_table">
        <tr id="header_tr">
            <th>
Highlight
            </th>
            <th>
Link
            </th>
        </tr>
        <tr id="row[0]">
            <td>
                <input type="checkbox" name="publications[0][notable]" />
            </td>
            <td>
                <input type="text" name="publications[0][url]" placeholder="link"/>
            </td>
        </tr>
        <tr id="add_row_tr">
            <td colspan="2" id="add_row_td">
                <a id="add_row_link" title="Click to add another row" href="#" onclick="add_row();return false;">Add Another Row</a>
            </td>
        </tr>
        <tr id="submit_tr" name="submit_tr">
            <td colspan="2">
                <input type="submit" name="Submit" />
            </td>
        </tr>
    </table>
</form>




<script language="JavaScript">
global_old_row="";
    function add_row() {
        var table = document.getElementById("form_table");
        var old_row = table.rows[table.rows.length - 3];
        var new_row = table.insertRow(table.rows.length - 2);

        var old_row_num = Number(old_row.id.slice(4,-1));
        var new_row_num = old_row_num + 1;

        var n = String(new_row_num);
        new_row.id = ''.concat("row[",n,"]");
        new_row.innerHTML = ''.concat(
                "\t\t\t<td>\n",
                "\t\t\t\t<input type=\"checkbox\" name=\"publications[", n, "][notable]\" />",
                "\t\t\t</td>",
                "\t\t\t<td>",
                "\t\t\t\t<input type=\"text\" name=\"publications[", n, "][link]\" placeholder=\"link\"/>",
                "\t\t\t</td>"
            );
    }
</script>
