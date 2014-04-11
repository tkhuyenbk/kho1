<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM 
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['detail_question'];
$listquestion = nv_questionList();

$qid = $nv_Request->get_int( 'qid', 'get', 0 );
$listcat = nv_listcats( $qid );
$thu2_mail = '';
$query = "SELECT `answer` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_question` WHERE `qid`=" . $qid;
$result = $db->query( $query );
$numrows = $result->rowCount();

/*
$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_order` SET view=view+1 WHERE id=" . $id;
$db->query( $sql );
*/
$f = false;

if ( ! empty( $listquestion[$qid] ) )
{
 $html = $error = $img = '';
 $xtpl = new XTemplate( "detail2.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
 $xtpl->assign( 'LANG', $lang_module );
 $xtpl->assign( 'link_module', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_data );
 $xtpl->assign( 'GLANG', $lang_global );
 $xtpl->assign( 'TABLE_CAPTION', $lang_module['detail_question'] );
 $xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );
 
 if ( $listquestion[$qid]['file'] != '' )
 {
 $file2 = NV_UPLOADS_DIR . '/' . $module_data . "/" . $listquestion[$qid]['file'];
 
 if ( file_exists( NV_ROOTDIR . '/' . $file2 ) and ( $filesize = filesize( NV_ROOTDIR . '/' . $file2 ) ) != 0 )
 {
 $alias = change_alias( $listquestion[$qid]['file'] );
 $new_name = str_replace( "-", "_", $alias );
 $listquestion[$qid]['links'] = $file2;
 $listquestion[$qid]['titles'] = $listquestion[$qid]['file'];
 
 $session_files['fileupload'][$new_name] = array( 
 'qid' => $listquestion[$qid]['qid'], 'src' => NV_ROOTDIR . '/' . $file2 
 );
 }
 }
 $xtpl->assign( 'ORDER', $listquestion[$qid] );
 if ( $listquestion[$qid]['file'] != '' )
 {
 $xtpl->parse( 'main.files' );
 }
 
 $xtpl->assign( 'cat', $listcat[$listquestion[$qid]['catid']]['title'] );
 $xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
 //$xtpl->assign( 'FILES_DIR', NV_UPLOADS_DIR . '/' . $module_name ); 
 $xtpl->assign( 'ACTION_FILE', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&op=de&qid=" . $qid );
 $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_answer` WHERE qid =" . $qid;
 $re = $db->query( $sql );
 if ( $re->rowCount() )
 {
 $i = 0;
 while ( $row = $re->fetch() )
 {
 $i = $i + 1;
 $xtpl->assign( 'num', $i );
 $xtpl->assign( 'phanhoi', $row['answer'] );
 $xtpl->parse( 'main.phanhoi' );
 }
 }
 else
 {
 if ( defined( 'NV_EDITOR' ) )
 {
 require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
 }
 if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
 {
 $_cont = nv_aleditor( 'bodytext', '100%', '200px', $html );
 }
 else
 {
 $_cont = "<textarea style=\"width:100%;height:200px\" name=\"bodytext\" id=\"bodytext\">" . $html . "</textarea>";
 }
 $xtpl->assign( 'CONTENT', $_cont );
 $xtpl->parse( 'main.nophanhoi' );
 }
 if ( $nv_Request->isset_request( 'send', 'post' ) )
 {
 $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET status = 1 WHERE qid=" . $qid;
 
 if ( $db->query( $sql ) )
 {
 if ( $arr_config['is_mark'] == 1 )
 {
 $db->query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_mark` SET `mark`=mark-" . $arr_config['mark_question'] . " WHERE `userid`=" . $listquestion[$qid]['userid'] );
 $db->query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_note` SET `sendque`=sendque+1 WHERE `userid`=" . $listquestion[$qid]['userid'] );
 }
 
 die('<script type="text/javascript">
 window.location.href = window.location.href;
 alert ("Duyệt thành công");
			</script>');
 }
 }
 
 //$fileupload_num = count( $arr_img ); 
 

 if ( $error != '' )
 {
 $xtpl->assign( 'ERROR', $error );
 $xtpl->parse( 'main.error' );
 }
 $xtpl->parse( 'main' );
 $contents = $xtpl->text( 'main' );

}
else
{
 $contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['no_data'] . '</span>';
 $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
 $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>