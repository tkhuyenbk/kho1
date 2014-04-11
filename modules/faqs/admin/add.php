<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );
$xtpl = new XTemplate( "add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );

$listcats = nv_catList();
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$post['question'] = $post['title'] = $error = '';
$post['catid'] = $post['provinceid'] = $post['disid'] = 0;
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$post['title'] = $nv_Request->get_title( 'title', 'post', '' );
	$post['question'] = $nv_Request->get_textarea( 'question', '', NV_ALLOWED_HTML_TAGS );
 $post['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
 
	if( $post['title'] == '' )
	{
		$error = $lang_module['errorTitleque'];
	}
	else if( $post['question'] == '' )
	{
		$error = $lang_module['errorDesque'];
	}
	else
	{
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_question` VALUES (
					NULL,					
					" . $admin_info['userid'] . ",
					" . $post['catid'] . ",
					" . $db->quote( $admin_info['username'] ) . ",
					" . $db->quote( $admin_info['email'] ) . ",
					" . $db->quote( $post['title'] ) . ",'',	
					" . $db->quote( $post['question'] ) . ",			
					" . NV_CURRENTTIME . ",0,0,0,0,0,1)";

		$id = $db->insert_id( $sql );
		if( $id != 0 )
		{
			$post['alias'] = change_alias( $post['title'] );
			$db->query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET `alias`=" . $db->quote( $post['alias'] . "-" . $id ) . " WHERE `qid`=" . $id );
			nv_del_moduleCache( $module_name );
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&op=main" );
			die();
		}
		else
		{
			$error = $lang_module['errorInsert'] ;
		}
	}
}

if( defined( 'NV_EDITOR' ) )
{
	require_once (NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php');
}
if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$_cont = nv_aleditor( 'question', '100%', '200px', $post['question'] );
}
else
{
	$_cont = "<textarea style=\"width:100%;height:200px\" name=\"question\" id=\"question\">" . $post['question'] . "</textarea>";
}

$xtpl->assign( 'HTMLQS', $_cont );
$xtpl->assign( 'CONTENT', $post );

foreach( $listcats as $cat )
{
 $xtpl->assign( 'LISTCATS', $cat );
 $xtpl->parse( 'main.catid' );
}
if( $error != '' )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");