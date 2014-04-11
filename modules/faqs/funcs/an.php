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

$xtpl = new XTemplate( "an.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$xtpl->assign( 'LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_file . "&amp;op=question" );
$i = 0;
$per_page = 25;
$page = $nv_Request->get_int( 'page', 'get', 0 );
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&op=" . $op;
$a = array();
$s = "SELECT qid, alias, title FROM `" . NV_PREFIXLANG . "_" . $module_name . "_question` WHERE status != 0 ORDER BY `addtime` DESC";
$re = $db->query( $s );
while ( $r = $re->fetch() )
{
 $a[$r['qid']] = array(
 	'alias' => $r['alias'],
 	'title' => $r['title'] 	
 );
}

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_PREFIXLANG . "_" . $module_name . "_answer` WHERE status=1 AND userid = " . $user_info['userid'] . " ORDER BY `addtime` DESC LIMIT " . $page . ", " . $per_page;
$result = $db->query( $sql );
$query = $db->query( "SELECT FOUND_ROWS()" );
$all_page = $query->fetchColumn();

$array = array();
while ( $rows = $result->fetch() )
{
 $array[$rows['id']] = array( 
 	'id' => $rows['id'],//
 'answer' => $rows['answer'], //
 	'addtime' => $rows['addtime'],//
		'cus_name' => $rows['cus_name'], //
		'cus_email' => $rows['cus_email'], //
		'file' => $rows['file'], //
		'title' => $a[$rows['qid']]['title'],//
 	'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=detail/" . $a[$rows['qid']]['alias'] 
 );
}
//print_r($array);die();


if ( ! empty( $array ) )
{
 foreach ( $array as $k => $v )
 {
 if ( $v['file'] != '' )
 {
 $file2 = NV_UPLOADS_DIR . '/' . $module_data . "/" . $v['file'];
 
 if ( file_exists( NV_ROOTDIR . '/' . $file2 ) and ( $filesize = filesize( NV_ROOTDIR . '/' . $file2 ) ) != 0 )
 {
 $alias = change_alias( $v['file'] );
 $new_name = str_replace( "-", "_", $alias );
 $v['links'] = $file2;
 $v['titles'] = $v['file'];
 
 $session_files['fileupload'][$new_name] = array( 
 'qid' => $v['id'], 'src' => NV_ROOTDIR . '/' . $file2 
 );
 }
 
 }
 
 $v['addtime'] = nv_date( 'd.m.Y H:i', $v['addtime'] );
 
 $xtpl->assign( 'LOOP', $v );
 if ( $v['file'] != '' )
 {
 $xtpl->parse( 'main.an.loop.file' );
 }
 $xtpl->parse( 'main.an.loop' );
 //$xtpl->assign( 'answer', $answer );
 $xtpl->parse( 'main.an' );
 }
 
 $xtpl->parse( 'main' );
 $contents = $xtpl->text( 'main' );

}

else
{
 $contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['nousan'] . '</span>';

}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>