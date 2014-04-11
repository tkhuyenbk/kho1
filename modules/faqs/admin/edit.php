<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:32:04 AM
 */

if (!defined('NV_IS_FILE_ADMIN'))
 die('Stop!!!');

if (defined('NV_EDITOR'))
{
 require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
}

$listcats = nv_catList();
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$qid = $nv_Request->get_int('qid', 'get,post', 0);
$error = '';
$f = false;
if ($nv_Request->isset_request('save', 'post'))
{
 $array['title'] = $nv_Request->get_title( 'title', 'post', '' );
 $array['qid'] = $nv_Request->get_int('qid', 'post', 0);
 $array['catid'] = $nv_Request->get_int('catid', 'post', 0);
 
 $array['sendmail'] = $nv_Request->get_int('sendmail', 'post', 0);
 $array['question'] = $nv_Request->get_textarea( 'question', '', NV_ALLOWED_HTML_TAGS );
 $array['full_name'] = $nv_Request->get_title( 'full_name', 'post', '' );
 $array['email'] = $nv_Request->get_title( 'email', 'post', '' );

 $check_valid_email = nv_check_valid_email($array['email']);
 $array['alias'] = change_alias($array['title']);

 if (empty($array['title']))
 {
 $error = $lang_module['error_title'];
 }
 elseif (empty($array['question']))
 {
 $error = $lang_module['error_question'];
 }
 elseif (empty($array['full_name']))
 {
 $error = $lang_module['error_full_name'];
 }
 elseif (!empty($check_valid_email))
 {
 $error = $check_valid_email;
 }

 if ($error == '')
 {
 $s = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET 
 `alias`=" . $db->quote($array['alias'] . "-" . $array['qid']) . ", 
 `cus_name` = " . $db->quote($array['full_name']) . ",
 `cus_email` = " . $db->quote($array['email']) . ",
 `title` = " . $db->quote($array['title']) . ",
 
 `catid`=" . $array['catid'].",
 `question` = " . $db->quote($array['question']) . " 
 WHERE `qid`=" . $array['qid'];
 if ($db->query($s))
 {
 $f = true;
 }
 else
 {
 $error = $lang_module['edit_nocom'];
 }
 }
}
if ($qid != 0)
{
 $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_name . "_question` WHERE qid =" . $qid;
 $result = $db->query($sql);
 if ($db->sql_numrows($result) > 0)
 {
 $xtpl = new XTemplate("edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
 $xtpl->assign('LANG', $lang_module);
 $xtpl->assign('link_module', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_data);
 $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

 while ($rows = $db->sql_fetchrow($result))
 {
 $post = $rows;

 $xtpl->assign('CONTENT', $rows);
 }

 if ($error != '')
 {
 $xtpl->assign('error', $error);
 $xtpl->parse('main.error');
 }
 if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor'))
 {
 $_cont = nv_aleditor('question', '100%', '200px', $post['question']);
 }
 else
 {
 $_cont = "<textarea style=\"width:100%;height:200px\" name=\"question\" id=\"question\">" . $post['question'] . "</textarea>";
 }

 $xtpl->assign('HTMLQS', $_cont);
 

 foreach ($listcats as $cat)
 {
 $cat['selected'] = ($cat['id'] == $post['catid']) ? 'selected="selected"' : '';
 $xtpl->assign('LISTCATS', $cat);
 $xtpl->parse('main.catid');
 }
 $xtpl->parse('main');
 $contents = $xtpl->text('main');

 }
}
if ($f == 1)
{
 $contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['edit_com'] . '</span>';
 $nv_redirect = !empty($nv_redirect) ? nv_base64_decode($nv_redirect) : NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
 $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>