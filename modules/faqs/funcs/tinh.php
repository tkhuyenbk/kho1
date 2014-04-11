<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 14 Apr 2011 12:01:30 GMT
 */

if (!defined('NV_IS_MOD_FAQS'))
 die('Stop!!!');
$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/");
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
if( $nv_Request->isset_request( 'action', 'post' ) )
{
 $provinceid = $nv_Request->get_int( 'provinceid', 'post', '' );
 
 if( $provinceid == 0 )
 die( 'Chưa chọn tình thành' );

 $ListDiss = nv_Districts($provinceid);
 
 foreach( $ListDiss as $listhoc_i )
 {
 $xtpl->assign( 'OPTION', $listhoc_i );
 $xtpl->parse( 'main.link.dis' );
 }

 $xtpl->parse( 'main.link' );
 $contents = $xtpl->text( 'main.link' );
 include (NV_ROOTDIR . '/includes/header.php');
 echo $contents;
 include (NV_ROOTDIR . '/includes/footer.php');

}


$xtpl->parse('main');
$contents = $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
exit ;
?>