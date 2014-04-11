<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM 
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

//Delete link
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
 if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
 
 $id = $nv_Request->get_int( 'id', 'post', 0 );
 
 if ( ! $id ) die( 'NO' );
 
 $query = "SELECT qid FROM " . NV_PREFIXLANG . "_" . $module_data . "_question WHERE qid=" . $id;
 $result = $db->query( $query );
 $numrows = $result->rowCount();
 
 if ( $numrows > 0 )
 {
 $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_question WHERE qid=" . $id;
 $db->query( $sql );
 $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_answer WHERE qid=" . $id;
 $db->query( $sql );
 
 } 
 die( 'OK' );

}

//Chinh trang thai
if ( $nv_Request->isset_request( 'changesta', 'post' ) )
{
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
 
 $id = $nv_Request->get_int( 'id', 'post', 0 );
 $new = $nv_Request->get_int( 'new', 'post', 0 );
 
 if ( empty( $id ) ) die( 'NO' );
 
 $query = "SELECT `qid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_question` WHERE `qid`=" . $id;
 
 $result = $db->query( $query );
 $numrows = $result->rowCount();
 if ( $numrows == 0 ) die( 'NO' ); 
 $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET `status`=" . $new . " WHERE `qid`=" . $id; 
 $db->query( $sql );
 
 nv_del_moduleCache( $module_name );
 
 die( 'OK' );
}

$page_title = $lang_module['list_order'];
//$cus_list = nv_cusList();
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_question` WHERE `qid`!=0 AND `status` != 0 ";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
$error = $code = '';
$from = $to = $status = 0;
if ( $nv_Request->isset_request( "status", "get" ) )
{
 
 $status = $nv_Request->get_int( 'status', 'get', 0 );
 if ( $status > 0 )
 {
 $sql .= " AND `status`=" . $status;
 $base_url .= "&amp;status=" . $status;
 }
}

if ( $nv_Request->isset_request( "from", "get" ) )
{
 $from = $nv_Request->get_title( 'from', 'get,post', '' );
 
 unset( $m );
 if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $from, $m ) )
 {
 $from = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
 }
 else
 {
 $from = 0;
 }
 
 if ( $from != 0 )
 {
 //die($year.'');
 

 $sql .= " AND `addtime` >= " . $from;
 $base_url .= "&amp;from =" . $from;
 }
}
if ( $nv_Request->isset_request( "to", "get" ) )
{
 $to = $nv_Request->get_title( 'to', 'get,post', '' );
 
 unset( $m );
 if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $to, $m ) )
 {
 $to = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
 }
 else
 {
 $to = 0;
 }
 if ( $to != 0 )
 {
 //die($year.'');
 

 $sql .= " AND `addtime` <= " . $to;
 $base_url .= "&amp;to=" . $to;
 }
}


$sql1 = "SELECT COUNT(*) " . $sql;

$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

if ( ! $all_page )
{
 $error = 'Không có dữ liệu như bạn tìm';
}

$sql .= " ORDER BY addtime DESC";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;

$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->query( $sql2 );

$array = array();
$i = 0;
while ( $row = $query2->fetch() )
{
 $i = $i + 1;
 $array[$row['qid']] = array( //
 'qid' => $row['qid'],//
 'addtime' => nv_date( 'd.n.Y, H:i', $row['addtime'] ),//
		'status' => $row['status'], // 
 	'title' => $row['title'], // 
		'sort' => $i, //
		'cus_name' => $row['cus_name'],// 
		'cus_email' =>$row['cus_email'], //		
		'detail_url' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=detail&qid=" . $row['qid'],//		
 	'edit_url' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=edit&qid=" . $row['qid'],//
 );
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'TABLE_CAPTION', $lang_module['list_order'] );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );

if ( ! empty( $array ) )
{
 foreach ( $array as $a )
 {
 foreach ( $arr_status as $k => $v )
 {
 $v['selected'] = ( $k == $a['status'] ) ? 'selected="selected"' : '';
 $xtpl->assign( 'STATUS', $v );
 $xtpl->parse( 'main.loop.status' );
 }
 $a['class'] = ( ( $a['sort'] % 2 == 0 ) ? " class=\"second\"" : "" );
 
 $xtpl->assign( 'ROW', $a );
 $xtpl->parse( 'main.loop' );
 }

}

if ( $error != '' )
{
 $xtpl->assign( 'ERROR', $error );
 $xtpl->parse( 'main.error' );
}

foreach ( $arr_status as $a )
{
 $a['selected'] = ( $a['id'] == $status ) ? 'selected="selected"' : ''; 
 $xtpl->assign( 'OPTION3', $a );
 $xtpl->parse( 'main.psopt3' );
}
if ( $from != 0 )
{
 $xtpl->assign( 'from', nv_date("d.n.Y",$from ));
}
if ( $to != 0 )
{
 $xtpl->assign( 'to', nv_date("d.n.Y",$to) );
}
if ($code !='')
{
	$xtpl->assign( 'code', $code);
}
if ( ! empty( $generate_page ) )
{
 $xtpl->assign( 'GENERATE_PAGE', $generate_page );
 $xtpl->parse( 'main.generate_page' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>