<?php
include_once dirname(__FILE__)."/config.php";
/*
* File: CurrencyConverter.php
* Author: Simon Jarvis
* Copyright: 2005 Simon Jarvis
* Date: 10/12/05
* Link: http://www.white-hat-web-design.co.uk/articles/php-currency-conversion.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class CurrencyConverter 
{
   var $xml_file = "www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
   var $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_table, $mysql_tmp_table;
   var $exchange_rates = array();
   var $last_update;
 
   //Load Currency Rates
   function CurrencyConverter($host,$user,$pass,$db,$tb,$tmp) 
   {
      $this->mysql_host = $host;
      $this->mysql_user = $user;
      $this->mysql_pass = $pass;
      $this->mysql_db = $db;
      $this->mysql_tmp_table = $tmp;
      $this->mysql_table = $tb;
      $this->checkLastUpdated();
 
      $conn = mysql_connect($this->mysql_host,$this->mysql_user,$this->mysql_pass);
      $rs = mysql_select_db($this->mysql_db,$conn);
      $sql = "SELECT * FROM `".$this->mysql_tmp_table."` order by `currency` asc ";
      $rs =  mysql_query($sql,$conn);
      while($row = mysql_fetch_assoc($rs)) 
	  {
         $this->exchange_rates[$row['currency']] = $row['rate'];       
      }
   }
 
   /* Perform the actual conversion, defaults to £1.00 GBP to USD */
   function convert($amount=1,$from="GBP",$to="USD",$decimals=2) 
   {
      return ($amount/$this->exchange_rates[$from])*$this->exchange_rates[$to];
   }
 
   /* Check to see how long since the data was last updated */
   function checkLastUpdated() 
   {
      $conn = mysql_connect($this->mysql_host,$this->mysql_user,$this->mysql_pass);
      $rs = mysql_select_db($this->mysql_db,$conn);
      $sql = "SHOW TABLE STATUS FROM ".$this->mysql_db." LIKE '".$this->mysql_tmp_table."'";
      $rs =  mysql_query($sql,$conn);
      if(mysql_num_rows($rs) == 0 ) 
	  {
         $this->createTable();
      } 
	  else 
	  {
         $row = mysql_fetch_array($rs);
		 $this->last_update = $row["Update_time"];
		 if(!$row["Update_time"])
		 {
			 $this->last_update = $row["Create_time"];
		 }
         if(time() > (strtotime($row["Update_time"])+(60*60)) ) 
		 {
			$this->downloadExchangeRates();  
         }
      }
   }
 
   /* Download xml file, extract exchange rates and store values in database */
   function downloadExchangeRates() 
   {
	  $buffer = "";
      $currency_domain = substr($this->xml_file,0,strpos($this->xml_file,"/"));
      $currency_file = substr($this->xml_file,strpos($this->xml_file,"/"));
      $fp = @fsockopen($currency_domain, 80, $errno, $errstr, 10);
      if($fp) 
	  {
         $out = "GET ".$currency_file." HTTP/1.1\r\n";
         $out .= "Host: ".$currency_domain."\r\n";
         $out .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8) Gecko/20051111 Firefox/1.5\r\n";
         $out .= "Connection: Close\r\n\r\n";
         fwrite($fp, $out);
         while (!feof($fp)) 
		 {
            $buffer .= fgets($fp, 128);
         }
         fclose($fp);
 
         $pattern = "{<Cube\s*currency='(\w*)'\s*rate='([\d\.]*)'/>}is";
         preg_match_all($pattern,$buffer,$xml_rates);
         array_shift($xml_rates);
 	     $exchange_rate = array();
         for($i=0;$i<count($xml_rates[0]);$i++) 
		 {
            $exchange_rate[$xml_rates[0][$i]] = $xml_rates[1][$i];
         }
         $conn = mysql_connect($this->mysql_host,$this->mysql_user,$this->mysql_pass);
         $rs = mysql_select_db($this->mysql_db,$conn);
		 
         foreach($exchange_rate as $currency=>$rate) 
		 {
            if((is_numeric($rate)) && ($rate != 0)) 
			{
               $sql = "SELECT * FROM ".$this->mysql_tmp_table." WHERE currency='".$currency."'";
               $rs =  mysql_query($sql,$conn) or die(mysql_error());
               if(mysql_num_rows($rs) > 0) 
			   {
                  $sql = "UPDATE ".$this->mysql_tmp_table." SET rate=".$rate." WHERE currency='".$currency."'";
               } 
			   else 
			   {
                  $sql = "INSERT INTO ".$this->mysql_tmp_table." VALUES('".$currency."',".$rate.")";
               }
               $rs =  mysql_query($sql,$conn) or die(mysql_error());
            }
         } 
		 
		   
	  
	  
	  // update currency
	  
	  $now = date('Y-m-d H:i:s');

      $sql = "SELECT * FROM `".$this->mysql_tmp_table."` order by `currency` asc ";
      $rs =  mysql_query($sql,$conn);
      while($row = mysql_fetch_assoc($rs)) 
	  {
         $this->exchange_rates[$row['currency']] = $row['rate'];       
      }
	  
	  $sql = "select `".$this->mysql_table."`.* 
	  from `".$this->mysql_table."`
	  where `".$this->mysql_table."`.`value` = '1.00000'
	  ";
	  $res = mysql_query($sql);
	  if(mysql_num_rows($res))
	  {
	  	$data = mysql_fetch_assoc($res);
		$from = $data['code'];
		$value1 = $data['value'];
		
		
		$sql = "select `".$this->mysql_table."`.* 
		from `".$this->mysql_table."`
		where `".$this->mysql_table."`.`value` != '1.00000'
		";
		$res = mysql_query($sql);
		if(mysql_num_rows($res))
		{
			while(($data = mysql_fetch_assoc($res)))
			{
				$to = $data['code'];
				$value2 = ($value1/$this->exchange_rates[$from])*$this->exchange_rates[$to];;
				$sql = "update `".$this->mysql_table."` set `value` = '$value2', `date_modified` = '$now'
				where `code` = '$to';
				";
				mysql_query($sql);

			}
		}
		
		
	  }
	  
      }
	  
	  
   }
	
   /* Create the currency exchange table */
   function createTable() 
   {
      $conn = mysql_connect($this->mysql_host,$this->mysql_user,$this->mysql_pass);
      $rs = mysql_select_db($this->mysql_db,$conn);
      $sql = "CREATE TABLE ".$this->mysql_tmp_table." ( currency char(3) NOT NULL default '', rate double NOT NULL default '0', PRIMARY KEY(currency) ) ENGINE=MyISAM";
      $rs =  mysql_query($sql,$conn) or die(mysql_error());
      $sql = "INSERT INTO ".$this->mysql_tmp_table." VALUES('EUR',1)";
      $rs =  mysql_query($sql,$conn) or die(mysql_error());
      $this->downloadExchangeRates();   
   }
}
$cc = new CurrencyConverter(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE,DB_PREFIX."currency", DB_PREFIX."tmp_currency");

?>