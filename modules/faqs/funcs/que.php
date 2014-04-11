<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_FAQS' ) ) die( 'Stop!!!' );
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$per_page =25;
$page = $nv_Request->get_int( 'page', 'get', 0 );
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&op=" . $op;

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_name . "_question` WHERE status != 0 AND userid = ".$user_info['userid']." ORDER BY `addtime` DESC LIMIT " . $page . ", " . $per_page;
$result = $db->query( $sql );
$query = $db->query( "SELECT FOUND_ROWS()" );
$all_page = $query->fetchColumn();

$array = array();
while ( $rows = $result->fetch() )
{	
 $array[$rows['qid']] = array( 
 'title' => $rows['title'], //
		'cus_name' => $rows['cus_name'], //
		'cus_email' => $rows['cus_email'], //
		'question' => $rows['question'], //
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=detail/" . $rows['alias'], //
		'addtime' => nv_date( 'd.m.Y, H:i', intval($rows['addtime'] )) //				
 );
}
//print_r($array);die();

$generate_page = x_generate_page( $base_url, $all_page, $per_page, $page );
if ( ! empty( $array ) )
{
 $xtpl = new XTemplate( "que.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
 $xtpl->assign( 'LANG', $lang_module );
 $xtpl->assign( 'que', $lang_module['numque'] );
 $xtpl->assign( 'LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_file . "&amp;op=question" );
 foreach ( $array as $key => $a )
 {
 $xtpl->assign( 'ROW', $a );
 $xtpl->parse( 'main.que' );
 }
 if ( ! empty( $generate_page ) )
 {
 $xtpl->assign( 'PAGE', $generate_page );
 $xtpl->parse( 'main.page' );
 }
 $xtpl->parse( 'main' );
 $contents = $xtpl->text( 'main' );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>