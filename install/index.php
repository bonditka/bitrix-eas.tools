<?
global $MESS;

use \Bitrix\Main\Loader as Loader;
use \Bitrix\Main\Localization\Loc;

use \Bitrix\Highloadblock as HL;

Loc::loadMessages(__FILE__);

if (class_exists("eas_tools")) return;

Class eas_tools extends CModule{
    var $MODULE_ID = "eas.tools";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $MODULE_GROUP_RIGHTS = "Y";
    var $errors = array();

    public function __construct(){
        $arModuleVersion = array();

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path.'/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
                $this->MODULE_VERSION = $arModuleVersion['VERSION'];
                $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->PARTNER_NAME="Сергеева Екатерина";
        $this->PARTNER_URI="http://es-website.ru/";

        $this->MODULE_NAME = GetMessage("EAS_TOOLS_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("EAS_TOOLS_MODULE_DESCRIPTION");
    }

    function DoInstall(){
        RegisterModule($this->MODULE_ID);;
    }

    function DoUninstall(){
        UnRegisterModule($this->MODULE_ID);
    }
}
