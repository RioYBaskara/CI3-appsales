<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Buat ngilangin red line, karena intelphense
 *  @property form_validation $form_validation 
 *  @property load $load 
 *  @property input $input 
 *  @property db $db
 *  @property session $session
 *  @property Sales_model $Sales_model
 *  @property Pks_model $Pks_model
 *  @property Nasabah_model $Nasabah_model
 *  @property Closing_model $Closing_model
 *  @property Aktivitas_model $Aktivitas_model
 *  @property Menu_model $Menu_model
 *  @property Submenu_model $Submenu_model
 *  @property Role_model $Role_model
 *  @property Access_model $Access_model
 *  @property Users_model $Users_model
 */

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
        $data['title'] = 'Dashboard';

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