<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json');
    }

    public function status()
    {
        echo json_encode(['status'=>'ok','time'=>date('c')]);
    }

    public function echo_get($id = null)
    {
        echo json_encode(['method'=>'GET','id'=>$id]);
    }

    public function echo_post($id = null)
    {
        echo json_encode(['method'=>'POST','id'=>$id,'payload'=>$this->input->post()]);
    }

    public function register()
    {
        $payload = json_decode($this->input->raw_input_stream, true) ?: [];
        $this->form_validation->set_data($payload);

        $this->config->load('validation/api', TRUE);
        $rules = $this->config->item('api_register', 'api');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === FALSE) {
            http_response_code(422);
            echo json_encode(['ok'=>false,'errors'=>$this->form_validation->error_array()]);
            return;
        }
        $email = $payload['email']; $name = $payload['name']; $age = (int)$payload['age'];
        echo json_encode(['ok'=>true,'user'=>compact('email','name','age')]);
    }
}
