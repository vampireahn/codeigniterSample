<h2>비밀번호 변경</h2>
<?php if ($msg = $this->session->flashdata('error')): ?>
	<div style="color:red;"><?=$msg?></div>
<?php endif; ?>
<form action="/member/changePasswordProc" method="post">
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	<div><label>현재 비밀번호</label><input type="password" name="currentPassword" required></div>
	<div><label>새 비밀번호</label><input type="password" name="newPassword" required></div>
	<div><label>새 비밀번호 확인</label><input type="password" name="newPasswordConfirm" required></div>
	<button type="submit">변경</button>
</form>
