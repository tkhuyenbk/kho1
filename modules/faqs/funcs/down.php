<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_FAQS' ) ) die( 'Stop!!!' );



if ( $nv_Request->isset_request( 'check', 'post' ) )
{
 $file = $nv_Request->get_string( 'file', 'post', '' );
 if( $file == "")
 die('');
 
 if(file_exists( $file )){
 die($file); 
 }else{
 die('');
 } 
 
}

$file = $nv_Request->get_string( 'file', 'get', '' );
taifile( $file );

?>