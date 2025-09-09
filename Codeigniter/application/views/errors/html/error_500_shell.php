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
		max-width: 800px; /* 너비를 넓혀 iframe이 잘 보이도록 조정 */
		width: 90%;
	}
	.iframe-container {
		margin-top: 2rem;
		width: 100%;
		height: 400px; /* iframe 높이 지정 */
		border: 1px solid #dee2e6;
		border-radius: .25rem;
		overflow: hidden;
	}
</style>
</head>
<body>
	<div class="error-container">
		<h1 class="display-4">Custom 500 Error Page</h1>
		<p class="lead">서버 내부 오류가 발생했습니다. 아래 iframe에서 실제 오류 내용을 확인하세요.</p>
		<div class="iframe-container">
			<!-- 현재 URL에 content_only=1 파라미터를 추가하여 iframe의 소스로 사용합니다. -->
			<iframe src="<?php echo config_item('base_url') . ltrim($_SERVER['REQUEST_URI'], '/') . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '&') . 'content_only=1'; ?>" width="100%" height="100%" style="border:none;"></iframe>
		</div>
	</div>
</body>
</html>
