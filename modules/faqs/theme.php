<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:23:15 AM
 */

if (!defined('NV_IS_MOD_FAQS'))
 die('Stop!!!');

function nv_theme_samples_main($array_data)
{
 global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info;
 $xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
 $xtpl->assign('LANG', $lang_module);

 $xtpl->parse('main');
 return $xtpl->text('main');
}

function nv_theme_samples_detail($array_data)
{
 global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info;
 $xtpl = new XTemplate("detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
 $xtpl->assign('LANG', $lang_module);

 $xtpl->parse('main');
 return $xtpl->text('main');
}

function x_generate_page($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $onclick = false, $js_func_name = 'nv_urldecode_ajax', $containerid = 'generate_page')
{
 global $lang_global;

 $total_pages = ceil($num_items / $per_page);
 if ($total_pages == 1)
 return '';
 @$on_page = floor($start_item / $per_page) + 1;
 $amp = preg_match("/\?/", $base_url) ? "&amp;" : "?";
 $page_string = "";
 if ($total_pages > 10)
 {
 $init_page_max = ($total_pages > 3) ? 3 : $total_pages;
 for ($i = 1; $i <= $init_page_max; $i++)
 {
 $href = !$onclick ? "href=\"" . $base_url . $amp . "page=" . (($i - 1) * $per_page) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($base_url . $amp . "page=" . (($i - 1) * $per_page))) . "','" . $containerid . "')\"";
 $page_string .= ($i == $on_page) ? "<span class=\"number_click\">" . $i . "</span>" : "<a " . $href . "><span class=\"number\">" . $i . "</span></a>";
 if ($i < $init_page_max)
 $page_string .= "";
 }
 if ($total_pages > 3)
 {
 if ($on_page > 1 && $on_page < $total_pages)
 {
 $page_string .= ($on_page > 5) ? " ... " : ", ";
 $init_page_min = ($on_page > 4) ? $on_page : 5;
 $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;
 for ($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
 {
 $href = !$onclick ? "href=\"" . $base_url . $amp . "page=" . (($i - 1) * $per_page) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($base_url . $amp . "page=" . (($i - 1) * $per_page))) . "','" . $containerid . "')\"";
 $page_string .= ($i == $on_page) ? "<span class=\"number_click\">" . $i . "</span>" : "<a " . $href . "><span class=\"number\">" . $i . "</span></a>";
 if ($i < $init_page_max + 1)
 {
 $page_string .= "";
 }
 }
 $page_string .= ($on_page < $total_pages - 4) ? " ... " : ", ";
 }
 else
 {
 $page_string .= " ... ";
 }

 for ($i = $total_pages - 2; $i < $total_pages + 1; $i++)
 {
 $href = !$onclick ? "href=\"" . $base_url . $amp . "page=" . (($i - 1) * $per_page) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($base_url . $amp . "page=" . (($i - 1) * $per_page))) . "','" . $containerid . "')\"";
 $page_string .= ($i == $on_page) ? "<span class=\"number_click\">" . $i . "</span>" : "<a " . $href . "><span class=\"number\">" . $i . "</span></a>";
 if ($i < $total_pages)
 {
 $page_string .= "";
 }
 }
 }
 }
 else
 {
 for ($i = 1; $i < $total_pages + 1; $i++)
 {
 $href = !$onclick ? "href=\"" . $base_url . $amp . "page=" . (($i - 1) * $per_page) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($base_url . $amp . "page=" . (($i - 1) * $per_page))) . "','" . $containerid . "')\"";
 $page_string .= ($i == $on_page) ? "<span class=\"number_click\">" . $i . "</span>" : "<a " . $href . "><span class=\"number\">" . $i . "</span></a>";
 if ($i < $total_pages)
 {
 $page_string .= "";
 }
 }
 }
 if ($add_prevnext_text)
 {
 if ($on_page > 1)
 {
 $href = !$onclick ? "href=\"" . $base_url . $amp . "page=" . (($on_page - 2) * $per_page) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($base_url . $amp . "page=" . (($on_page - 2) * $per_page))) . "','" . $containerid . "')\"";
 $page_string = "&nbsp;&nbsp;<span class=\"number\"><a " . $href . ">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
 }
 if ($on_page < $total_pages)
 {
 $href = !$onclick ? "href=\"" . $base_url . $amp . "page=" . ($on_page * $per_page) . "\"" : "href=\"javascript:void(0)\" onclick=\"" . $js_func_name . "('" . rawurlencode(nv_unhtmlspecialchars($base_url . $amp . "page=" . ($on_page * $per_page))) . "','" . $containerid . "')\"";
 $page_string .= "&nbsp;&nbsp;<span class=\"number\"><a " . $href . ">" . $lang_global['pagenext'] . "</a></span>";
 }
 }
 return $page_string;
}

function nv_theme_question( $listcats, $array, $error)
{
 global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $db;
 $xtpl = new XTemplate("question.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
 $xtpl->assign('LANG', $lang_module);

 $xtpl->assign('CONTENT', $array);

 $htmlbodytext = '';
 if (defined('NV_EDITOR') and function_exists('nv_aleditor'))
 {
 $htmlbodytext .= nv_aleditor('question', '99%', '150px', $array['question']);
 }
 else
 {
 $htmlbodytext .= "<textarea style=\"width:99%;height:150px\" name=\"question\" id=\"question\">" . $array['question'] . "</textarea>";
 }
 $xtpl->assign('HTMLQS', $htmlbodytext);
 foreach ($listcats as $cat)
 { 
 $cat['selected'] = ($cat['id'] == $array['catid']) ? 'selected="selected"' : '';
 
 $xtpl->assign('LISTCATS', $cat);
 $xtpl->parse('main.catid');
 }

 if ($error != '')
 {
 $xtpl->assign('error', $error);
 $xtpl->parse('main.error');
 }

 $xtpl->parse('main');
 return $xtpl->text('main');
}
?>