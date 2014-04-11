<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if (!defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$page_title = $lang_module['detail_question'];
$listquestion = nv_questionList();

if ($nv_Request->isset_request('del', 'post'))
{
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $id = $nv_Request->get_int('id', 'post', 0);
    $qid = $nv_Request->get_int('qid', 'post', 0);

    if (!$id)
        die('NO');

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_answer` WHERE `id`=" . $id;
    $db->query($sql);

    die('OK');

}

if ($nv_Request->isset_request('duyet', 'post'))
{
    if (!defined('NV_IS_AJAX'))
        die('Wrong URL');

    $id = $nv_Request->get_int('id', 'post', 0);
    $qid = $nv_Request->get_int('qid', 'post', 0);
    $nv_que = nv_questionList();
    if (!$id)
        die('NO');

    $query = "SELECT `qid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_answer` WHERE `id`=" . $id . " AND qid = " . $qid;

    $result = $db->query($query);
    $numrows = $result->rowCount();

    if ($numrows > 0)
    {

        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_answer` SET `status`= 1 WHERE `id`=" . $id;
        $db->query($sql);

        if ($arr_config['is_mark'] == 1)
        {

            $db->query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_note` SET `anser`=anser+1, `mark`=mark+" . $arr_config['mark_an_add'] . " WHERE `userid`=" . $nv_que[$id]['userid']);
        }
        die('OK');

    }
    else
    {
        die($lang_module['nod']);
    }

}

if ($nv_Request->get_int('listaction', 'post'))
{

    $listid = $nv_Request->get_string('listid', 'post', 0);

    $nv_que = nv_questionList();

    $doaction = $nv_Request->get_string('do', 'post', 0);

    if ($doaction == "delete")
    {

        $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_answer` WHERE `id` in (" . $listid . ")";

        if ($db->query($sql))
        {
            die('OK');
        }

    }
    else
    if ($doaction == "duyet")
    {
        $arr_id = explode(",", $listid);

        foreach ($arr_id as $id_i)
        {
            $db->query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_answer` SET `status`= 1 WHERE `id`=" . $id_i);

            if ($arr_config['is_mark'] == 1)
            {

                $db->query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_note` SET `anser`=anser+1, `mark`=mark+" . $arr_config['mark_an_add'] . " WHERE `userid`=" . $nv_que[$id_i]['userid']);
            }
        }
        die('OK');

    }

}

$qid = $nv_Request->get_int('qid', 'get', 0);
$id = $nv_Request->get_int('id', 'get', 0);

$thu2_mail = '';
$query = "SELECT `answer` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_question` WHERE `qid`=" . $qid;
$result = $db->query($query);
$numrows = $result->rowCount();
$array_list_action = array('delete' => $lang_global['delete'], 'duyet' => $lang_module['duyet']);
/*
 $sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_order` SET view=view+1 WHERE id=" . $id;
 $db->query( $sql );
 */
if ($listquestion[$qid]['answer'] == 0)
{
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET status=2 WHERE qid=" . $qid;
    $db->query($sql);
}
$f = false;

