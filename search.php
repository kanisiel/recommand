<html>
<head>
<title>본문</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">
function getValue(id){
	var x=document.getElementById(id);
	var form=document.getElementById("link");
	var input=form.keyword;
	input.value=x.innerHTML;
	form.submit();
  }
</script>
<?
	$_zb_url = "http://kanisiel.kr.pe/bbs/";
	$_zb_path = "H:/APM_Setup/htdocs/bbs/";
	include $_zb_path."outlogin.php"; 

	//검색하지 않을 게시판 리스트
	$skip_board = "cugain_board_trashbox/cugain_board_guest"; 

	//검색서비스 테이블
	$search_table = "zetyx_search_table";


	//문자열 치환 함수 정의
	function replace_string($source, $target) {
		$extract_target = strtok($target, " ");
		while($extract_target) {
			$source = eregi_replace($extract_target,"<font color=red style='font-size: 9pt'>$extract_target</font>",del_html(stripslashes($source)));
			$extract_target = strtok (" ");
		}
		$source = str_replace("&lt;","<",$source);
		$source = str_replace("&gt;",">",$source);
		return $source;
	}

	//db connection 검사
	if($connect == null)
		$connect=dbconn();

	$keykind[0]="subject";
	$keykind[1]="memo";

?>
</head>
<body>
<link href="css/fonts.css" media="all" rel="stylesheet" type="text/css" />
<link href="css/stylesheet.css" media="all" rel="stylesheet" type="text/css" />
<table id="search_top" width="100%" height="50" border="0" cellpadding="0" cellspacing="0" align=center>
	<tr>
		<td>
		<form action=<?=$PHP_SELF?> method=post>
		<table id="search_top1" width="1050" height="50" border="0" cellpadding="0" cellspacing="0" align=center>
			<tr height="119">
				<td width="100"><p align=center><a href="body.html" target="_self"><img src="images/logo_s.gif" style="width:83px;height:45px;align:center;" alt=""></a></p></td>
				<td width="20"></td>
				<td width="500">
					<table id="search" width="500" height=30 border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="border-style:solid;border-width:3px;border-color:#4a7dcb;"><input id="keyword" name="keyword" type="text" style="width:100%;height:100%;font-size:18px;border:0;margin:0px;padding:4px;'" cellpadding="0" <? if($_POST["keyword"]){ ?> value=<? echo $_POST["keyword"]; } ?>></td>
							<td class="search" onclick="javascript:submit()" style="width:50px;background:#4a7dcb;cursor:hand;" align=center><input class="search_button" type="button" style="width:50px;background:#4a7dcb;cursor:hand;border-width:0px;" onclick="javascript:submit()" value="검색"></td>
						</tr>
					</table>
				</td>
				<td width="400" valign="middle"><p valign="middle" align=center><? print_outlogin("photune","1","10"); ?></p>
				</td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
	<tr height="1">
		<td colspan="4" style="background:#4a7dcb"></td>
	</tr>
</table>
<table id="search_body" width="100%" height="50" border="0" cellpadding="0" cellspacing="0" align=center>
	<tr height="100">
		<td valign="top">
		<table id="search_body" width="100%" border="0" cellpadding="0" cellspacing="0" align=center valign=top>
			<tr>
				<td valign="top">
			<?
 // DB처리용 함수
 function affect_db($keywords){
	 global $search_table;
	 global $connect;

	$query = "select * from $search_table where keyworld=\"".$keywords."\";";
	$table_result=mysql_query($query, $connect) or error(mysql_error());
	if($table_result){
		if(mysql_num_rows($table_result)){
			mysql_query("UPDATE $search_table SET time=".time().", recent=recent+1 ,amount=amount+1 WHERE keyworld='".$keywords."' LIMIT 1;",$connect) or error(mysql_error());		
		}else{
			mysql_query("INSERT INTO  $search_table (`no`, `keyworld`, `time`, `amount`) VALUES ( NULL , '".$keywords."', ".time()." ,'1' );",$connect) or error(mysql_error());		
		}
	}
 }


 // 실제 검색부분
