<?php
	defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php
			echo isset($page_title) ? html_escape($page_title) : 'P2P 서비스'; ?></title>

	<!-- Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

	<!-- Font Awesome (아이콘) -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
		  integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
		  crossorigin="anonymous" referrerpolicy="no-referrer"/>

	<!-- jQuery (Bootstrap JS보다 먼저 로드) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
	<div class="container">
		<a class="navbar-brand" href="<?=site_url();?>"><?=$this->lang->line('P2P 서비스');?></a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
				aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="mainNavbar">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link" href="<?=site_url('board');?>"><?=$this->lang->line('게시판');?></a>
				</li>
			</ul>
			<div class="d-flex">
				<?php if (is_logged_in()): ?>
						<a href="<?=site_url('auth/logout');?>" class="btn btn-outline-secondary"><?=$this->lang->line('로그아웃');?></a>
					<?php else: ?>
						<a href="<?=site_url('auth/login');?>" class="btn btn-outline-primary me-2"><?=$this->lang->line('로그인');?></a>
						<a href="<?=site_url('member/register');?>" class="btn btn-primary"><?=$this->lang->line('회원가입');?></a>
					<?php endif; ?>
			</div>
		</div>
	</div>
</nav>

<main class="container">
