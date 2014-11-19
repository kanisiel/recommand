<script language='JavaScript'>
	var select_obj;
	function ZB_layerAction(name,status) { 
		var obj=document.all[name];
		var _tmpx,_tmpy, marginx, marginy;
		_tmpx = event.clientX + parseInt(obj.offsetWidth);
		_tmpy = event.clientY + parseInt(obj.offsetHeight);
		_marginx = document.body.clientWidth - _tmpx;
		_marginy = document.body.clientHeight - _tmpy ;
		if(_marginx < 0)
			_tmpx = event.clientX + document.body.scrollLeft + _marginx ;
		else
			_tmpx = event.clientX + document.body.scrollLeft ;
		if(_marginy < 0)
			_tmpy = event.clientY + document.body.scrollTop + _marginy +20;
		else
			_tmpy = event.clientY + document.body.scrollTop ;
		obj.style.posLeft=_tmpx-13;
		obj.style.posTop=_tmpy-12;
		if(status=='visible') {
			if(select_obj) {
				select_obj.style.visibility='hidden';
				select_obj=null;
			}
			select_obj=obj;
		}else{
			select_obj=null;
		}
		obj.style.visibility=status; 
	}


	function print_ZBlayer(name, homepage, mail, member_no, boardID, writer, traceID, traceType, isAdmin, isMember) {
		var printHeight = 0;
		var printMain="";
	
		if(homepage) {
			printMain = "<tr onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='ffffff' onMousedown=window.open('"+homepage+"');><td width=90 style=font-family:verdana;font-size:10px;color:222222 height=20 nowrap  backgroundColor='FFFDF3'>&nbsp;<img src=images/n_homepage.gif border=0 align=absmiddle>&nbsp;&nbsp;Homepage&nbsp;&nbsp;</td></tr><tr><td height=1></td></tr>";
			printHeight = printHeight + 15;
		}
		if(mail) {
			printMain = printMain +	"<tr onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='ffffff' onMousedown=window.open('open_window.php?mode=m&str="+mail+"','ZBremote','width=1,height=1,left=1,top=1');><td width=90 style=font-family:verdana;font-size:10px;color:222222 height=20 nowrap  backgroundColor='FFFDF3'>&nbsp;<img src=images/n_mail.gif border=0 align=absmiddle>&nbsp;&nbsp;E-mail&nbsp;&nbsp;</td></tr><td height=1></td></tr>";
			printHeight = printHeight + 15;
		}
		if(member_no) {
			if(isMember) {
				printMain = printMain +	"<tr onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='ffffff' onMousedown=window.open('view_info.php?member_no="+member_no+"','view_info','width=400,height=510,toolbar=no,scrollbars=yes');><td width=90 style=font-family:verdana;font-size:10px;color:222222 height=20 nowrap  backgroundColor='FFFDF3'>&nbsp;<img src=images/n_memo.gif border=0 align=absmiddle>&nbsp;&nbsp;Message&nbsp;&nbsp;</td></tr><td height=1></td></tr>";
				printHeight = printHeight + 15;
			}
			printMain = printMain +	"<tr onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='ffffff' onMousedown=window.open('view_info2.php?member_no="+member_no+"','view_info','width=400,height=510,toolbar=no,scrollbars=yes');><td width=90 style=font-family:verdana;font-size:10px;color:222222 height=20 nowrap  backgroundColor='FFFDF3'>&nbsp;<img src=images/n_information.gif border=0 align=absmiddle>&nbsp;&nbsp;Info&nbsp;&nbsp;</td></tr><td height=1></td></tr>";
			printHeight = printHeight + 15;
		}
		if(writer) {
			printMain = printMain +	"<tr onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='ffffff' onMousedown=location.href='zboard.php?id="+boardID+"&sn1=on&sn=on&ss=off&sc=off&keyword="+writer+"';><td width=90 style=font-family:verdana;font-size:10px;color:222222 height=20 nowrap  backgroundColor='FFFDF3'>&nbsp;<img src=images/n_search.gif border=0 align=absmiddle>&nbsp;&nbsp;Search&nbsp;&nbsp;</td></tr><td height=1></td></tr>";
			printHeight = printHeight + 15;
		}
		if(isAdmin) {
			if(member_no) {
				printMain = printMain +	"<tr onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='ffffff' onMousedown=window.open('open_window.php?mode=i&str="+member_no+"','ZBremote','width=1,height=1,left=1,top=1');><td width=90 style=font-family:verdana;font-size:10px;color:222222 height=20 nowrap  backgroundColor='FFFDF3'>&nbsp;<img src=images/n_modify.gif border=0 align=absmiddle>&nbsp;&nbsp;<font color=2222224>Moddfy&nbsp;&nbsp;</td></tr><td height=1></td></tr>";
				printHeight = printHeight + 15;
			}
			printMain = printMain +	"<tr onMouseOver=this.style.backgroundColor='#f9f9f9' onMouseOut=this.style.backgroundColor='ffffff' onMousedown=window.open('open_window.php?mode="+traceType+"&str="+traceID+"','ZBremote','width=1,height=1,left=1,top=1');><td width=90 style=font-family:verdana;font-size:10px;color:222222 height=20 nowrap  backgroundColor='FFFDF3'>&nbsp;<img src=images/n_relationlist.gif border=0 align=absmiddle>&nbsp;&nbsp;Rearch</font>&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 15;
		
		}
		var printHeader = "<div id='"+name+"' style='position:absolute; left:10px; top:25px; width:127; height: "+printHeight+"; z-index:1; visibility: hidden' onMousedown=ZB_layerAction('"+name+"','hidden')><table border=0><tr><td colspan=3 onMouseover=ZB_layerAction('"+name+"','hidden') height=3></td></tr><tr><td width=5 onMouseover=ZB_layerAction('"+name+"','hidden') rowspan=2>&nbsp;</td><td height=5></td></tr><tr><td><table style=cursor:hand border='0' cellspacing='3' cellpadding='0' bgcolor='eeeeee' width=100% height=100%><tr><td valign=top><table style=cursor:hand border='0' cellspacing='1' cellpadding='0' bgcolor='dddddd' width=100% height=100%><tr><td valign=top bgcolor=white><table border=0 cellspacing=0 cellpadding=3 width=100% height=100%>  <tr><td height=5></td></tr>";
		var printFooter = " <tr><td height=5></td></tr></table></td></tr></table></td></tr></table></td><td width=5 rowspan=2 onMouseover=ZB_layerAction('"+name+"','hidden')>&nbsp;</td></tr><tr><td colspan=3 height=10 onMouseover=ZB_layerAction('"+name+"','hidden')></td></tr></table></div>";
	
		document.writeln(printHeader+printMain+printFooter);
	}
</script>
