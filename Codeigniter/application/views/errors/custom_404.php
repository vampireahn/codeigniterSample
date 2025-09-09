<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
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
		color: #6c757d;
	}
	.error-message {
		font-size: 1.5rem;
		color: #343a40;
	}
</style>
</head>
<body>
	<div class="error-container">
		<div class="error-code">404</div>
		<h1 class="error-message mb-4">페이지를 찾을 수 없습니다.</h1>
		<p class="text-muted">
			요청하신 페이지가 사라졌거나, 잘못된 경로를 이용하셨습니다.<br>
			입력하신 주소가 정확한지 다시 한번 확인해주세요.
		</p>
		<div class="mt-4">
			<a href="/" class="btn btn-primary">메인으로 돌아가기</a>
			<button onclick="history.back()" class="btn btn-secondary">이전 화면으로</button>
		</div>
	</div>
</body>
</html>
