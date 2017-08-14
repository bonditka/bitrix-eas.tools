<?php
/**
 * Description of hl
 *
 * @author bonditka
 */
namespace Eas\Tools;

use Bitrix\Highloadblock as bhl;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader as Loader;

/**
 * Description of hlinfoblockMain
 *
 * @author BONDitka
 */
class hl {
    private $iblockId = false;

    protected static $_instance;

    private function __construct(){}
    private function __wakeup() {}
    private function __clone(){}

    /*
     * @return Singletone
     */
    public static function get() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getBlockIdByCode($blockCode){
        if(!isset($this->iblockId[$blockCode])){
            if(!Loader::includeModule("highloadblock")){
                return;
            }

            $rsData = bhl\HighloadBlockTable::getList(array('filter'=>array('NAME'=>$blockCode)));
            $arData = $rsData->fetch();
            $this->iblockId[$blockCode] = $arData['ID'];
        }
        return $this->iblockId[$blockCode];
    }

    public static function getDataFromBlock($hlblock_, $params = array()){
        $requiredModules = array('highloadblock');

        foreach ($requiredModules as $requiredModule)
        {
                if (!Loader::includeModule($requiredModule))
                {
                        ShowError(GetMessage("F_NO_MODULE"));
                        return 0;
                }
        }

        if((int)$hlblock_>0){
            $hlblock_id = $hlblock_;
        }
        else{
            $hlblock_id = hl::get()->getBlockIdByCode($hlblock_);
        }

        $hlblock = bhl\HighloadBlockTable::getById($hlblock_id)->fetch();

        $entity = bhl\HighloadBlockTable::compileEntity($hlblock);
        $main_query = new Entity\Query($entity);
        $main_query->setSelect($params['select']?$params['select']:array('*'));

        if($params['filter']){
            $main_query->setFilter($params['filter']);
        }
        if($params['group']){
            $main_query->setGroup($params['group']);
        }
        if($params['order']){
            $main_query->setOrder($params['order']);
        }
        if($params['options']){
            $main_query->setOptions($params['options']);
        }
        if($params['select']){
            $main_query->setSelect($params['select']);
        }


        $result = $main_query->exec();
        $result = new \CDBResult($result);

        while ($ret = $result->Fetch()){
            /*if($hlblock['NAME'] == 'TableSize'){
                textMain::echopre($ret, '$ret');
            }*/

            if(array_key_exists('resultArrayKey', $params) and $ret[$params['resultArrayKey']]){
                if(array_key_exists('isInnerArray', $params) && $params['isInnerArray'] == 'Y'){
                    $data[$ret[$params['resultArrayKey']]][] = $ret;
                }
                else{
                    $data[$ret[$params['resultArrayKey']]] = $ret;
                }
            }
            elseif($ret['ID']){
                if(array_key_exists('isInnerArray', $params) && $params['isInnerArray'] == 'Y'){
                    $data[$ret['ID']][] = $ret;
                }
                else{
                    $data[$ret['ID']] = $ret;
                }
            }
            else{
                $data[] = $ret;
            }
        }
        return $data;
    }

    public static function add($hlblock_, $arAdd){
        if(!isset($arAdd) || empty($arAdd) || !is_array($arAdd)){
            return false;
        }
        if((int)$hlblock_>0){
            $hlblock_id = $hlblock_;
        }
        else{
            $hlblock_id = hl::get()->getBlockIdByCode($hlblock_);
        }
        $requiredModules = array('highloadblock');

        foreach ($requiredModules as $requiredModule)
        {
                if (!Loader::includeModule($requiredModule))
                {
                        ShowError(GetMessage("F_NO_MODULE"));
                        return 0;
                }
        }

        $hlblock = bhl\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = bhl\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $result = $entity_data_class::add($arAdd);

        if (!$result->isSuccess()){
            return false;
        }

        return $result->getId();
    }


    public static function update($hlblock_, $id, $arUpdate){
        if(!isset($arUpdate) || empty($arUpdate) || !is_array($arUpdate)){
            return false;
        }
        if((int)$hlblock_>0){
            $hlblock_id = $hlblock_;
        }
        else{
            $hlblock_id = hl::get()->getBlockIdByCode($hlblock_);
        }
        $requiredModules = array('highloadblock');

        foreach ($requiredModules as $requiredModule)
        {
                if (!Loader::includeModule($requiredModule))
                {
                        ShowError(GetMessage("F_NO_MODULE"));
                        return 0;
                }
        }

        $hlblock = bhl\HighloadBlockTable::getById($hlblock_id)->fetch();
        $entity = bhl\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $result = $entity_data_class::update($id, $arUpdate);

        if (!$result->isSuccess()){
            return false;
        }

        return true;
    }
}