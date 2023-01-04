<!-- /** menu 01 -->
<div id="Sidenav" class="menu-wrap">
  	<h1>
		<a href="#">
	  		<img src="/icm/images/common/bis_logo_White.svg" alt="BIS 로고">
	  		통합 관리 시스템 (IMS)
		</a>
  	</h1>
  	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()" accesskey="Q"><i class="fa-nav"></i>메뉴닫기</a>

  	<ul class="accordion" id="accordion">
		<li class="accordion_list <?=$menuNo[1]=="1"?"open":""?>">
			<div class="link" accordion_menu_no="1">계약 통계<i class="fa fa-chevron-down"></i></div>
			<ul class="submenu" style="<?=$menuNo[1]=="1"?"display:block;":""?>">
				<li><a href="/icm/admin/contract_list.php" class="<?=($menuNo[1]=="1" && $menuNo[2]=="1")?"active":""?>">계약 내역</a></li>
			</ul>
		</li>
		<li class="accordion_list <?=$menuNo[1]=="2"?"open":""?>">
			<div class="link" accordion_menu_no="2">계약 관리<i class="fa fa-chevron-down"></i></div>
			<ul class="submenu" style="<?=$menuNo[1]=="2"?"display:block;":""?>">
				<li><a href="/icm/admin/contract/upload_sales_data.php" class="<?=($menuNo[1]=="2" && $menuNo[2]=="1")?"active":""?>">계약 업로드</a></li>
				<li><a href="/icm/admin/contract_adm_list.php" class="<?=($menuNo[1]=="2" && $menuNo[2]=="2")?"active":""?>">계약 작업</a></li>
			</ul>
		</li>
		<li class="accordion_list <?=$menuNo[1]=="3"?"open":""?>">
			<div class="link" accordion_menu_no="3">기초 정보<i class="fa fa-chevron-down"></i></div>
			<ul class="submenu" style="<?=$menuNo[1]=="3"?"display:block;":""?>">
				<li><a href="/icm/admin/system/adm_mem_list.php" class="<?=($menuNo[1]=="3" && $menuNo[2]=="1")?"active":""?>">회원 관리</a></li>
				<li><a href="/icm/admin/contract/customer_list.php" class="<?=($menuNo[1]=="3" && $menuNo[2]=="2")?"active":""?>">거래처 관리</a></li>
			</ul>
		</li>
  	</ul>
</div>
<!-- /** menu 02 -->


<span onclick="openNav()" class="openBtn" accesskey="R"><i class="fa-nav"></i>메뉴열기</span>
<script type="text/javascript">
	$(document).ready(function() {
		$(".link[accordion_menu_no=<?=$menuNo[1]?>]").trigger("dropdown");
	});
</script>