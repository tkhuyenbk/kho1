<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:32:04 AM
 */

if ( ! defined( 'NV_IS_MOD_FAQS' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) )
{
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
elseif( ! nv_function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js' ) )
{
    define( 'NV_EDITOR', true );
    define( 'NV_IS_CKEDITOR', true );
    $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

    function nv_aleditor( $textareaname, $width = '100%', $height = '450px', $val = '' )
    {
        $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
        $return .= "<script type=\"text/javascript\">
        CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {width: '" . $width . "',height: '" . $height . "',});
        </script>";
        return $return;
    }
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$error = '';
$fb = false;
$array = array();
$mark = $array['sendmail'] =$array['showmail'] = 0;

$array['full_name'] = $user_info['full_name'];
$array['email'] = $user_info['email'];
$array['question'] = '';
$array['catid'] =0;

$listcats = nv_catList();

$b = false;

if ( $arr_config['who_view'] == 1 )
{
 if ( ! defined( 'NV_IS_USER' ) )
 { 
 	$contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['quyenuser'] . '</span>';
 $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login";
 $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
 include ( NV_ROOTDIR . "/includes/header.php" );
 echo nv_site_theme( $contents );
 include ( NV_ROOTDIR . "/includes/footer.php" );
 
 }
}
elseif ( $arr_config['who_view'] == 2 )
{
 if ( ! defined( 'NV_IS_ADMIN' ) )
 {
 $contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['quyenadmin'] . '</span>';
 $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
 $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
 include ( NV_ROOTDIR . "/includes/header.php" );
 echo nv_site_theme( $contents );
 include ( NV_ROOTDIR . "/includes/footer.php" );
 }
}


if ( $nv_Request->isset_request( 'save', 'post' ) )
{
 $fcode = $nv_Request->get_title( 'fcode', 'post', '' );
 $array['title'] = $nv_Request->get_title( 'title', 'post', '' ); 
 $array['sendmail'] = $nv_Request->get_int( 'sendmail', 'post', 0 );
 $array['showmail'] = $nv_Request->get_int( 'showmail', 'post', 0 );
 $array['question'] = $nv_Request->get_textarea( 'question', '', NV_ALLOWED_HTML_TAGS );
 $array['full_name'] = $nv_Request->get_title( 'full_name', 'post', '' );
 $array['email'] = $nv_Request->get_title( 'email', 'post', '' ); 
 $array['catid'] = $nv_Request->get_int( 'catid', 'post', 0 );
 $array['provinceid'] = $nv_Request->get_int( 'provinceid', 'post', 0 );
 $array['disid'] = $nv_Request->get_int( 'disid', 'post', 0 );
 
 
 $check_valid_email = nv_check_valid_email( $array['email'] );
 
 
 if ( ! file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name ) )
 {
 nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name );
 }
 
 $currentpath = NV_UPLOADS_REAL_DIR . '/' . $module_name;
 
 if ( ! defined( 'NV_IS_USER' ) )
 {
 $user_info['userid'] = 0;
 }
 if ( ! nv_capcha_txt( $fcode ) )
 {
 //die($fcode.'sdf');
 $error = $lang_module['error_captcha'];
 
 }
 elseif ( empty( $array['title'] ) )
 {
 $error = $lang_module['error_title'];
 }
 elseif ( empty( $array['question'] ) )
 {
 $error = $lang_module['error_question'];
 } 
 elseif ( empty( $array['full_name'] ) )
 {
 $error = $lang_module['error_full_name'];
 }
 elseif ( ! empty( $check_valid_email ) )
 {
 $error = $check_valid_email;
 }
 
 if ( $error == '' )
 {
 
 /*
    if( $arr_config['duyetan'] == 1 )
    {
        $status = 0;
    }
    else
    {
        $status = 1;
    }
 * */$status = 0;
 
 if ( isset( $array_op[1] ) and preg_match( "/^([a-zA-Z0-9\-\_]+)\-([\d]+)$/", $array_op[1], $matches ) )
 {
 $id = $matches[2];
 $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET 
 `showmail` = ".$array['showmail'].",
 `sendmail` = ".$array['sendmail'].",
 `question`= " . $db->quote( $array['question'] ) . ",
 `title` = " . $db->quote( $array['title'] ) . ",
 	`cus_name` = " . $db->quote( $array['full_name'] ) . ",
 
 `catid`=" . $array['catid'].",
 	`cus_email` = " . $db->quote( $array['email'] ) . "
 	 	
 WHERE `qid`=" . $id ;
 $db->query($sql);
 }
 else
 {
 $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_question` VALUES (
					NULL,					
					" . $user_info['userid'] . ",
					" . $array['catid'] . ",
 
					" . $db->quote( $array['full_name'] ) . ",
					" . $db->quote( $array['email'] ) . ",
					" . $db->quote( $array['title'] ) . ",'',	
					" . $db->quote( $array['question'] ) . ",			
					" . NV_CURRENTTIME . ",0,
					" . $array['sendmail'] . ",
					" . $array['showmail'] . ",
					0,0," . $status . "			
					)";
 
 $id = $db->insert_id( $sql );
 }
 if ( $id != 0 )
 {
 $array['alias'] = change_alias( $array['title'] );
 $db->query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_question` SET `alias`=" . $db->quote( $array['alias'] . "-" . $id ) . " WHERE `qid`=" . $id );
 
 $subject = $lang_module['que'] . " : " . nv_date( 'd.m.Y H:i', NV_CURRENTTIME );
 $message = "<b>" . $lang_module['ndquestion'] . ": </b><br/>";
 
 $message .= $lang_module['title'] . " : " . $array['title'] . "<br />";
 
 $message .= $lang_module['ndquestion'] . " : " . $array['question'] . "<br />";
 
 $message .= $lang_module['full_name'] . " : " . $array['full_name'] . "<br />";
 $message .= $lang_module['email'] . " : " . $array['email'] . "<br />";
 
 $from = $array['email']; 
 
 if ( $arr_config['is_cus'] == 1 )
 {
 
 $thu1 = nv_sendmail( $from, $from, $subject, $message, '' );
 if ( $thu1 == 1 )
 {
 $thu1_mail = $lang_module['thu1_mail'];
 }
 else
 {
 $thu1_mail = $lang_module['no_thu1_mail'];
 }
 
 }
 
 $fb = true;
 }
 else
 {
 $error = $lang_module['error_insert'];
 }
 
 }

}
if ( isset( $array_op[1] ) and preg_match( "/^([a-zA-Z0-9\-\_]+)\-([\d]+)$/", $array_op[1], $matches ) )
{
 $id = $matches[2];
 $alias = $matches[0];
 
 $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_question` WHERE `qid` =" . $id . " AND userid = " . $user_info['userid'];
 
 $result = $db->query( $sql );
 if ( $result->rowCount() )
 {
 $array = $result->fetch();
 $array['full_name'] = $array['cus_name'];
 $array['email'] = $array['cus_email'];
 
 }
}

$array['sendmail'] = ( $array['sendmail'] != 0 ) ? " checked=\"checked\"" : "";
$array['showmail'] = ( $array['showmail'] != 0 ) ? " checked=\"checked\"" : "";

$contents = nv_theme_question( $listcats,$array, $error );

if ( $fb == 1 )
{
 $contents = '<span style="font-weight: bold; font-size: 15px;">' . $lang_module['complates'] . '</span>';
 
 $nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
 $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>