/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010  12:49:25 PM 
 */
function nv_download_files(file)
{ 
    nv_ajax('post', nv_siteroot + 'index.php', nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=down&check=1&file=' + file, '', 'nv_download_file_result');
    
}
function nv_download_file_result(res){
    if( res != ""){
        window.location.href = nv_siteroot + "index.php?" + nv_lang_variable + "="
		+ nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&"
		+ nv_fc_variable + "=down&file=" + res;
    }else{
        alert('File không tồn tại trong hệ thống. Có thể BQT đã xóa file này trước đó. Vui lòng liên hệ BQT để biết thêm chi tiết');
    }

}
////////=--------------------------------
function nv_bc(id, alias)
{
	if ( confirm( bc_cho ) )
	   {
	      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=detail/'+alias+'&bc=1&id=' + id, '', 'nv_bc_result' );
	   }
	return false;
}

//  ---------------------------------------

function nv_bc_result( res )
{
   if( res == 'OK' )
   {
      window.location.href = window.location.href;
   }
   else
   {
      alert( res );
   }
   return false;
}

//  ---------------------------------------
//-----------------------
function nv_link(a,module){nv_settimeout_disable("cat_"+a,2E3);
var b=document.getElementById("cat_"+a).options[document.getElementById("cat_"+a).selectedIndex].value,
a=document.getElementById("cat_"+a).options[document.getElementById("cat_"+a).selectedIndex].text;
0!=b?(nv_ajax("post", nv_siteroot + "index.php", nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=tinh&action=1&provinceid="+b,"thu","")):($("#thu").hide())}
//--------------------------
function nv_chang_sapsepcat() {
	var newsort = document.getElementById( 'cat' ).options[document.getElementById('cat').selectedIndex].value;
	nv_ajax("post", nv_siteroot + "index.php", nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=main&changess=1&catids=" + newsort, "", "nv_re_chang_price")
};
//--------------------------
function nv_re_chang_price(res) {
	if (res != 'OK') {
		alert(res);
	} else {
		window.location.href = window.location.href;
	}

	return;
}
//--------------
function nv_chang_sapseppro() {
	var newsort = document.getElementById( 'pro' ).options[document.getElementById('pro').selectedIndex].value;
	nv_ajax("post", nv_siteroot + "index.php", nv_lang_variable + "=" + nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=main&changesss=1&provinceid=" + newsort, "", "nv_re_chang_price")
};