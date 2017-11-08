<?php
class DBN
{
var $link_id = 0;
var $rs = 0;
var $error_no = 0;
var $error = "";
var $debug = true;
var $paging_size = 20;
var $database_name;
var $rsarr;
var $rs_arr;
var $arr_row;
var $ci;
var $all_db;
var $all_db_array;
//*******************************************************************************************
/*function get_database()
{
	return "advertisingvehicles";//local
	//return "advertisingvehicles";//online
}
function get_user()
{
	return "root";
	//return "contingent";//online
}
function get_host()
{
	return "localhost";
	//return "breadstick.donet.com";//online
}
function get_password()
{
	return "";
	//return "frTyu1";//omline
}*/
function get_all_databases()
{
	//return "sitebuilder";//online
	$this->open();	
	$this->all_db = 0;
	$query = mysql_query("SHOW DATABASES");
	while ( $arr_row = mysql_fetch_array($query))
	{	
		//print_r($arr_row);
		if($arr_row['Database'] != 'information_schema' &&  $arr_row['Database'] != 'mysql' && $arr_row['Database'] != 'webpowerup' && $arr_row['Database'] != 'performance_schema')
		{
			$rs_arr[$this->all_db] = $arr_row;
			$this->all_db++;
		}
		
	}
	
		return ($rs_arr);
}

function get_database()
{	
	return $this->database_name;
}
function set_database($database)
{	
	$this->database_name = $database;
}
function add_fields_in_table($query)
{
		$this->open();	
		$this->all_db = 0;
		mysql_query($query);
		$this->error_no = mysql_errno();
		$this->error = mysql_error();
		
		return   $this->error;
}
function get_user()
{
	//return "root";//online
	return "root";//online

}
function get_host()
{
	return "localhost";//online	
}
function get_password()
{
	//return "";//omline
	//return "root";
	return "root";	
}

//*******************************************************************************************
function halt($msg)
{
	if ( $this->debug )
	{
		echo("<B>Database error:</B> $msg<BR>\n");
		echo("<B>MySQL error</B>: $this->error_no ($this->error)<BR>\n");
		die("Process of this page has been halted.");
	}	
}
//*******************************************************************************************
function last_insert_id()
{
	if ( mysql_affected_rows($this->link_id)>0 )
		return mysql_insert_id();
	else
		return 0;
}

//*******************************************************************************************
function open($p_connect = false)
{ 
	
	//echo '----------------'.$this->get_database().'<br>';
	if($this->link_id == 0)
	{
		if ($p_connect)
		{
			$this->link_id = mysql_pconnect($this->get_host(), $this->get_user(), $this->get_password());
		}
		else
		{
			$this->link_id = mysql_connect($this->get_host(), $this->get_user(), $this->get_password());
		}
		
		if (!$this->link_id)
		{
			$this->halt("link_id =".$this->link_id.", mysql connection failed");
		}
	
		$SelectResult = mysql_select_db($this->get_database(), $this->link_id);
		if(!$SelectResult)
		{
			$this->error_no = mysql_errno($this->link_id);
			$this->error = mysql_error($this->link_id);
			$this->halt("cannot select database <I>".$this->Database."</I>");
		}
	}
}

//*******************************************************************************************
function query($Query, $page_limit = 0)
{
	$this->open();
	if ( $page_limit > 0 )
	{	
		$sp = ($page_limit-1) * $this->paging_size;
		//$lp = $page_limit * $this->paging_size;
		$lp = $this->paging_size;
		$Query .= " Limit $sp,$lp";
		//echo $Query;
	}
	#echo $Query."<br>";
	$this->rs = mysql_query($Query, $this->link_id);
	$this->error_no = mysql_errno();
	$this->error = mysql_error();
	if (!$this->rs && $this->error_no!=1062) //for duplicate record error
	{
		$this->halt("<BR>Invalid SQL: ".$Query);
	}
return $this->rs;
}
//*******************************************************************************************
function rs_array($Query)
{ 

	$this->open();
	if (isset($this->rsarr))
	{	if(is_resource($this->rsarr))
		{		mysql_free_result($this->rsarr);
		}
	}

	if(!$this->rsarr=mysql_query($Query))
	{	/*echo "Unable to Process Query<Br>Query is : <Hr>";
		print $Qry;
		echo "<HR>";
		*/
		return false;
	}
	else
	{
		//create the array
		$this->ci = 0;
		while ( $this->arr_row = mysql_fetch_array($this->rsarr) )
		{	$this->rs_arr[$this->ci] = $this->arr_row;
			$this->ci++;
		}
	
		return ($this->rs_arr);
	}
}
//*******************************************************************************************
function rsset()
{
	$this->record = mysql_fetch_array($this->rs);
	$this->error_no = mysql_errno();
	$this->error = mysql_error();
	$stat = is_array($this->record);
	if (!$stat)
	{
		mysql_free_result($this->rs);
		$this->rs = 0;
	}
return $this->record;
}

//*******************************************************************************************
function rows()
{
if ( isset($this->rs) && is_resource($this->rs) )
	return mysql_num_rows($this->rs);
else
	false;
}

//*******************************************************************************************  
function aff_rows() //affected rows
{
	return mysql_affected_rows($this->link_id);
}
  
//*******************************************************************************************
function free_results()
{
	if($this->rs != 0) mysql_freeresult($this->rs);
}

//*******************************************************************************************
function db_close()
{
	if($this->link_id != 0) mysql_close($this->link_id);
}

//*******************************************************************************************
function query_pagger($total_records, $current_page, $link_str, $link_css = "")
{
	if ( $total_records > 0 && $current_page > 0 )
	{
		//echo $total_records,"<BR>",$current_page;
		//if ( $no_rows <= $this->paging_size )
		$no_pages = ceil($total_records / $this->paging_size);
		//echo "<BR>",$no_pages;
		//echo get_verbage("PAGGING_TEXT");
		for($i = 1; $i <= $no_pages; $i++)
		{
			if ($i == $current_page)
				echo "<b>$i</b>";
			else
				echo "<a href='$link_str&page_no=$i' class='$link_css'>$i</a>";

			if ($total_records > $i*$this->paging_size ) echo "&nbsp;|&nbsp;";
		}
	}
}
//*******************************************************************************************

}
?>