if (!empty($listquestion[$qid]))
{
    $html = $error = $img = '';
    $xtpl = new XTemplate("detail.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('link_module', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_data);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TABLE_CAPTION', $lang_module['detail_question']);
    $xtpl->assign('OP', $op);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?");
    $xtpl->assign('ORDER', $listquestion[$qid]);

    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('FILES_DIR', NV_UPLOADS_DIR . '/' . $module_name);
    $xtpl->assign('TABLE_EDIT', $lang_module['edit_an']);
    $xtpl->assign('ACTION_FILE', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&op=detail&qid=" . $qid);
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_answer` WHERE qid =" . $qid;
    $re = $db->query($sql);
    $num = 0;
    if ($re->rowCount())
    {
        $i = 0;
        while ($row = $re->fetch())
        {
            $i = $i + 1;
            $row['i'] = $i;
            if ($row['file'] != '')
            {
                $file2 = NV_UPLOADS_DIR . '/' . $module_data . "/" . $row['file'];

                if (file_exists(NV_ROOTDIR . '/' . $file2) and ($filesize = filesize(NV_ROOTDIR . '/' . $file2)) != 0)
                {
                    $alias = change_alias($row['file']);
                    $new_name = str_replace("-", "_", $alias);
                    $row['links'] = $file2;
                    $row['titles'] = $row['file'];
                    $session_files['fileupload'][$new_name] = array('qid' => $row['id'], 'src' => NV_ROOTDIR . '/' . $file2);
                }
            }
            $row['addtime'] = nv_date('d.m.Y H:i', $row['addtime']);
            $row['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=detail&qid=" . $row['qid'] . "&id=" . $row['id'];
            $xtpl->assign('ROW', $row);

            if ($row['file'] != '')
            {
                $xtpl->parse('main.an.phanhoi.files');
            }
            if ($row['status'] == 0)
            {
                $xtpl->parse('main.an.phanhoi.duyet');
            }
            $xtpl->parse('main.an.phanhoi');
        }

        $num = $i;
        $xtpl->assign('num', $num);
        while (list($catid_i, $title_i) = each($array_list_action))
        {
            $xtpl->assign('key_action', $catid_i);
            $xtpl->assign('value_action', $title_i);
            $xtpl->parse('main.an.action');
        }
        $xtpl->parse('main.an');

    }
    if ($id == 0)
    {
        if (defined('NV_EDITOR'))
        {
            require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
        }
        if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor'))
        {
            $_cont = nv_aleditor('bodytext', '100%', '200px', $html);
        }
        else
        {
            $_cont = "<textarea style=\"width:100%;height:200px\" name=\"bodytext\" id=\"bodytext\">" . $html . "</textarea>";
        }
        $xtpl->assign('CONTENT', $_cont);
        $xtpl->parse('main.nophanhoi');
    }
    $arr_imgs = array();

    //$fileupload_num = count( $arr_img );

    if ($nv_Request->get_int('id', 'get'))
    {

        $id = $nv_Request->get_int('id', 'get', 0);
        $xtpl->assign('ACTION_FILE', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&op=detail&qid=" . $qid . "&id=" . $id);
        $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_answer` WHERE id =" . $id;
        $re = $db->query($sql);
        $arr = $re->fetch();
        $html1 = $arr['answer'];

        if (defined('NV_EDITOR'))
        {
            require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
        }
        if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor'))
        {
            $_cont = nv_aleditor('ans', '100%', '200px', $html1);
        }
        else
        {
            $_cont = "<textarea style=\"width:100%;height:200px\" name=\"ans\" id=\"ans\">" . $html1 . "</textarea>";
        }
        $xtpl->assign('CONTENT', $_cont);

        $xtpl->parse('main.edit');

    }
    if ($nv_Request->isset_request('gui', 'post'))
    {
        $html = $nv_Request->get_editor('ans', '', NV_ALLOWED_HTML_TAGS);
        $file = $nv_Request->get_string('fileupload', 'post', '');
        $id = $nv_Request->get_int('id', 'get', 0);
        $qid = $nv_Request->get_int('qid', 'get', 0);

        $cut = strlen(NV_BASE_SITEURL . "uploads/" . $module_name . "/");
        if ($file != '')
        {
            $file = substr($file, $cut, strlen($file));
        }

        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_answer` SET
	 		`file` = " . $db->quote($file) . ",
	 		`answer` = " . $db->quote($html) . "
	 	WHERE id =" . $id;

        if ($db->query($sql))
        {
            Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detail&qid=" . $qid);
            exit();
        }

    }
    if ($nv_Request->isset_request('send', 'post'))
    {

        $html = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
        $file = $nv_Request->get_string('fileupload', 'post', '');
        $cut = strlen(NV_BASE_SITEURL . "uploads/" . $module_name . "/");
        if ($file != '')
        {
            $file = substr($file, $cut, strlen($file));
        }
        if ($html == '')
        {
            $error = $lang_module['error_loi'];
        }
        if ($error == '')
        {

            $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_answer` VALUES (
					NULL," . $qid . "," . $admin_info['userid'] . ",				
					" . $db->quote($html) . ",		
					" . $db->quote($admin_info['full_name']) . ",
					" . $db->quote($admin_info['email']) . ",			
					" . NV_CURRENTTIME . "," . $db->quote($file) . ",1										
					)";

            if ($db->query($sql))
            {
              
                $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET answer=1, status = 3 WHERE qid=" . $qid;
                $db->query($sql);

               /// $sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_note` where `userid` =" . $admin_info['userid'];
              //  $re = $db->query($sql);
             //   if ($re->rowCount() == 0)
            //    {
            //        $db->query("INSERT `" . NV_PREFIXLANG . "_" . $module_data . "_note` VALUES (NULL," . $admin_info['userid'] . ",0,0,0,0." . $arr_config['mark_start'] . ")");
           //     }
//
               // $db->query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_note` SET `anser`=anser+1, `mark`=mark+" . $arr_config['mark_an_add'] . " WHERE `userid`=" . $admin_info['userid']);

               
                Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detail&qid=" . $qid);
                exit();
            }

            else
            {
                $error = $lang_module['nocomplate'];
            }

        }

    }

    if ($error != '')
    {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

}
else
{
    $contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['no_data'] . '</span>';
    $nv_redirect = !empty($nv_redirect) ? nv_base64_decode($nv_redirect) : NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
}


include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>