<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    public function index()
    {
        $data['judul'] = 'Sales';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('sales/index', $data);
        $this->load->view('templates/footer');
    }
    public function cba()
    {
        echo "tesss";
    }
}