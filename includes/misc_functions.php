<?php

/**
 * Little text output functions that didn't seem to belong anywhere else.
 */

function array_as_text_list($array_in, $conjunction=" and ") {
    switch (count($array_in)) {
        case 0:
            return "";
            break;
        case 1:
            return array_pop($array_in);
            break;
        case 2:
            return array_shift($array_in) . $conjunction . array_shift($array_in);
            break;
        default:
            //Oxford comma FTW
            return implode(", ", array_slice($array_in, 0, -1)) . "," . $conjunction . array_pop($array_in);
    }
};

function table_keyval($label, $value, $columns=2, $preformatted=FALSE, $escape_html=TRUE) {
    if ($escape_html === TRUE) {
        $label = str_replace("\n", "<br />", htmlspecialchars($label));
        $value = str_replace("\n", "<br />", htmlspecialchars($value));
    }
    if ($preformatted === TRUE) { $value = "<pre>" . $value . "</pre>"; }
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

function escape_multiline_input($text) {
    return str_replace("\n", "<br />", htmlspecialchars($text));
}


?>