if($keyword) {
	//해당 검색어로 검색되었던 이력이 있는지 검사후 DB에 반영
	affect_db($keyword);

	$comment_search=0;
	$s_que = "";
	$extract_keyword = strtok($keyword, " "); // 공백 입력시 OR 연산을 위한 변수
	while($extract_keyword) {
		//공백분리후 검색 이력 DB에 반영 ( 논의중 )
		//affect_db($extract_keyword);
		for($i=0;$i<=sizeof($keykind);$i++) {
			if($keykind[$i]) {
				if($keykind[$i]!="ismember") {
    				if(!$s_que){ 
						$s_que .= " where $keykind[$i] like '%$extract_keyword%' ";
						if($i>0)
						$s_que1 .= " where $keykind[$i] like '%$extract_keyword%' ";
					}else{ 
						$s_que .= " or $keykind[$i] like '%$extract_keyword%' ";
						if($i>0)
						$s_que1 .= " where $keykind[$i] like '%$extract_keyword%' ";
					}
				} else {
					if($userno) {
						if(!$s_que){ 
							$s_que .= " where $keykind[$i] = '$userno' ";
							if($i>0)
							$s_que1 .= " where $keykind[$i] = '$userno' ";
						}else {
							$s_que .= " or $keykind[$i] = '$userno' ";
							if($i>0)
							$s_que1 .= " or $keykind[$i] = '$userno' ";
						}
					}
				}
			}
			$table_name_result=mysql_query("select name, use_alllist, title from $admin_table order by name",$connect) or error(mysql_error());
		}
		$extract_keyword = strtok (" ");
	}
}

if($keyword&&$s_que ) {
	$rows=0;
	while($table_data=mysql_fetch_array($table_name_result)) {
		$table_title=$table_data[title];
		$table_name=$table_data[name];
		if(!strstr($skip_board, $table_name)) {
			if($table_data[use_alllist]) 
				$file="zboard.php"; else $file="view.php";

			// 본문
			//echo "select * from $t_board"."_$table_name $s_que";
			$result=mysql_query("select * from $t_board"."_$table_name $s_que", $connect) or error(mysql_error());
			if(mysql_num_rows($result)>0){
				$rows++;
				echo "<br>";

				while($data=mysql_fetch_array($result)) {
					flush();
					$data[subject] = replace_string($data[subject], $keyword);
?>

&nbsp;&nbsp; <font style='font-size: 10pt'>[<?=stripslashes($data[name])?>]</font>
<a href=bbs/<?=$file?>?id=<?=$table_name?>&no=<?=$data[no]?> target=_self><font style='font-size: 9pt'><?=$data[subject]?></font></a></b><br>
&nbsp;&nbsp; <font color=666666 style="font-size: 9pt"><?=cut_str($data[memo],100);?><br>
&nbsp;&nbsp; <font color=444444 style="font-size: 9pt"><?=$table_title?></font>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; <font color=666666 style="font-size: 9pt">(</font><font color=blue style="font-size: 9pt"><?=date("Y-m-d H:i:s",$data[reg_date])?></font> / <font color=green style="font-size: 9pt"><?=$data[ip]?>)</font>&nbsp;&nbsp;

<img src=bbs/images/t.gif border=0 height=20><Br>

<?
				}
			}
			mysql_free_result($result);

			/// 코멘트
			if($comment_search) {
				$result=mysql_query("select * from $t_comment"."_$table_name $s_que1", $connect) or error(mysql_error());
?>

<br><Br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/lastnode.gif"> <a href=bbs/zboard.php?id=<?=$table_name?> target=_self><font  size="2" style=font-family:tahoma;><?=$table_name?><b>게시판</b> 의 간단한 답글</font></a>
<br>
<?
				while($data=mysql_fetch_array($result)) {
					flush();
					$data[memo] = replace_string($data[memo], $keyword);
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font style='font-size: 10pt'>[<?=stripslashes($data[name])?>]</font>
<a href=bbs/<?=$file?>?id=<?=$table_name?>&no=<?=$data[parent]?> target=_self><font style='font-size: 9pt'><?=$data[memo]?></font></a> &nbsp;&nbsp;
<font color=666666 style="font-size: 9pt">(</font><font color=blue style="font-size: 9pt"><?=date("Y-m-d H:i:s",$data[reg_date])?></font> / <font color=green style="font-size: 9pt"><?=$data[ip]?>)</font>
<img src=bbs/images/t.gif border=0 height=20><Br><br>

<?
				}
			}
		}
	}
}
if($rows==0){
?>
			<tr>
				<td valign="top"><p class="search_result" style="margin-left:30px;" align="left"><font style="color:#d70000">'<?=$keyword;?>'</font>에 대한 검색결과가 없습니다.<br>
단어의 철자가 정확한지 확인해 보세요.<br>
한글을 영어로 혹은 영어를 한글로 입력했는지 확인해 보세요.<br>
검색어의 단어 수를 줄이거나, 보다 일반적인 검색어로 다시 검색해 보세요.<br>
두 단어 이상의 검색어인 경우, 띄어쓰기를 확인해 보세요.<br>
검색 옵션을 변경해서 다시 검색해 보세요.</p></td>
			</tr>			
<?
}
if($connect != null) {
	mysql_close($connect);
	$connect="";
}
?>
					</td>
				</tr>
			</table>
		</td>
		<td width="250" valign="top">
			<table width="100%" cellpadding=0 cellspacing=0 class="rank_table" style="margin-top:10px;margin-bottom:10px;">
			<tr><td>
				<table id="recent_table" name="recent_table" width="100%" cellpadding=0 cellspacing=0>
					<tr>
						<td colspan=3 class="rank_title" style="background-color:#4a7dcb;"><p style="margin-left:10px">최근 인기 검색어</p></td>
					</tr>
