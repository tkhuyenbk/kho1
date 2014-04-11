<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM 
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );


$page_title = $lang_module['duyetan'];
//$cus_list = nv_cusList();
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_answer` WHERE `id`!=0 AND `status` = 0 ";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name."&op=duyetan";
$error = '';
$sql1 = "SELECT COUNT(*) " . $sql;

$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

if ( ! $all_page )
{
 $error = 'Không có câu hỏi cần duyệt';
}

$sql .= " ORDER BY `addtime` DESC";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;

$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->query( $sql2 );

$array = array();
$i = 0;
while ( $row = $query2->fetch() )
{
 if (strlen($row['answer']) > 100)
 {
 	$row['answer'] = nv_clean60($row['answer'],100);
 }
 
	$i = $i + 1;
 $array[$row['id']] = array( //
 'id' => $row['id'],//
 	'qid' => $row['qid'],//
 	'userid' => $row['userid'],//
 'addtime' => nv_date( 'd.n.Y, H:i', $row['addtime'] ),//
		'status' => $row['status'], // 
 	'answer' => $row['answer'], // 
		'sort' => $i, //
		'cus_name' => $row['cus_name'],// 
		'cus_email' =>$row['cus_email'], //		
		'detail_url' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=detail&qid=" . $row['qid']	
 	
 );
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "duyetan.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'TABLE_CAPTION', $lang_module['duyetan'] );
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