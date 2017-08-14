<?php
/**
 * Description of stringHelper
 *
 * @author BONDitka
 */
namespace Eas\Tools;

class stringHelper {

    /*
     * remove any empty tag
     * return string
     */
    public static function stripEmptyTag($html){
        return preg_replace("/<[^\/>]*>([\s]?[\&nbsp;]?)*<\/[^>]*>/", '', $html);
    }

}