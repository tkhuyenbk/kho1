/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010  12:48:35 PM 
 */
//  ----------------------------------------
function nv_chang_cat_weight( catid )
{
   var nv_timer = nv_settimeout_disable( 'weight' + catid, 2000 );
   var newpos = document.getElementById( 'weight' + catid ).options[document.getElementById( 'weight' + catid ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&changeweight=1&catid=' + catid + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_cat_weight_result' );
   return;
}

//  ---------------------------------------

function nv_chang_cat_weight_result( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}

//  ---------------------------------------

function nv_chang_cat_status( catid )
{
   var nv_timer = nv_settimeout_disable( 'change_status' + catid, 2000 );
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&changestatus=1&catid=' + catid + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_cat_status_res' );
   return;
}

//  ---------------------------------------

function nv_chang_cat_status_res( res )
{
   if( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
      window.location.href = window.location.href;
   }
   return;
}

//  ---------------------------------------

function nv_cat_del( catid )
{
   if ( confirm( cat_del_cofirm ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&del=1&catid=' + catid, '', 'nv_cat_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_cat_del_result( res )
{
   if( res == 'OK' )
   {
      window.location.href = window.location.href;
   }
   else
   {
      alert( nv_is_del_confirm[2] );
   }
   return false;
}

//  ---------------------------------------
function nv_status(sid)
{	     
   var nv_timer = nv_settimeout_disable('sta_' + sid, 2000);
	var new_status = document.getElementById('sta_' + sid).options[document.getElementById('sta_' + sid).selectedIndex].value;
	nv_ajax("post", script_name, nv_name_variable + '=' + nv_module_name + '&'+ nv_fc_variable + '=main&id=' + sid
			+'&new=' + new_status + '&changesta=1&num='+ nv_randomPassword(8), '', 'nv_chang_sta_result');
	return;
   
}
//  ---------------------------------------
function nv_chang_sta_result( res )
{
   if ( res != 'OK' )
   {
      alert( "error!");
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}

//  ---------------------------------------
function nv_de_del( id )
{
	if ( confirm( de_del_cofirm ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&'+ nv_fc_variable + '=main&del=1&id=' + id, '', 'nv_de_del_result' );
   }
   return false;
}

//  ---------------------------------------

function nv_de_del_result( res )
{
   if( res == 'OK' )
   {
      window.location.href = window.location.href;
   }
   else
   {
      alert( nv_is_del_confirm[2] );
   }
   return false;
}

//  ----------------------------------------
//-----------------------
function doaction(op) {
	
	var list_post = document.getElementById('list_post');
	var action = document.getElementById('action');
	var fa = document.list_post['idcheck[]'];
	var del_list = '';
	if (fa.length) {
		var k = 0;
		for ( var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				if ( k == 0 )
				{
					del_list = fa[i].value;
				}else{
					del_list = del_list + ',' + fa[i].value;
				}
				k++;
			}
		}
	}
	
    if (del_list == "" && fa.checked) {
       del_list = fa.value
    }
    
	if (del_list == "") {
		alert(doempty+ 'thu');
	} else {
		if (confirm(nv_is_change_act_confirm[0])) {
			$.ajax({        
		      type: "POST",
		      url: nv_siteroot + 'admin/index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '='+op,
		      data: 'listaction=1&do=' + action.value + '&listid='+ del_list,
		      success: function(data){  
		    	  nv_level_ress();
		      }
		    });
		}
	}
}
//-----------------------------------
function nv_level_ress( res )
{
	
	if ( res == 'OK' )
   {
      alert( nv_is_change_act_confirm[2]);
   }
   
   window.location.href = window.location.href;
   return;
}

//-----------------------
function nv_download_files(file)
{     
	nv_ajax('post', nv_siteroot + 'index.php', nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=down&check=1&file=' + file, '', 'nv_download_file_result');
    
}
//------------
function nv_download_file_result(res){
    if( res != ""){
        window.location.href = nv_siteroot + "index.php?" + nv_lang_variable + "="
		+ nv_sitelang + "&" + nv_name_variable + "=" + nv_module_name + "&"
		+ nv_fc_variable + "=down&file=" + res;
    }else{
        alert('File không tồn tại trong hệ thống. Có thể BQT đã xóa file này trước đó. Vui lòng liên hệ BQT để biết thêm chi tiết');
    }

}
//----------------------
function nv_de_dels( id, qid )
{
	if ( confirm( de_del_cofirm ) )
	{
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&'+ nv_fc_variable + '=detail&qid='+qid+'&del=1&id=' + id, '', 'nv_de_dels_result' );
	}
   return false;
}

//  ---------------------------------------

function nv_de_dels_result( res )
{
   if( res == 'OK' )
   {
      window.location.href = window.location.href;
   }
   else
   {
      alert( res);
   }
   return false;
}

//  ----------------------------------------
function nv_de_duyet( id, qid )
{
	
  if ( confirm( de_duyet_cofirm ) )
   {
      nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&'+ nv_fc_variable + '=detail&qid='+qid+'&duyet=1&id=' + id, '', 'nv_de_duyet_result' );
   }
   return false;
}

//  ---------------------------------------
function nv_de_duyet_result( res )
{
  
	if( res == 'OK' )
   {
      window.location.href = window.location.href;
   }
   else
   {
      alert( res);
   }
   return false;
}

//  ----------------------------------------
//-----------------------
function faqstion(op,qid) {	
	var list_post = document.getElementById('list_post');
	var action = document.getElementById('action');
	var fa = document.list_post['idcheck[]'];
	var j = 0;
	var del_list = '';
	if (fa.length) {		
		var k = 0;
		for ( var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				j = j + 1;
				if ( k == 0 )
				{				
					del_list = fa[i].value;
					
				}else{
					del_list = del_list + ',' + fa[i].value;
				}
				k++;
			}
		}
	}
	
    if (del_list == "" && fa.checked) {
       del_list = fa.value
    }
    
	if (del_list == "") {
		alert(doempty+ 'thu');
	} else {
		if (confirm(nv_is_change_act_confirm[0])) {
			$.ajax({        
		      type: "POST",
		      url: nv_siteroot + 'admin/index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '='+op+'&qid='+qid,
		      data: 'listaction=1&do=' + action.value + '&listid='+ del_list,
		      success: function(data){  
		    	  nv_level_resss();
		      }
		    });
		}
	}
}
//-----------------------------------
function nv_level_resss( res )
{
	
	if ( res == 'OK' )
   {
      alert( nv_is_change_act_confirm[2]);
   }
   
   window.location.href = window.location.href;
   return;
}

//-----------------------
function nv_link(a,module){nv_settimeout_disable("cat_"+a,2E3);

var b=document.getElementById("cat_"+a).options[document.getElementById("cat_"+a).selectedIndex].value,
a=document.getElementById("cat_"+a).options[document.getElementById("cat_"+a).selectedIndex].text;
0!=b?(nv_ajax("post",script_name,nv_name_variable+
"="+nv_module_name+"&op=add&action=1&provinceid="+b,"thu","")):($("#thu").hide())}