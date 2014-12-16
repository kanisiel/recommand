<html>
<head>
<title>본문</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?
	$_zb_url = "http://kanisiel.kr.pe/bbs/";
	$_zb_path = "H:/APM_Setup/htdocs/bbs/";
	include $_zb_path."outlogin.php";
	$search_table = "zetyx_search_table";

	$type=array( '0'=> "total", '1' => "recent", 'total' => "amount", 'recent' => "recent" );
	$result=mysql_query("select * from ".$search_table." where 1 order by amount desc;", $connect) or error(mysql_error());
	$index=1;
	while($data=mysql_fetch_array($result)){
		$query="update ".$search_table." set total_ranked=".$index." where no=".$data[no].";";
		mysql_query($query, $connect) or error(mysql_error());
		$index++;
	}
	$result=mysql_query("select * from ".$search_table." where 1 order by recent desc;", $connect) or error(mysql_error());
	$index=1;
	while($data=mysql_fetch_array($result)){
		$query="update ".$search_table." set ranked=".$index." where no=".$data[no].";";
		mysql_query($query, $connect) or error(mysql_error());
		$index++;
	}
	
	function showArray($data, $sub){//, $parent){
		$tabs="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		foreach($data as $key=>$value){
			if($sub>=2&&is_int($key)) continue;
			for($i=0;$i<$sub;$i++){
				echo $tabs;
			}
			//if($parent) echo $parent."-";
			echo $key . " : " . $value . "</br>";
			if(is_array($value)){
				showArray($value, $sub+1);//, $key);
			}
		}
	}

	function setDataArray($index, $connect){ 
		global $search_table;
		global $connect;
		global $type;
		
		$query="select * from $search_table where 1 order by ".$type[$index]." desc limit 10;";
		$result=mysql_query($query, $connect) or error(mysql_error());
		if(mysql_num_rows($result)){
			$index=0;
			while($data=mysql_fetch_array($result)){
				$buffer='';
				if($index=="total"){
					mysql_query("UPDATE $search_table SET ranked=".($index+1)." WHERE keyworld='".$data[keyworld]."' LIMIT 1;",$connect) or error(mysql_error());
				}
				foreach($data as $key=>$value){
					if(!is_int($key)){
						if(!$buffer) $buffer = $value;
						else if($buffer) $buffer.=",".$value;
					}
				}
				$total_data[$index]=$buffer;
				$index++;
			}
		}
		return $total_data;
	}

	function showValue($data_array, $index){
		foreach($data_array as $key=>$value){
			if(is_array($value)){
				showValue($value, $key);
			} else {
				switch($index){
				case 0:
					$name="totalrank";
					break;
				case 1:
					$name="recentrank";
					break;
				default:
					$name="default";
					break;
				}
				$input = "<input id=\"".$name."\" name=\"".$name."\" type=\"text\" value=\"".$value."\"><br>";
				echo $input;
			}
		}
	}

	//마지막 검색 후 6시간	이 경과한 검색어는 최근 검색수를 0으로 초기화
	mysql_query("update $seacrh_table set recent=0 where time < ".(time()-21600).";");

	//총 검색수 결과와 최근 검색수 결과 query
	for($i=0;$i<sizeof($type)-2;$i++){
		$total_data[$i]=setDataArray($type[$i], $connect);
	}

//showArray($total_data, 0);
	
?>
</head>
<body>
<form id="ranking" name="ranking">
<?
showValue($total_data,null);
?>
<input id="test" name="test" type="text" value="test">
</form>
<script>
	var form = parent.document.getElementById("rank_form");
	for(var i = 0; i < 10 ; i++){
		var value = document.ranking.totalrank[i].value;
		var dataArray = value.split(",");
		var img="";
		var updown=0;
		var table1 = parent.document.getElementById("total_no"+i);
		table1.innerHTML=i+1;
		var table2 = parent.document.getElementById("total_keyword"+i);
		table2.innerHTML=dataArray[1];
		var table3 = parent.document.getElementById("total_rank"+i);
		var updown = i - dataArray[5] + 1;
		if(updown > 0) img = "<img src=\"images/down.gif\" style=\"border-width:0px;\">&nbsp;"
		else if(updown == 0) img = "<img src=\"images/stay.gif\" style=\"border-width:0px;\">&nbsp;"
		else if(updown < 0) img = "<img src=\"images/up.gif\" style=\"border-width:0px;\">&nbsp;"
		table3.innerHTML=img+updown;
	}
	for(var i = 0; i < 10 ; i++){
		var value = document.ranking.recentrank[i].value;
		var dataArray = value.split(",");
		var img="";
		var updown=0;
		var table1 = parent.document.getElementById("recent_no"+i);
		table1.innerHTML=i+1;
		var table2 = parent.document.getElementById("recent_keyword"+i);
		table2.innerHTML=dataArray[1];
		var table3 = parent.document.getElementById("recent_rank"+i);
		var updown = i - dataArray[5] + 1;
		if(updown > 0) img = "<img src=\"images/down.gif\" style=\"border-width:0px;\">&nbsp;"
		else if(updown == 0) img = "<img src=\"images/stay.gif\" style=\"border-width:0px;\">&nbsp;"
		else if(updown < 0) img = "<img src=\"images/up.gif\" style=\"border-width:0px;\">&nbsp;"
		table3.innerHTML=img+updown;
	}
</script>
