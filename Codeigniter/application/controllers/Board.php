<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Board extends CI_Controller
{
    public function writeForm(){ $this->load->view('board/write'); }
    public function writeProc(){
        $this->config->load('validation/comBoard', TRUE);
        $rules = $this->config->item('board_write', 'comBoard');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors()); redirect('/comBoard/write'); return;
        }
        $this->session->set_flashdata('notice','게시글이 등록되었습니다.'); redirect('/');
    }
    public function commentCreate(){
        $payload = json_decode($this->input->raw_input_stream, true) ?: [];
        $this->form_validation->set_data($payload);
        $this->config->load('validation/comBoard', TRUE);
        $rules = $this->config->item('comment_write', 'comBoard');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === FALSE) {
            http_response_code(422); echo json_encode(['ok'=>false,'errors'=>$this->form_validation->error_array()]); return;
        }
        echo json_encode(['ok'=>true]);
    }
}
