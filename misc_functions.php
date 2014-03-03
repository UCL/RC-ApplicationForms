<?php

function array_as_text_list($array_in) {
    switch (count($array_in)) {
        case 0:
            return "";
            break;
        case 1:
            return array_pop($array_in);
            break;
        case 2:
            return array_shift($array_in) . " and " . array_shift($array_in);
            break;
        default:
            //Oxford comma FTW
            return implode(", ", array_slice($array_in, 0, -1)) . ", and " . array_pop($array_in);
    }
};

function test_array_as_text_list() {
    //NB: doesn't test with non-numerical indices.
    assert ("" == array_as_text_list(array()));
    assert ("one" == array_as_text_list(array('one')));
    assert ("one and two" == array_as_text_list(array('one','two')));
    assert ("one, two, and three" == array_as_text_list(array('one','two','three')));
    assert ("one, two, three, and four" == array_as_text_list(array('one', 'two', 'three', 'four')));
    return TRUE;
}



function table_keyval($label, $value, $columns=2) {
    if ($columns == 2) {
        $html  = "";
        $html .= "    <tr class='even'>\n";
        $html .= "        <td>\n";
        $html .= "            <strong>{$label}</strong>\n";
        $html .= "        </td>\n";
        $html .= "        <td>\n";
        $html .= "            {$value}\n";
        $html .= "        </td>\n";
        $html .= "    </tr>\n";
    } elseif ($columns == 1) {
        $html  = "";
        $html .= "    <tr>\n";
        $html .= "        <td colspan=2 class='odd'>\n";
        $html .= "            <strong>{$label}</strong>\n";
        $html .= "        </td>\n";
        $html .= "    </tr>\n";
        $html .= "    <tr>\n";
        $html .= "        <td colspan=2 class='even'>\n";
        $html .= "            {$value}\n";
        $html .= "        </td>\n";
        $html .= "    </tr>\n";
    }
    return $html;
}

function test_table_keyval() {
    assert(table_keyval("test_key", "test_value") ==
        "    <tr class='even'>\n" .
        "        <td>\n" .
        "            <strong>test_key</strong>\n" .
        "        </td>\n" .
        "        <td>\n" .
        "            test_value\n" .
        "        </td>\n" .
        "    </tr>\n"
    );
    assert(table_keyval("test_key", "test_value", 1) ==
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
    return TRUE; 
}

function run_tests() {
    test_table_keyval();
    test_array_as_text_list();
}

?>
