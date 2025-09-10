<div class="row justify-content-center">
	<div class="col-md-6 col-lg-5">
		<h2 class="text-center mb-4">로그인</h2>

		<?php
		// 1. 폼 유효성 검사 오류를 표시합니다.
		if (validation_errors()): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo validation_errors(); ?>
			</div>
		<?php endif; ?>

		<?php // 2. 컨트롤러에서 전달된 로그인 실패 오류를 표시합니다.
		if (isset($error)): ?>
			<div class="alert alert-danger" role="alert">
				<?php echo html_escape($error); ?>
			</div>
		<?php endif; ?>

		<?php // 3. 회원가입 성공 등 다른 페이지에서 전달된 일회성 메시지를 표시합니다.
		if ($msg = $this->session->flashdata('notice')): ?>
			<div class="alert alert-info" role="alert">
				<?php echo html_escape($msg); ?>
			</div>
		<?php endif; ?>

		<form action="/auth/loginProc" method="post">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
			<div class="mb-3">
				<label for="userId" class="form-label">아이디</label>
				<input type="text" class="form-control" id="userId" name="userId" value="<?php echo set_value('userId'); ?>" required>
			</div>
			<div class="mb-3">
				<label for="password" class="form-label">비밀번호</label>
				<input type="password" class="form-control" id="password" name="password" required>
			</div>
			<div class="d-grid">
				<button type="submit" class="btn btn-primary btn-lg">로그인</button>
			</div>
		</form>
	</div>
</div>
