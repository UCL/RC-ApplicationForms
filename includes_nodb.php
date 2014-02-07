<?php
    include "db_settings.php";

    function get_consortia() {
        $consortia = array(
            array(
                "id" => 1,
                "full_name" => "Ham Studies",
                "short_name" => "hams",
            ),
            array(
                "id" => 2,
                "full_name" => "Logology",
                "short_name" => "logos",
            ),
        );
        return $consortia;
    
    }

    function options_from_consortia() {
        $options = "";
        $consortia = get_consortia();
        foreach ($consortia as $consortium) {
            $options .= "<option value=\"{$consortium['id']}\">{$consortium['full_name']}</option>\n";
        }
        return $options;
    }

?>
 
