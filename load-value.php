<?php

$dir = '';
if(isset($_GET['dir']))
$dir = @$_GET['dir'];
$dir = strip_tags(str_replace(array("\\", "/"), "", $dir));

$group = '';
if(isset($_GET['group']))
$group = @$_GET['group'];
$group = strip_tags(str_replace(array("\\", "/"), "", $group));

$module = '';
if(isset($_GET['module']))
$module = @$_GET['module'];
$module = strip_tags(str_replace(array("\\", "/"), "", $module));

$langref = '';
if(isset($_GET['langref']))
$langref = @$_GET['langref'];
$langref = strip_tags(str_replace(array("\\", "/"), "", $langref));

$lang2edit = '';
if(isset($_GET['lang2edit']))
$lang2edit = @$_GET['lang2edit'];
$lang2edit = strip_tags(str_replace(array("\\", "/"), "", $lang2edit));

$key = '';
if(isset($_GET['key']))
$key = @$_GET['key'];
$key = strip_tags(str_replace(array("\\", "/"), "", $key));


if(($dir == 'catalog' || $dir == 'admin') && $langref != '' && $lang2edit != '' && $group != '' && $module != '')
{
	$parent1 = dirname(__FILE__)."/upload/$dir/language/".$langref;
	$parent2 = dirname(__FILE__)."/upload/$dir/language/".$lang2edit;
	
	$data = array();
	
	
	$_ = array();
	$module_file1 = $parent1."/".$group."/".$module;
	if($group == '---' && $module == '---')
	{
		$module_file1 = dirname(__FILE__)."/upload/$dir/language/$langref/$langref".".php";
	}
	if(file_exists($module_file1))
	{
		include $module_file1;
		if(isset($_[$key]))
		{
			$data['ref'] = $_[$key];
		}
	}

	$_ = array();
	$module_file2 = $parent2."/".$group."/".$module;
	if($group == '---' && $module == '---')
	{
		$module_file2 = dirname(__FILE__)."/upload/$dir/language/$lang2edit/$lang2edit".".php";
	}
	if(file_exists($module_file2))
	{
		include $module_file2;
		if(isset($_[$key]))
		{
			$data['edit'] = $_[$key];
		}
	}
	echo json_encode($data);
	
	
	
}
?>