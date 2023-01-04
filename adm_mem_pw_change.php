<!-- /*********************** POPUP Modal 비밀번호 변경 -->
<div id="modal">
	<div class="modal-bg">
		<div class="modal-cont">
			<h2>비밀번호 변경</h2>
			<div class="pass-change-wrap">
				<form name="chgPWForm" action="/adm_mem_pw_change_act.php" method="post">
					<input type="hidden" name="chgPW_mode" value="CHANGE_PW" />
					<input type="hidden" name="chgPW_auto_defense" />
					<table class="table-write">
						<colgroup>
							<col width="28%">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th class="required">ID</th>
								<td><?=LoginManager::getManagerLoginInfo("userid")?></td>
							</tr>
							<tr>
								<th class="required">이름</th>
								<td><?=LoginManager::getManagerLoginInfo("name")?></td>
							</tr>
							<tr>
								<th class="required">기존 비밀번호</th>
								<td>
									<input type="password" name="chgPW_passwd_old" class="input01" placeholder="기존 비밀번호" >
								</td>
							</tr>
							<tr>
								<th class="required">변경 비밀번호</th>
								<td>
									<input type="password" name="chgPW_passwd_new" class="input01" placeholder="비밀번호" >
								</td>
							</tr>
							<tr>
								<th class="required">변경 비밀번호 확인</th>
								<td>
									<input type="password" name="chgPW_passwd_new_cfm" class="input01" placeholder="비밀번호 확인">
								</td>
							</tr>
						</tbody>
					</table>
					<div class="button-center">
						<a href="#" class="close close_modal button lineGray2 large">취소</a>
						<a href="#" name="btnChangePW" class="close button line-basic large">저장</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
			
<script src="/js/ValidCheck.js"></script>	
<script type="text/javascript">
var chgPW_mc_consult_submitted = false;

$(document).on("click","a[name=btnChangePW]",function() {
	if(chgPW_mc_consult_submitted == true) { return false; }
	
	var f = document.chgPWForm;

	if ( VC_inValidText(f.chgPW_passwd_old, "기존 비밀번호") ) return false;
    if ( VC_inValidText(f.chgPW_passwd_new, "변경 비밀번호") ) return false;
    if ( VC_inValidText(f.chgPW_passwd_new_cfm, "변경 비밀번호 확인") ) return false;

	if(f.chgPW_passwd_new.value != f.chgPW_passwd_new_cfm.value) {
        alert("[변경 비밀번호 확인]이 일치하지 않습니다.    ");
        f.chgPW_passwd_new_cfm.focus();
        return;
    }

	//var reg_engnum = /^[A-Za-z0-9+]{4,20}$/;
	var reg_engnum = /^[A-Za-z0-9+\d$@$!%*#?&]{4,20}$/;
	
    if (!reg_engnum.test(f.chgPW_passwd_new.value)) {
        alert("비밀번호는 숫자와 영문, 일부 특수문자($@$!%*#?&)만 가능하며, 4~20자리여야 합니다.    ");
        f.chgPW_passwd.focus();
        return;
    }

	f.chgPW_auto_defense.value = "identicharmc!@";
	chgPW_mc_consult_submitted = true;

    f.submit();	

    return false;
});
</script>