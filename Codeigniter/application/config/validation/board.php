<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// validation/comBoard.php
$config = array(
    'board_write' => array(
        array('field'=>'title','label'=>'제목','rules'=>'trim|required|min_length[2]|max_length[200]'),
        array('field'=>'contents','label'=>'내용','rules'=>'trim|required|min_length[10]'),
        array('field'=>'uploadFile','label'=>'첨부파일','rules'=>'file_ext[uploadFile]|file_max_size[uploadFile]')
    ),
    'comment_write' => array(
        array('field'=>'boardId','label'=>'게시글ID','rules'=>'required|integer'),
        array('field'=>'content','label'=>'내용','rules'=>'trim|required|min_length[2]|max_length[1000]')
    )
);
