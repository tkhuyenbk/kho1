<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:11:28 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN'))
    die('Stop!!!');

//$submenu['add'] = $lang_module['addquestion'];
//$submenu['cat'] = $lang_module['cat'];
//$submenu['duyetque'] = $lang_module['duyetque'];
//$submenu['duyetan'] = $lang_module['duyetan'];
//$submenu['config'] = $lang_module['config'];

$allow_func = array('main', 'config', 'cat', 'detail', 'edit', 'duyetque', 'duyetan', 'de', 'down', 'add');

$arr_config = array();
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_config ";
$result = $db->query($sql);

while ($r = $result->fetch())
{
    $arr_config[$r['config_name']] = $r['config_value'];
}

global $arr_status;

$arr_status = array('1' => array('id' => '1', //
    'name' => $lang_module['dis_sta0']), '2' => array('id' => '2', //
    'name' => $lang_module['dis_sta1']), '3' => array('id' => '3', //
    'name' => $lang_module['dis_sta2']));

/**
 * nv_setcat1()
 *
 * @param mixed $list2
 * @param mixed $id
 * @param mixed $list
 * @param integer $m
 * @param integer $num
 * @return
 */
function nv_catList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY weight ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch())
    {
        $list[$row['id']] = array(//
            'id' => $row['id'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => ( int )$row['weight'] //
        );
    }

    return $list;
}

function fix_catWeight()
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY `weight` ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $db->sql_fetchrow($result))
    {
        $weight++;
        $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
        $db->query($query);
    }
}

function nv_questionList()
{
    global $db, $module_data, $db_config;
    $listcat = nv_catList();
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_question` ORDER BY `addtime` DESC";

    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch())
    {

        $list[$row['qid']] = array(//
            'qid' => $row['qid'], //
            'cattitle' => $listcat[$row['catid']]['title'], //
            'userid' => $row['userid'], //
            'cus_name' => $row['cus_name'], //
            'addtime' => nv_date('d.n.Y, H:i', $row['addtime']), //
            'cus_email' => $row['cus_email'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'question' => $row['question'], //
            'sendmail' => $row['sendmail'], //
            'answer' => $row['answer'], //

            'status' => $row['status'] //
        );
    }
    return $list;
}

function nv_Province()
{
    global $db, $module_data;

    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_location_province` WHERE `status`=1 ORDER BY `weight` ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch())
    {
        $list[$row['id']] = array(//
            'id' => $row['id'], 'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => ( int )$row['weight'] //
        );
    }

    return $list;
}

function nv_District()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_location_district WHERE status=1 ORDER BY weight ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch())
    {
        $list[$row['id']] = array(//
            'idprovince' => $row['idprovince'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => ( int )$row['weight'] //
        );
    }

    return $list;
}

function nv_Districts($proid)
{
    global $db, $module_data;

    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_location_district` WHERE status=1 AND idprovince=" . $proid . " ORDER BY `weight` ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch())
    {
        $list[$row['id']] = array(//
            'id' => $row['id'], 'idprovince' => $row['idprovince'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => ( int )$row['weight'] //
        );
    }

    return $list;
}

define('NV_IS_FILE_ADMIN', true);
?>