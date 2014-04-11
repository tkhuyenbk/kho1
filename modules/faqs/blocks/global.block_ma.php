<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_faqs_ma' ) )
{

 function nv_faqs_ma ( $block_config )
 {
 global $db, $module_array_cat, $module_info, $lang_module, $site_mods, $db, $user_info;
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
 
 if ( $user_info['userid'] != 0 )
 {
 $xtpl = new XTemplate( "block_ma.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module );
 $xtpl->assign( 'LINK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=question" );
 $xtpl->assign( 'LANG', $lang_module );
 $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
 $xtpl->assign( 'themes', $block_theme );
 
 $rows['sendque'] = 0;
 $rows['anser'] = 0;
 $rows['mostanser'] = 0;
 $rows['choanser'] = 0;
 $rows['linkque'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=que";
 $rows['linkan'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=an";
 $rows['moan'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=moan";
 $rows['bcan'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=bcan";

 $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module . "_note` where userid= " . $user_info['userid'];
 $resutl = $db->query( $sql );
 if ( $resutl->rowCount() > 0 )
 {
 $rows = $resutl->fetch();
 $rows['linkque'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=que";
 $rows['linkan'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=an";
 $rows['moan'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=moan";
 $rows['bcan'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module . "&amp;op=bcan";
 }
 $xtpl->assign( 'ROW', $rows );
 
 $xtpl->parse( 'main' );
 return $xtpl->text( 'main' );
 }
 
 }
}

if ( defined( 'NV_SYSTEM' ) )
{
 $content = nv_faqs_ma( $block_config );

}

?>