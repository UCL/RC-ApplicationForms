<?php

    function options_from_consortia() {
        global $actor;
        $options = "";
        $consortia = $actor->get_consortia();
        foreach ($consortia as $consortium) {
            $options += "<option value=\"{$consortium['id']}\">{$consortium['full_name']}</option>\n";
        }
        return $options;
    }

?>
 
