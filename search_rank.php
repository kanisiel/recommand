<?
	$_zb_url = "http://kanisiel.kr.pe/bbs/";
	$_zb_path = "H:/APM_Setup/htdocs/bbs/";
	include $_zb_path."outlogin.php";
	$search_table = "zetyx_search_table";

	//마지막 검색 후 6시간	이 경과한 검색어는 최근 검색수를 0으로 초기화
	mysql_query("update $seacrh_table set recent=0 where time < ".(time()-21600).";");

	//총 검색수 결과와 최근 검색수 결과 query
	$total_result=mysql_query("select * from $search_table where 1 order by amount desc limit 10;",$connect) or error(mysql_error());
	$recent_result=mysql_query("select * from $search_table where 1 order by recent desc limit 10;",$connect) or error(mysql_error());
	if(mysql_num_rows($total_result)){
		$index=0;
		while($data=mysql_fetch_array($total_result)){
			$buffer='';
			for($i=0; $i < sizeof($data); $i++){
				if($buffer) $buffer.=$data[$i];
				else $buffer = $data[$i];
			}
			$total_data[0][$index]=$data;
			$index++;
		}
	}
	if(mysql_num_rows($recent_result)){
		$index=0;
		while($data=mysql_fetch_array($recent_result)){
			$buffer='';
			for($i=0; $i < sizeof($data); $i++){
				if($buffer) $buffer.=$data[$i];
				else $buffer = $data[$i];
			}
			$total_data[1][$index]=$data;
			$index++;
		}
	}
	print_r($total_data);
?>