<?php
/**
 * Description of textMain
 *
 * @author BONDitka
 */
namespace Eas\Tools;

class formatter {

    /**
    * @param string $str
    * Replace short hyphen to long
    *
    * @return string string
    */
    public static function replaceDash($str){
        return str_replace('-', '–', $str);
    }

    /**
    * @param int $number
    * @param array $titles
    *
    * @return string word
    */
    public static function declension($number, $titles){
           $cases = array(2, 0, 1, 1, 1, 2);
           return $number.' '.$titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }

    /**
    * @param string $phone
    * @param string $mode
    * Prepare string to add in href attribut in link with tel protocol
    *
    * @return string string
    */
    public static function phone($phone, $mode = 'href'){
        if($mode == 'href'){
            return preg_replace('/[^\+\d]/i', '', $phone);
        }
        return  $phone;
    }
    
    public static function getPropNameMeasure($str){
        $arData = explode('#|#', $str);
        return array(
            'name' => textMain::replaceDash($arData[0]),
            'measure' => $arData[1]
        );
    }
}