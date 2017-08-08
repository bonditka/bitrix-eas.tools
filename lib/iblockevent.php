<?php
/**
 * Description of iblockmain
 *
 * @author BONDitka
 */
namespace Eas\Tools;

use Bitrix\Main\Loader as Loader;

class iblockevent {
    public static function OnAfterIBlockElementAdd($arFields){
        if(!Loader::includeModule("iblock")){
            return;
        }
        /*$iblockMain = \Eas\Tools\iblockmain::get();
        //добавляем почтовое событие форме ""
        if($arFields['IBLOCK_ID'] == $iblockMain->getIblockIdByCode('store_review')){
            $arMailFields = Array(
                'NAME' => $arFields['NAME'],
                'CONTACT' => $arFields['PROPERTY_VALUES'][26],
                'TEXT' => $arFields['PREVIEW_TEXT']
             );
             \CEvent::Send('FEEDBACK_FORM_STORE', SITE_ID, $arMailFields);
        }*/
    }

    public static function agentExample(){
        if(!Loader::includeModule("iblock")){
            return;
        }


        return '\Eas\Tools\iblockevent::agentExample();';
    }

}