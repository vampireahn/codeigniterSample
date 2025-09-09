<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	$route['default_controller'] = 'SampleMainController';
	// 404 오류 발생 시, Errors 컨트롤러의 show_404 메소드를 호출합니다.
	$route['404_override'] = 'Errors/show_404';
	$route['translate_uri_dashes'] = FALSE;
	
	/*
	| -------------------------------------------------------------------------
	| HTTP 동사 기반 라우팅 (Verb-based Routing)
	| -------------------------------------------------------------------------
	| 요청 메소드(GET, POST 등)에 따라 라우팅 규칙을 명확하게 분리합니다.
	*/
	// Member (회원)
	$route['member/register']['get'] = 'SampleMemberController/register';
	$route['member/registerProc']['post'] = 'SampleMemberController/registerProc';
	$route['member/ajax_id_check']['post'] = 'SampleMemberController/ajax_id_check';
	$route['member/changePassword']['get'] = 'SampleMemberController/changePassword';
	$route['member/changePasswordProc']['post'] = 'SampleMemberController/changePasswordProc';
	
	// Auth (인증)
	$route['auth/login']['get'] = 'SampleAuthController/login'; // 로그인 페이지 표시
	$route['auth/loginProc']['post'] = 'SampleAuthController/loginProc';
	$route['auth/logout']['get'] = 'SampleAuthController/logout';
	
	// Board (게시판)
	$route['board']['get'] = 'SampleBoardController/index';
	$route['board/write']['get'] = 'SampleBoardController/write';
	$route['board/writeProc']['post'] = 'SampleBoardController/writeProc';
	$route['board/view/(:num)']['get'] = 'SampleBoardController/view/$1';
	$route['board/edit/(:num)']['get'] = 'SampleBoardController/edit/$1'; // 수정 페이지
	$route['board/editProc/(:num)']['post'] = 'SampleBoardController/editProc/$1'; // 수정 처리
	$route['board/delete/(:num)']['get'] = 'SampleBoardController/delete/$1'; // 삭제 처리
	$route['board/ajaxDeleteFile']['post'] = 'SampleBoardController/ajaxDeleteFile'; // AJAX 파일 삭제
	$route['board/excelDownload']['get'] = 'SampleBoardController/excelDownload'; // 엑셀 다운로드
	$route['board/excelUploadFormDownload']['get'] = 'SampleBoardController/excelUploadFormDownload'; // 엑셀 양식 다운로드
	$route['board/excelUploadProc']['post'] = 'SampleBoardController/excelUploadProc'; // 엑셀 업로드 처리

	// File Download (공통)
	// /download/[디렉토리]/[저장된 파일명]/[원본 파일명]
	$route['download/(:any)/(:any)/(:any)']['get'] = 'FileDownloadController/download/$1/$2/$3';
