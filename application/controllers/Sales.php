<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    public function index()
    {
        $data['judul'] = 'Sales';
        $this->load->view('template/index', $data);
    }
    public function cba()
    {
        echo "tesss";
    }
}