<?php
/**
 * Description of textMain
 *
 * @author BONDitka
 */
namespace Eas\Tools;

class dump {
    const DUMP = true;
    public static function echopre($arr, $title){
        if(dump::canDump()){
            echo '<pre style="text-align:left;">'.$title.': ';
            print_r($arr);
            echo '</pre>';
        }
    }

    public static function canDump(){
        global $USER;
        return dump::DUMP === true && ((is_object($USER) and $USER->GetID() == 2) || $_SERVER['REMOTE_ADDR'] == '91.122.59.152' || $_SERVER['REMOTE_ADDR'] == '109.172.11.158');
    }
}

?>
