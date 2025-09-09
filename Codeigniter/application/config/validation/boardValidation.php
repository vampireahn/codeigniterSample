<?php
	defined('BASEPATH') or exit('No direct script access allowed');
	
	/**
	 * 게시판 관련 유효성 검사 규칙입니다.
	 */
	
	$config = array(
		// 게시글 작성 유효성 검사 규칙
		'board_write' => array(
			array(
				'field'  => 'title',
				'label'  => '제목',
				'rules'  => 'trim|required|max_length[100]',
				'errors' => array(
					'required'   => '{field}은(는) 필수 입력 항목입니다.',
					'max_length' => '{field}은(는) 최대 {param}자까지 입력할 수 있습니다.',
				),
			),
			array(
				'field' => 'content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
		),
	);
