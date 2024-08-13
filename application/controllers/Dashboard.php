<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sales_model');
        $this->load->model('Pks_model');
        $this->load->model('Nasabah_model');
        $this->load->model('Closing_model');
        $this->load->model('Aktivitas_model');
        $this->load->model('rbac/Menu_model');
        $this->load->model('rbac/Submenu_model');
        $this->load->model('rbac/Role_model');
        $this->load->model('rbac/Access_model');
        $this->load->model('rbac/Users_model');
    }

    public function index()
    {
        $data['judul'] = 'Dashboard';

        $data['jumlahsales'] = $this->Sales_model->jumlahData();
        $data['jumlahpks'] = $this->Pks_model->jumlahData();
        $data['jumlahnasabah'] = $this->Nasabah_model->jumlahData();
        $data['jumlahclosing'] = $this->Closing_model->jumlahData();
        $data['jumlahaktivitas'] = $this->Aktivitas_model->jumlahData();
        $data['jumlahmenu'] = $this->Menu_model->jumlahData();
        $data['jumlahsubmenu'] = $this->Submenu_model->jumlahData();
        $data['jumlahrole'] = $this->Role_model->jumlahData();
        $data['jumlahaccess'] = $this->Access_model->jumlahData();
        $data['jumlahuser'] = $this->Users_model->jumlahData();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
    public function cba()
    {
        echo "tesss";
    }
}