<?
	for($i=0;$i<10;$i++){
	echo "<tr>
				<td id=\"recent_no".($i)."\" name=\"recent_no".($i)."\" class=\"rank\" style=\"text-align:center;width:30px;\"></td>
				<td id=\"recent_keyword".($i)."\" name=\"recent_keyword".($i)."\" class=\"rank\" style=\"padding-left:5px;cursor:hand;\" onclick=\"getValue(this.id)\"></td>
				<td id=\"recent_rank".($i)."\" name=\"recent_rank".($i)."\" class=\"rank\" style=\"text-align:center\"></td>
			</tr>";
	}
?>
				</table>
			</td></tr>
			</table>
			<table width="100%" cellpadding=0 cellspacing=0 class="rank_table" style="margin-top:10px;margin-bottom:10px;">
			<tr><td>
				<table id="total_table" name="total_table" width="100%" cellpadding=0 cellspacing=0>
					<tr>
						<td colspan=3 class="rank_title" style="background-color:#4a7dcb;"><p style="margin-left:10px">종합 인기 검색어</p></td>
					</tr>
<?
	for($i=0;$i<10;$i++){
	echo "<tr>
				<td id=\"total_no".($i)."\" name=\"total_no".($i)."\" class=\"rank\" style=\"text-align:center;width:30px;\"></td>
				<td id=\"total_keyword".($i)."\" name=\"total_keyword".($i)."\" class=\"rank\" style=\"padding-left:5px;cursor:hand;\" onclick=\"getValue(this.id)\"></td>
				<td id=\"total_rank".($i)."\" name=\"total_rank".($i)."\" class=\"rank\" style=\"text-align:center\"></td>
			</tr>";
	}
?>
				</table>
			</td></tr>
			</table>
		</td>
	</tr>
</table>
<table id="search_bottom" width="100%" height="50" border="0" cellpadding="0" cellspacing="0" align=center>
	<tr height="2">
		<td valign=top background="images/bottom_bg.gif"></td>
	</tr>
	<tr>
		<td valign=top><p class="bottommenu" align=center>이용약관&nbsp;&nbsp;&nbsp;개인정보취급방침&nbsp;&nbsp;&nbsp;이메일무단수집거부&nbsp;&nbsp;&nbsp;청소년정책</p></td>
	</tr>
</table>
<form id="link" name="link" method="POST">
<input id="keyword" name="keyword" type=hidden>
</form>
<iframe id="rankFrame" src="search_rank.php" style="border-width:0px;width:0px;height:0px;cellpadding:0px;cellspacing:0px;">
</body>
</html>