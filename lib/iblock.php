<?php
/**
 * Description of iblock
 *
 * @author BONDitka
 */
namespace Eas\Tools;

use Bitrix\Main\Loader as Loader;

class iblock {
    private $iblockId = false;

    protected static $_instance;

    private function __construct(){}
    private function __wakeup() {}
    private function __clone(){}

    public static function get() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getIblockIdByCode($iblockCode){
        if(!isset($this->iblockId[$iblockCode])){
            if(!Loader::includemodule("iblock")){
                return false;
            }
            $res = \CIBlock::GetList(
                    Array(),
                    Array(
                        'ACTIVE'=>'Y',
                        "CODE"=>$iblockCode
                    )
            );
            $ar_res = $res->Fetch();
            $this->iblockId[$iblockCode] = $ar_res['ID'];
        }
        return $this->iblockId[$iblockCode];
    }
}