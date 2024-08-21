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

class Admin extends CI_Controller
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
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

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
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }
    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }

    public function roleAccess($role_id)
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function changeaccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Akses telah diubah!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
    }

    public function sales()
    {
        $data['title'] = 'Sales';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['sales'] = $this->db->get('sales')->result_array();

        $this->form_validation->set_rules('sales', 'Sales', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('sales/index', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('sales', ['nama_sales' => $this->input->post('sales')]);
            $this->session->set_flashdata("flashswal", "Ditambahkan");
            redirect('admin/sales');
        }
    }
    public function salesedit()
    {

        $this->form_validation->set_rules('sales', 'Sales', 'required');
        if ($this->form_validation->run() == FALSE) {
            redirect('admin/sales', $data);
        } else {
            $data = array(
                "nama_sales" => $this->input->post('sales')
            );
            $this->db->where('id_sales', $this->input->post('id'));
            $this->db->update('sales', $data);
            $this->session->set_flashdata("flashswal", "Diedit");
            redirect('admin/sales');
        }
    }

    public function saleshapus($id)
    {
        $this->db->delete("sales", ["id_sales" => $id]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('admin/sales');
    }
}