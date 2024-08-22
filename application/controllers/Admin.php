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

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

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

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

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

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

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

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

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

    public function users()
    {
        $data['title'] = 'Users';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['users'] = $this->db->get('users')->result_array();

        $this->db->select('users.id, users.name, users.email, users.password, users.role_id, user_role.role AS nama_role, users.id_sales, sales.nama_sales AS nama_sales, users.is_active, users.date_created');
        $this->db->from('users');
        $this->db->join('user_role', 'users.role_id = user_role.id');
        $this->db->join('sales', 'users.id_sales = sales.id_sales');
        $data['users'] = $this->db->get()->result_array();

        $data['role'] = $this->db->get('user_role')->result_array();
        $querysales = "SELECT * FROM sales WHERE id_sales NOT IN (SELECT id_sales FROM users)";
        $data['sales'] = $this->db->query($querysales)->result_array();

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
            'is_unique' => 'Email telah terdaftar!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[3]', [
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('role_id', 'Role', 'required');
        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('users/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name')),
                'email' => htmlspecialchars($this->input->post('email')),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => $this->input->post('role_id'),
                'id_sales' => $this->input->post('id_sales'),
                'is_active' => $this->input->post('is_active'),
                'date_created' => time()
            ];
            $this->db->insert('users', $data);
            $this->session->set_flashdata("flashswal", "Ditambah");
            redirect('admin/users');
        }
    }
    public function usersedit()
    {
        $data['title'] = 'Users';

        $this->db->select('users.id, users.name, users.email, users.password, users.role_id, user_role.role AS nama_role, users.id_sales, sales.nama_sales AS nama_sales, users.is_active, users.date_created');
        $this->db->from('users');
        $this->db->join('user_role', 'users.role_id = user_role.id');
        $this->db->join('sales', 'users.id_sales = sales.id_sales');
        $data['users'] = $this->db->get()->result_array();

        $data['role'] = $this->db->get('user_role')->result_array();
        $data['sales'] = $this->db->get('sales')->result_array();

        $current_email = $this->input->post('current_email');
        $new_email = $this->input->post('email');

        if ($new_email != $current_email) {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
                'is_unique' => 'Email telah terdaftar!'
            ]);
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        }

        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('role_id', 'Role', 'required');
        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('users/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name')),
                'email' => htmlspecialchars($this->input->post('email')),
                'role_id' => $this->input->post('role_id'),
                'id_sales' => $this->input->post('id_sales'),
                'is_active' => $this->input->post('is_active'),
            ];

            $new_password = $this->input->post('password');
            if (!empty($new_password)) {
                $data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
            }

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('users', $data);
            $this->session->set_flashdata("flashswal", "Diedit");
            redirect('admin/users');
        }
    }

    public function usershapus($id)
    {
        $this->db->delete("users", ["id" => $id]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('admin/users');
    }

    public function nasabah()
    {
        $data['title'] = 'Nasabah - Admin';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['sales'] = $this->db->get('sales')->result_array();

        $this->form_validation->set_rules('sales', 'Sales', 'required');

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

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
    public function nasabahedit()
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

    public function nasabahhapus($id)
    {
        $this->db->delete("sales", ["id_sales" => $id]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('admin/sales');
    }
}