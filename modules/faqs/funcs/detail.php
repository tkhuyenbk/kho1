<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:32:04 AM
 */

if (!defined('NV_IS_MOD_FAQS'))
    die('Stop!!!');
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$qid = 0;
if (isset($array_op[1]) and preg_match("/^([a-zA-Z0-9\-\_]+)\-([\d]+)$/", $array_op[1], $matches))
{
    $qid = $matches[2];
    $alias = $matches[0];

}

if (defined('NV_EDITOR'))
{
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
elseif (!nv_function_exists('nv_aleditor') and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js'))
{
    define('NV_EDITOR', true);
    define('NV_IS_CKEDITOR', true);
    $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

    function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '')
    {
        $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
        $return .= "<script type=\"text/javascript\">
        CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {width: '" . $width . "',height: '" . $height . "',});
        </script>";
        return $return;
    }

}

if ($nv_Request->isset_request('bc', 'post'))
{
    $id = $nv_Request->get_int('id', 'post', 0);

    $query = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer WHERE `id`=" . $id;
    $result = $db->query($query);
    $ro_a = $result->fetch();

    if (!empty($ro_a))
    {
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_question SET `most`=" . $ro_a['id'] . " WHERE `qid`=" . $ro_a['qid']);
        if ($arr_config['is_mark'] == 1)
        {
            $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_note SET mark=mark+" . $arr_config['mark_an_cho'] . " , choanser= choanser+1 WHERE `userid`=" . $user_info['userid']);
            $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_note SET mark=mark+" . $arr_config['mark_an_most'] . " , mostanser= mostanser+1 WHERE `userid`=" . $ro_a['userid']);
        }

        die('OK');
    }
    else
    {
        die($lang_module['bc_chon_no']);
    }

}
$hoten = $email = $noidung = '';

function nv_return_post_result($message)
{
    die('<script type="text/javascript">parent.nv_complete(\'' . $message . '\');</script>');
}

if ($nv_Request->get_string('type', 'post', '') == "sendemail")
{

    $noidung = $nv_Request->get_string('answer', 'post', '');
    $id = $nv_Request->get_int('id', 'post', '');
    $email = $nv_Request->get_string('email', 'post', '');
    $hoten = $nv_Request->get_string('full_name', 'post', '');

    $check_valid_email = nv_check_valid_email($email);

    $query = $db->query( "SELECT id, userid FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer where qid=" . $id );
    
    $news_contents = $query->fetch();
    


    if ($news_contents['id'] > 0 )
    {

        $error = $lang_module['error_ansss'];
        die('<script type="text/javascript">
 
 alert(\'' . $error . '\');
 window.location.href = window.location.href ;
 </script>');
    }
    elseif (empty($noidung))
    {
        $error = $lang_module['error_an'];
        die('<script type="text/javascript">
 
 alert(\'' . $error . '\');
 window.location.href = window.location.href ;
 </script>');
    }

    if (!file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_name))
    {
        nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_name);
    }

    $file_name = '';
    /*
     if( $arr_config['duyetan'] == 1 )
     {
     $status = 0;
     }
     else
     {
     $status = 1;
     }
     * */$status = 0;

    $file_name = '';
    $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_answer` (`id` , `qid`, `userid` ,`answer` , `cus_name` ,`cus_email` ,
 `addtime` , `file` ,`status`) 
 VALUES (NULL," . $qid . ",0," . $db->quote($noidung) . "," . $db->quote($hoten) . ", 
 " . $db->quote($email) . ", " . intval(NV_CURRENTTIME) . ", " . $db->quote($file_name) . "," . $status . ")";
    $id_post = $db->query($query);

    if ($id_post)
    {
        $db->query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET `number`=number+1, answer = 1 WHERE `qid`=" . $qid);

        die('<script type="text/javascript"> alert(\'Bạn gửi câu hỏi thành công\' );
		 window.location.href = window.location.href;		
		</script>');

        //die( nv_return_post_result( "OK" ) );
    }
    else
    {
        die(nv_return_post_result($lang_module['no_send']));
    }
}
if ($qid != 0)
{

    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_name . "_question` WHERE qid =" . $qid;
    $result = $db->query($sql);

    if ($result->rowCount() > 0)
    {
        $xtpl = new XTemplate("detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('themes', $module_info['template']);
        if (!empty($user_info))
        {
            $xtpl->assign('hoten', $user_info['full_name']);
            $xtpl->assign('email', $user_info['email']);

        }
        $xtpl->assign('LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_file . "&amp;op=question");

        $htmlbodytext = '';
        if (defined('NV_EDITOR') and function_exists('nv_aleditor'))
        {
            $htmlbodytext .= nv_aleditor('answer', '99%', '150px', $noidung);
        }
        else
        {
            $htmlbodytext .= "<textarea style=\"width:70%;height:150px\" name=\"answer\" id=\"answer\">" . $noidung . "</textarea>";
        }
        $xtpl->assign('HTML_ND', $htmlbodytext);

        // $xtpl->assign( 'noidung', $noidung );

        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=detail&qid=" . $qid);
        while ($rows = $result->fetch())
        {
            $rows['addtime'] = nv_date('d.m.Y', $rows['addtime']);

            $i = 0;

            $xtpl->assign('ROW', $rows);

            if ($rows['showmail'] == 1)
            {
                $xtpl->parse('main.email');
            }

            $s = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_name . "_answer` WHERE status=1 AND qid =" . $qid;
            $re = $db->query($s);

            if ($re->rowCount() > 0)
            {
                while ($ro = $re->fetch())
                {
                    $i = $i + 1;

                    $ro['addtime'] = nv_date('d.m.Y H:i', $ro['addtime']);

                    $xtpl->assign('LOOP', $ro);
                    if ($ro['file'] != '')
                    {
                        $xtpl->parse('main.an.loop.file');
                    }
                    if ($rows['most'] == $ro['id'])
                    {
                        $xtpl->parse('main.an.loop.bchn');
                    }
                    elseif ($rows['most'] == 0 && $rows['userid'] == $user_info['userid'])
                    {
                        if (defined('NV_IS_USER'))
                        {

                            $xtpl->parse('main.an.loop.bc');
                        }
                    }
                    $xtpl->parse('main.an.loop');
                }

                $xtpl->assign('number', $i);
                $xtpl->parse('main.an');
            }

        }
        //$sqls = "SELECT id, userid FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer where userid =" . $user_info['userid'] . " AND qid=" . $qid;
        //$result = $db->query($sqls);
        if ($arr_config['who_an'] == 0)
        {
            $xtpl->parse('main.anss');
        }
        else
        if ($arr_config['who_an'] == 1)
        {
            if (defined('NV_IS_USER'))
            {
                $xtpl->parse('main.anss');
            }
        }
        else
        if ($arr_config['who_an'] == 2)
        {
            if (!defined('NV_IS_ADMIN'))
            {
                $xtpl->parse('main.anss');
            }
        }

        $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_name . "_question` WHERE qid !=" . $qid . " LIMIT 10";
        $result = $db->query($sql);
        while ($rows = $result->fetch())
        {
            $rows['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=detail/" . $rows['alias'];
            $xtpl->assign('LOOP', $rows);
            $xtpl->parse('main.loops');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    }
    else
    {
        $contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['noque'] . '</span>';

    }
}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>