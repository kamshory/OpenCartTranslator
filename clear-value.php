<?php

$dir = '';
if(isset($_POST['dir']))
$dir = @$_POST['dir'];
$dir = strip_tags(str_replace(array("\\", "/"), "", $dir));

$group = '';
if(isset($_POST['group']))
$group = @$_POST['group'];
$group = strip_tags(str_replace(array("\\", "/"), "", $group));

$module = '';
if(isset($_POST['module']))
$module = @$_POST['module'];
$module = strip_tags(str_replace(array("\\", "/"), "", $module));

$langref = '';
if(isset($_POST['langref']))
$langref = @$_POST['langref'];
$langref = strip_tags(str_replace(array("\\", "/"), "", $langref));

$lang2edit = '';
if(isset($_POST['lang2edit']))
$lang2edit = @$_POST['lang2edit'];
$lang2edit = strip_tags(str_replace(array("\\", "/"), "", $lang2edit));

$key = '';
if(isset($_POST['key']))
$key = @$_POST['key'];
$key = strip_tags(str_replace(array("\\", "/"), "", $key));

if(($dir == 'catalog' || $dir == 'admin') && $lang2edit != '' && $langref != '' && $group != '' && $module != '')
{
	$parent1 = dirname(__FILE__)."/upload/$dir/language/".$langref;
	$parent2 = dirname(__FILE__)."/upload/$dir/language/".$langref;
	
	$data = array();
	
	
	$_ = array();
	$module_file1 = $parent2."/".$group."/".$module;
	if($group == '---' && $module == '---')
	{
		$module_file1 = dirname(__FILE__)."/upload/$dir/language/$langref/$langref".".php";
	}
	if(file_exists($module_file1))
	{
		include $module_file1;
	}
	$keys = array_keys($_);
	print_r($keys);
	
	$_ = array();
	$module_file2 = $parent2."/".$group."/".$module;
	if($group == '---' && $module == '---')
	{
		$module_file2 = dirname(__FILE__)."/upload/$dir/language/$lang2edit/$lang2edit".".php";
	}
	if(file_exists($module_file2))
	{
		include $module_file2;
	}
	
	
	$html = "<"."?php\r\n";
	foreach($_ as $k=>$v)
	{
		$v = str_replace("\\", "\\\\", $v);
		$v = str_replace("'", "\\'", $v);
		
		if(in_array($k, $keys))
		{
			$html .= "\$"."_"."['".$k."'] = '".$v."';\r\n";
		}
	}
	$html .= "?".">";
	
	
	if(!file_exists($parent2) && $group != '---' && $module != '---')
	{
		mkdir($parent2);
		chmod($parent2, 755);
	}
	
	if(!file_exists($parent2."/".$group) && $group != '---' && $module != '---')
	{
		mkdir($parent2."/".$group);
		chmod($parent2."/".$group, 755);
	}
	
	file_put_contents($module_file2, $html);
}
?>