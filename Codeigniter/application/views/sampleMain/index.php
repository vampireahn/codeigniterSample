<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container mt-5">
	<div class="p-5 mb-4 bg-light rounded-3">
		<div class="container-fluid py-5">
			<h1 class="display-5 fw-bold">메인 페이지에 오신 것을 환영합니다!</h1>
			<p class="col-md-8 fs-4">이 페이지는 <code>application/views/sampleMain/index.php</code> 파일에서 생성되었습니다.</p>
		</div>
	</div>

	<div class="d-flex gap-2 justify-content-center">
		<?php if (is_logged_in()): ?>
			<a href="<?php echo site_url('member/changePassword'); ?>" class="btn btn-secondary">비밀번호 변경</a>
			<a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger">로그아웃</a>
		<?php else: ?>
			<a href="<?php echo site_url('member/register'); ?>" class="btn btn-success">회원가입</a>
			<a href="<?php echo site_url('auth/login'); ?>" class="btn btn-primary">로그인</a>
		<?php endif; ?>
		<a href="<?php echo site_url("board"); ?>" class="btn btn-info">게시판</a>
	</div>
</div>
