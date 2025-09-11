<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>500 Internal Server Error</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
	body {
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100vh;
		background-color: #f8f9fa;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
	}
	.error-container {
		text-align: center;
		max-width: 500px;
	}
	.error-code {
		font-size: 8rem;
		font-weight: 700;
		color: #dc3545;
	}
	.error-message {
		font-size: 1.5rem;
		color: #343a40;
	}
	.error-details {
		text-align: left;
		background-color: #f8f9fa;
		border: 1px solid #dee2e6;
		border-radius: .25rem;
		padding: 1rem;
		margin-top: 2rem;
		font-family: "SFMono-Regular", Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
		font-size: 13px;
		white-space: pre-wrap;
		word-wrap: break-word;
		color: #212529;
	}
	.error-details h4 {
		font-size: 1rem;
		font-weight: bold;
	}
</style>
</head>
<body>
	<div class="error-container">
		<div class="error-code">500</div>
		<h1 class="error-message mb-4">서버 내부 오류가 발생했습니다.</h1>
		<p class="text-muted">
			서비스 이용에 불편을 드려 죄송합니다.<br>
			문제가 지속될 경우 관리자에게 문의해주세요.
		</p>
		<a href="/" class="btn btn-primary">메인으로 돌아가기</a>
		<button onclick="history.back()" class="btn btn-secondary">이전 화면으로</button>

		<?php
		// 개발 환경(development, local)일 때만 상세 오류 내용을 표시합니다.
		if (defined('ENVIRONMENT') && in_array(ENVIRONMENT, ['development', 'local'])):
		?>
			<div class="error-details">
				<h4><?php echo $heading; // 오류 제목 (e.g., "A PHP Error was encountered") ?></h4>
				<?php echo is_array($message) ? implode("\n", $message) : $message; // 상세 오류 메시지 ?>
			</div>
		<?php endif; ?>

	</div>
</body>
</html>
