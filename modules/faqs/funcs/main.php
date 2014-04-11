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
$nv_Request->get_int('catids', 'cookie', 0);
$catids = $nv_Request->get_int('catids', 'post', 0);
$catids_old = $nv_Request->get_int('catids', 'cookie', 0);
$catids = $nv_Request->get_int('catids', 'post', $catids_old);
if ($nv_Request->isset_request('changess', 'post'))
{
    $catids = $nv_Request->get_int('catids', 'post', 0);
    $nv_Request->set_Cookie('catids', $catids, NV_LIVE_COOKIE_TIME);
    nv_del_moduleCache($module_name);
    die("OK");
}

//die($catids.'');
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_file . "&amp;op=question");
$base_url = array();
$base_url['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$base_url['amp'] = "/page-";
$listcats = nv_catList();
$per_page = 12;
$page = 0;
unset($matches);
if (isset($array_op[1]) and preg_match("/^page\-([\d]+)$/", $array_op[1], $matches))
{
    $page = ( int )$matches[1];
}
$where = '';
if ($catids != 0)
{
    $where .= " AND catid=" . $catids;
}
else
{
    $where .= " AND catid != 0 ";
}

$db->sqlreset()
    ->select( 'COUNT(*)' )
    ->from( NV_PREFIXLANG . "_" . $module_name . "_question" )
    ->where( "status != 0 " . $where );

$all_page = $db->query( $db->sql() )->fetchColumn();

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_name . "_question WHERE status != 0 " . $where . " ORDER BY addtime DESC LIMIT " . $page . "," . $per_page;
$result = $db->query($sql);
//$res = $db->query("SELECT FOUND_ROWS()");
//$all_page = $db->query( $db->sql() )->fetchColumn();
//$all_page = intval($all_page);
//$all_page =1;
while ($row = $result->fetch())
{
    $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=detail/" . $row['alias'];
    $row['linksua'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=question/" . $row['alias'];
    $row['addtime'] = nv_date("d.m.Y H:i", $row['addtime']);

    $row['titlecat'] = $listcats[$row['catid']]['title'];
    $xtpl->assign('ROW', $row);

    $xtpl->parse('main.row');
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if (!empty($generate_page))
{
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.nv_generate_page');
}
foreach ($listcats as $cat)
{
    $cat['selected'] = ($cat['id'] == $catids) ? 'selected="selected"' : '';

    $xtpl->assign('LISTCATS', $cat);
    $xtpl->parse('main.catid');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>