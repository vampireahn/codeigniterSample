<h2>로그인</h2>
<?php
	// 1. 폼 유효성 검사 오류를 표시합니다. (예: 아이디나 비밀번호를 입력하지 않은 경우)
	echo validation_errors('<div style="color:red;">', '</div>');

	// 2. 컨트롤러에서 전달된 로그인 실패 오류를 표시합니다. (예: 아이디나 비밀번호가 틀린 경우)
	if (isset($error))
	{
		echo '<div style="color:red;">' . html_escape($error) . '</div>';
	}

	// 3. 회원가입 성공 등 다른 페이지에서 전달된 일회성 메시지를 표시합니다.
	if ($msg = $this->session->flashdata('notice'))
	{
		echo '<div style="color:blue;">' . html_escape($msg) . '</div>';
	}
?>
<form action="/auth/loginProc" method="post">
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	<div><label>아이디</label><input type="text" name="userId" value="<?php echo set_value('userId'); ?>" required></div>
	<div><label>비밀번호</label><input type="password" name="password" required></div>
	<button type="submit">로그인</button>
</form>
