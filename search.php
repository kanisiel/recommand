<?
	$_zb_url = "http://kanisiel.kr.pe/bbs/";
	$_zb_path = "H:/APM_Setup/htdocs/bbs/";
	include $_zb_path."outlogin.php"; 

	//�˻����� ���� �Խ��� ����Ʈ
	$skip_board = "cugain_board_trashbox/cugain_board_guest"; 

	//�˻����� ���̺�
	$search_table = "zetyx_search_table";

	//���ڿ� ġȯ �Լ� ����
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

	//db connection �˻�
	if($connect == null)
		$connect=dbconn();

	$keykind[0]="subject";
	$keykind[1]="memo";

?>
<link href="css/fonts.css" media="all" rel="stylesheet" type="text/css" />
<table id="search_top" width="100%" height="50" border="0" cellpadding="0" cellspacing="0" align=center>
	<tr>
		<td>
		<form action=<?=$PHP_SELF?> method=post>
		<table id="search_top1" width="1050" height="50" border="0" cellpadding="0" cellspacing="0" align=center>
			<tr height="119">
				<td width="100"><p align=center><img src="images/logo_s.gif" style="width:83px;height:45px;align:center;" alt=""></p></td>
				<td width="20"></td>
				<td width="500">
					<table id="search" width="500" height=30 border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="border-style:solid;border-width:3px;border-color:#4a7dcb;"><input id="keyword" name="keyword" type="text" style="width:100%;height:100%;font-size:18px;border:0;margin:0px;padding:4px;'" cellpadding="0" <? if($_POST["keyword"]){ ?> value=<? echo $_POST["keyword"]; } ?>></td>
							<td class="search" onclick="javascript:submit()" style="width:50px;background:#4a7dcb;cursor:hand;" align=center><input class="search_button" type="button" style="width:50px;background:#4a7dcb;cursor:hand;border-width:0px;" onclick="javascript:submit()" value="�˻�"></td>
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
	<tr>
		<td>
		<table id="search_body" width="80%" height="80%" border="0" cellpadding="0" cellspacing="0" align=center>
			<tr>
				<td>
				</td>
			</tr>

<?
 // DBó���� �Լ�
 function affect_db($keywords){
	//echo "select * from $search_table where keyword='".$keywords."';";
	/*$table_result=mysql_query("select * from $search_table where keyword='".$keywords."';",$connect) or error(mysql_error());
	if(mysql_num_rows($table_result)>0){
		mysql_query("UPDATE $search_table SET amount=amount+1 WHERE keyword='".$keywords."' LIMIT 1;",$connect) or error(mysql_error());		
	}else{
		mysql_query("INSERT INTO  $search_table (`no`, `keyworld`, `amount`) VALUES ( NULL , '".$keywords."',  '1' );",$connect) or error(mysql_error());		
	}*/
 }
 // ���� �˻��κ�
if($keyword) {
	//�ش� �˻���� �˻��Ǿ��� �̷��� �ִ��� �˻��� DB�� �ݿ�
	affect_db($keyword);

	$comment_search=1;
	$s_que = "";
	$extract_keyword = strtok($keyword, " "); // ���� �Է½� OR ������ ���� ����
	while($extract_keyword) {
		//����и��� �˻� �̷� DB�� �ݿ� ( ������ )
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
			$table_name_result=mysql_query("select name, use_alllist from $admin_table order by name",$connect) or error(mysql_error());
		}
		$extract_keyword = strtok (" ");
	}
	echo "query : $s_que";
}

if($keyword&&$s_que ) {
	while($table_data=mysql_fetch_array($table_name_result)) {
		$table_name=$table_data[name];
		if(!strstr($skip_board, $table_name)) {
			if($table_data[use_alllist]) 
				$file="zboard.php"; else $file="view.php";

			// ����
			//echo "select * from $t_board"."_$table_name $s_que";
			$result=mysql_query("select * from $t_board"."_$table_name $s_que", $connect) or error(mysql_error());
?>

<br><br><br>

&nbsp;&nbsp;<img src="btn2.jpg" width="11" height="11"> <a href=bbs/zboard.php?id=<?=$table_name?> target=_self><font size=2 style=font-family:tahoma; color=black><?=$table_name?>&nbsp;<b>�Խ���</b></font></a><br>
<?
			while($data=mysql_fetch_array($result)) {
				flush();
				$data[subject] = replace_string($data[subject], $keyword);
?>

&nbsp;&nbsp; <font style='font-size: 10pt'>[<?=stripslashes($data[name])?>]</font>
<a href=bbs/<?=$file?>?id=<?=$table_name?>&no=<?=$data[no]?> target=_self><font style='font-size: 9pt'><?=$data[subject]?></font></a></b> 
&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
<font color=666666 style="font-size: 9pt">(</font><font color=blue style="font-size: 9pt"><?=date("Y-m-d H:i:s",$data[reg_date])?></font> / <font color=green style="font-size: 9pt"><?=$data[ip]?>)</font>

<img src=bbs/images/t.gif border=0 height=20><Br>

<?
			}
			mysql_free_result($result);

			/// �ڸ�Ʈ
			if($comment_search) {
				$result=mysql_query("select * from $t_comment"."_$table_name $s_que1", $connect) or error(mysql_error());
?>

<br><Br><br>
&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/lastnode.gif"> <a href=bbs/zboard.php?id=<?=$table_name?> target=_self><font  size="2" style=font-family:tahoma;><?=$table_name?><b>�Խ���</b> �� ������ ���</font></a>
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
else{
?>
			<tr>
				<td><p class="search_result" style="margin-left:30px;" align="left"><font style="color:#d70000">''</font>�� ���� �˻������ �����ϴ�.<br>
�ܾ��� ö�ڰ� ��Ȯ���� Ȯ���� ������.<br>
�ѱ��� ����� Ȥ�� ��� �ѱ۷� �Է��ߴ��� Ȯ���� ������.<br>
�˻����� �ܾ� ���� ���̰ų�, ���� �Ϲ����� �˻���� �ٽ� �˻��� ������.<br>
�� �ܾ� �̻��� �˻����� ���, ���⸦ Ȯ���� ������.<br>
�˻� �ɼ��� �����ؼ� �ٽ� �˻��� ������.</p></td>
			</tr>
<?
}
if($connect != null) {
	mysql_close($connect);
	$connect="";
}
?>
			</table>
		</td>
	</tr>
	<tr height="2">
		<td colspan="4" valign=top background="images/bottom_bg.gif"></td>
	</tr>
	<tr>
		<td valign=top><p class="bottommenu" align=center>�̿���&nbsp;&nbsp;&nbsp;����������޹�ħ&nbsp;&nbsp;&nbsp;�̸��Ϲ��ܼ����ź�&nbsp;&nbsp;&nbsp;û�ҳ���å</p></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" height="100%">
<tr><td></td></tr>
</table>

</body>
</html>