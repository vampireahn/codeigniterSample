<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h1>메인 페이지에 오신 것을 환영합니다!</h1>
<p>이 페이지는 <strong>application/views/main/index.php</strong> 파일에서 생성되었습니다.</p>

<div class="button-container">
	<?php if (is_logged_in()): ?>
		<a href="<?php echo site_url('member/changePassword'); ?>" class="button">비밀번호 변경</a>
		<a href="<?php echo site_url('auth/logout'); ?>" class="button">로그아웃</a>
	<?php else: ?>
		<a href="<?php echo site_url('member/register'); ?>" class="button">회원가입</a>
		<a href="<?php echo site_url('auth/login'); ?>" class="button">로그인</a>
	<?php endif; ?>
	<a href="<?php echo site_url("board"); ?>" class="button">게시판</a>
</div>
