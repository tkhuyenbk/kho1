<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_faqs_book' ) )
{

 function nv_faqs_book ( $block_config )
 {
 global $db, $module_array_cat, $module_info, $lang_module, $site_mods, $db;
 $module = $block_config['module'];
 require_once ( NV_ROOTDIR . "/modules/" . $module . "/language/" . NV_LANG_DATA . ".php" );
 
 if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module . "/block_question.tpl" ) )
 {
 $block_theme = $module_info['template'];
 }
 else
 {
 $block_theme = "default";
 }
 
 $xtpl = new XTemplate( "block_question.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module );
 $xtpl->assign( 'LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=question" );
 $xtpl->assign( 'LANG', $lang_module );
 $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
 $xtpl->assign( 'themes', $block_theme );
 $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module . "_question` where status != 0 ORDER BY qid desc limit 15";
 $resutl = $db->query( $sql );
 while ( $rows = $resutl->fetch() )
 {
 $rows['addtime'] = nv_date( 'd.m.Y', $rows['addtime'] );
 if ( strlen( $rows['title'] ) > 80 )
 {
 $rows['title'] = nv_clean60( $rows['title'], 80 );
 }
 
 $rows['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=detail/".$rows['alias'];
 
 
 $sql1 = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module . "_answer` WHERE qid = " . $rows['qid'] . " AND status = 1 limit 15";
 $resutl1 = $db->query( $sql1 );
 $i = 0;
 while ( $row = $resutl1->fetch() )
 {
 
 	if ( strlen( $row['answer'] ) > 80 )
 {
 	$row['answer'] = strip_tags($row['answer']);
 $row['answer'] = nv_clean60( $row['answer'], 80 );
 }
 $i = $i + 1;
 $xtpl->assign( 'DATA', $row );
 $xtpl->parse( 'main.que.an' );
 }
 $rows['number'] = $i ;
 $xtpl->assign( 'ROW', $rows );
 $xtpl->parse( 'main.que' );
 }
 
 $xtpl->parse( 'main' );
 return $xtpl->text( 'main' );
 
 }
}

if ( defined( 'NV_SYSTEM' ) )
{
 $content = nv_faqs_book( $block_config );

}

?>