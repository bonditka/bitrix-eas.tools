<?
use Eas\Tools as EasTools;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/options.php');
Loc::loadMessages(__FILE__);

$module_id = "eas.tools";
if (!Loader::includeModule($module_id)){
    echo '<br/>'.Loc::getMessage('EAS_TOOLS_MODULE_NOT_INSTALL');
    return;
}


$POST_RIGHT = $APPLICATION->GetGroupRight('main');

if ($POST_RIGHT < 'R'){
    return;
}
$arAllOptions = array(
    array('sitename', GetMessage('EAS_TOOLS_OPTION_SITENAME').':', array('text', 100)),
    array('groupheading', GetMessage('EAS_TOOLS_OPTION_CONTACT_HEADER_TITLE')),
    array('mail', GetMessage('EAS_TOOLS_OPTION_MAIL').':', array('text', 100)),
    array('phone', GetMessage('EAS_TOOLS_OPTION_PHONE').':', array('text', 100)),

    array('groupheading', GetMessage('EAS_TOOLS_OPTION_SOCIAL_HEADER_TITLE')),
    array('vk', GetMessage('EAS_TOOLS_OPTION_VK').':', array('text', 100)),
    array('fb', GetMessage('EAS_TOOLS_OPTION_FB').':', array('text', 100)),
    array('instagramm', GetMessage('EAS_TOOLS_INSTAGRAM').':', array('text', 100)),
    array('youtube', GetMessage('EAS_TOOLS_YOUTUBE').':', array('text', 100)),
    array('linkedin', GetMessage('EAS_TOOLS_LINKEDIN').':', array('text', 100)),
);

$tabControl = new CAdmintabControl('tabControl', array(
    array('DIV' => 'edit1', 'TAB' => Loc::getMessage('MAIN_TAB_SET'), 'ICON' => ''),
));

if (ToUpper($REQUEST_METHOD) == 'POST'
    && strlen($Update.$Apply.$RestoreDefaults)>0
    && ($POST_RIGHT=='W' || $POST_RIGHT=='X') && check_bitrix_sessid()){

    if (strlen($RestoreDefaults)>0){
        foreach ($arAllOptions as $arOption){
            Option::delete($module_id, $arOption[0]);
        }
    }
    else{
        foreach ($arAllOptions as $arOption){
            $name = $arOption[0];
            if($name === 'groupheading'){
                continue;
            }
            if ($arOption[2][0]=='text-list'){
                $val = '';
                for ($j=0; $j<count($$name); $j++)
                        if (strlen(trim(${$name}[$j])) > 0)
                                $val .= ($val <> ''? ',':'').trim(${$name}[$j]);
            }
            elseif ($arOption[2][0]=='selectbox'){
                $val = '';
                for ($j=0; $j<count($$name); $j++){
                    if (strlen(trim(${$name}[$j])) > 0){
                        $val .= ($val <> ''? ',':'').trim(${$name}[$j]);
                    }
                }
            }
            else{
                $val = $$name;
            }

            if ($arOption[2][0] == 'checkbox' && $val<>'Y'){
                $val = 'N';
            }
            Option::set($module_id, $name, $val);
        }
    }

    $Update = $Update.$Apply;

    if (strlen($Update)>0 && strlen($_REQUEST['back_url_settings'])>0){
        LocalRedirect($_REQUEST['back_url_settings']);
    }
    else{
        LocalRedirect($APPLICATION->GetCurPage().'?mid='.urlencode($module_id).'&lang='.urlencode(LANGUAGE_ID).'&back_url_settings='.urlencode($_REQUEST['back_url_settings']).'&'.$tabControl->ActiveTabParam());
    }
}

?><form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>"><?
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    foreach($arAllOptions as $Option){
        $type = $Option[2];
        $val = Option::get($module_id, $Option[0]);
        ?><tr><?
            if($Option[0] === 'groupheading'){
                ?><tr class="heading"><?
                    ?><td colspan="2"><?=$Option[1]?></td><?
                ?></tr><?
            }
            else{

                ?><td valign="top" width="30%"><?
                    if ($type[0]=='checkbox'){
                        echo '<label for="'.htmlspecialcharsbx($Option[0]).'">'.$Option[1].'</label>';
                    }
                    else{
                        echo $Option[1];
                    }
                ?></td><?
                ?><td valign="middle" width="70%"><?
                    if ($type[0] == 'checkbox'){
                        ?><input type="checkbox" name="<?echo htmlspecialcharsbx($Option[0])?>" id="<?echo htmlspecialcharsbx($Option[0])?>" value="Y"<?if($val == 'Y')echo ' checked="checked"';?> /><?
                    }
                    elseif ($type[0] == 'text'){
                        ?><input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($Option[0])?>" /><?
                    }
                    elseif ($type[0] == 'textarea'){
                        ?><textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($Option[0])?>"><?echo htmlspecialcharsbx($val)?></textarea><?
                    }
                    elseif ($type[0] == 'text-list'){
                            $aVal = explode(",", $val);
                            for($j=0; $j<count($aVal); $j++):
                                    ?><input type="text" size="<?echo $type[2]?>" value="<?echo htmlspecialcharsbx($aVal[$j])?>" name="<?echo htmlspecialcharsbx($Option[0]).'[]'?>" /><br /><?
                            endfor;
                            for($j=0; $j<$type[1]; $j++):
                                    ?><input type="text" size="<?echo $type[2]?>" value="" name="<?echo htmlspecialcharsbx($Option[0]).'[]'?>" /><br /><?
                            endfor;
                    }
                    elseif ($type[0]=="selectbox"){
                        $arr = $type[1];
                        $arr_keys = array_keys($arr);
                        $arVal = explode(",", $val);
                        ?><select name="<?echo htmlspecialcharsbx($Option[0])?>[]"<?= $type[2]?>><?
                            foreach ($arr_keys as $item){
                                ?><option value="<?=$item?>"<?if(in_array($item, $arVal))echo ' selected="selected"'?>><?echo htmlspecialcharsbx($arr[$item])?></option><?
                            }
                        ?></select><?
                    }
                ?></td><?
            }
}

$tabControl->Buttons();
?><input <?if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="submit" name="Update" value="<?=GetMessage('MAIN_SAVE')?>" title="<?=GetMessage('MAIN_OPT_SAVE_TITLE')?>" /><?
?><input <?if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="submit" name="Apply" value="<?=GetMessage('MAIN_OPT_APPLY')?>" title="<?=GetMessage('MAIN_OPT_APPLY_TITLE')?>" /><?
if (strlen($_REQUEST["back_url_settings"]) > 0){
    ?><input <?if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="button" name="Cancel" value="<?=GetMessage('MAIN_OPT_CANCEL')?>" title="<?=GetMessage('MAIN_OPT_CANCEL_TITLE')?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST['back_url_settings']))?>'" /><?
    ?><input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>" /><?
}
?><input <?if ($POST_RIGHT < 'W') echo 'disabled="disabled"' ?> type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" onclick="confirm('<?echo AddSlashes(GetMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING'))?>')" value="<?echo GetMessage('MAIN_RESTORE_DEFAULTS')?>" /><?
?><?=bitrix_sessid_post();?><?
$tabControl->End();
?></form><?
