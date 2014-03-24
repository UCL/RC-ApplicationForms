<?php

include "includes/misc_functions.php";

class MiscFunctionsTest extends PHPUnit_Framework_TestCase {

    public function test_array_as_text_list() {
        //NB: doesn't test with non-numerical indices.
        $this->assertEquals ("", array_as_text_list(array()));
        $this->assertEquals ("one", array_as_text_list(array('one')));
        $this->assertEquals ("one and two", array_as_text_list(array('one','two')));
        $this->assertEquals ("one, two, and three", array_as_text_list(array('one','two','three')));
        $this->assertEquals ("one, two, three, and four", array_as_text_list(array('one', 'two', 'three', 'four')));
        $this->assertEquals ("one, two, or three", array_as_text_list(array('one','two','three'), " or "));
        $this->assertEquals ("one, two, three, or four", array_as_text_list(array('one', 'two', 'three', 'four'), " or "));
    }


    public function test_table_keyval() {
        $this->assertEquals(table_keyval("test_key", "test_value"),
            "    <tr class='even'>\n" .
            "        <td>\n" .
            "            <strong>test_key</strong>\n" .
            "        </td>\n" .
            "        <td>\n" .
            "            test_value\n" .
            "        </td>\n" .
            "    </tr>\n"
        );
        $this->assertEquals(table_keyval("test_key", "test_value", 1),
            "    <tr>\n" .
            "        <td colspan=2 class='odd'>\n" .
            "            <strong>test_key</strong>\n" .
            "        </td>\n" .
            "    </tr>\n" .
            "    <tr>\n" .
            "        <td colspan=2 class='even'>\n" .
            "            test_value\n" .
            "        </td>\n" .
            "    </tr>\n"
        );
    }

}

?>

