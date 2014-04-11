<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:12:21 AM
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

$arr_config = array();
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_config ";
$result = $db->query($sql);
while ($r = $result->fetch())
{
    $arr_config[$r['config_name']] = $r['config_value'];
}

function nv_setcats1($list2, $id, $list, $m = 0, $num = 0)
{
    $num++;
    $defis = "";
    for ($i = 0; $i < $num; $i++)
    {
        $defis .= "--";
    }

    if (isset($list[$id]))
    {
        foreach ($list[$id] as $value)
        {
            if ($value['id'] != $m)
            {
                $list2[$value['id']] = $value;
                $list2[$value['id']]['name'] = "|" . $defis . "&gt; " . $list2[$value['id']]['name'];
                if (isset($list[$value['id']]))
                {
                    $list2 = nv_setcats1($list2, $value['id'], $list, $m, $num);
                }
            }
        }
    }
    return $list2;
}

/**
 * nv_listcats()
 *
 * @param mixed $parentid
 * @param integer $m
 * @return
 */
function nv_listcats($parentid, $m = 0)
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY parentid,weight ASC";

    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch())
    {
        if ($row['parentid'] != 0)
        {
            $row['title'] = "&nbsp;&nbsp;" . $row['title'];
        }
        else
        {
            $row['title'] = $row['title'];
        }
        $list[$row['parentid']][] = array(//
            'id' => ( int )$row['id'], //
            'parentid' => ( int )$row['parentid'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'admin' => $row['admin'], //
            'weight' => ( int )$row['weight'], //
            'status' => $row['status'], //
            'listcatid' => $row['listcatid'], //
            'name' => $row['title'], //
            'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : "" //
        );
    }

    if (empty($list))
    {
        return $list;
    }

    $list2 = array();
    foreach ($list[0] as $value)
    {
        if ($value['id'] != $m)
        {
            $list2[$value['id']] = $value;
            if (isset($list[$value['id']]))
            {
                $list2 = nv_setcats1($list2, $value['id'], $list, $m);
            }
        }
    }

    return $list2;
}

function nv_admin_a($select)
{
    global $db, $module_data, $db_config;

    $sql = "SELECT username,email,admin_id FROM `" . $db_config['prefix'] . "_authors as a LEFT JOIN " . $db_config['prefix'] . "_users as u on a.admin_id = u.userid ORDER BY a.admin_id";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch())
    {
        $list[$row['admin_id']] = array(//
            'userid' => $row['admin_id'], //
            'email' => $row['email'], //
            'username' => $row['username'], //
            'selected' => $select == $row['admin_id'] ? "selected='selected'" : "");
    }
    return $list;
}

function taifile($file_src)
{

    global $module_name;

    $upload_dir = "files";
    $max_speed = 0;
    $is_resume = true;
    $file_basename = end(explode("/", $file_src));
    $directory = NV_UPLOADS_REAL_DIR;
    require_once (NV_ROOTDIR . '/includes/class/pclzip.class.php');
    $zip = new PclZip(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $upload_dir . "/" . $pathfile);

    require_once (NV_ROOTDIR . '/includes/class/download.class.php');
    $download = new download($file_src, $directory, $file_basename, $is_resume, $max_speed);

    $download->download_file();

}

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

function nv_questionList()
{
    global $db, $module_data, $db_config;
    $listcat = nv_catList();
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_question ORDER BY addtime DESC";

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

define('NV_IS_MOD_FAQS', true);
?>