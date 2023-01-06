<!-- /** menu 01 -->
<div id="Sidenav" class="menu-wrap">
  	<h1>
		<a href="#">
	  		<img src="/images/common/bis_logo_White.svg" alt="BIS 로고">
	  		통합 관리 시스템 (CMI)
		</a>
  	</h1>
  	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fa-nav"></i>메뉴닫기</a><a name="btnExpandLeftMenu" accesskey="Q"></a>
  	<ul class="accordion" id="accordion">
<?php
	$idx = array_search($menuNo[0], array_column($arrSystemMenu, 'menu_no0'));

	$arrSubMenu = $arrSystemMenu[$idx]["menu1"];
	for($i=0;$i<count($arrSubMenu);$i++) {
?>
		<li class="accordion_list <?=$menuNo[1]==$arrSubMenu[$i]["menu_no1"]?"open":""?>">
		<div class="link" accordion_menu_no="<?=$arrSubMenu[$i]["menu_no1"]?>"><?=$arrSubMenu[$i]["title"]?><i class="fa fa-chevron-down"></i></div>
			<ul class="submenu" style="<?=$menuNo[1]==$arrSubMenu[$i]["menu_no1"]?"display:block;":""?>">
<?php
		for($j=0;$j<count($arrSubMenu[$i]["menu2"]);$j++) {
?>
				<li><a href="<?=$arrSubMenu[$i]["menu2"][$j]["url"]?>" class="<?=($menuNo[1]==$arrSubMenu[$i]["menu_no1"] && $menuNo[2]==$arrSubMenu[$i]["menu2"][$j]["menu_no2"])?"active":""?>"><?=$arrSubMenu[$i]["menu2"][$j]["title"]?></a></li>
<?php
		}			
?>
			</ul>
		</li>
<?php		
	}
?>
	</ul>
</div>
<!-- /** menu 02 -->

<span onclick="openNav()" class="openBtn"><i class="fa-nav"></i>메뉴열기</span>
<script type="text/javascript">
	$(document).ready(function() {
		$(".link[accordion_menu_no=<?=$menuNo[1]?>]").trigger("dropdown");
	});

	$(document).on('click', 'a[name=btnExpandLeftMenu]', function() {
		if($('#Sidenav').css('width')=="0px") {
			openNav();
		} else {
			closeNav();
		}
	});
</script>