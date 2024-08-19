<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Buat ngilangin red line, karena intelphense
 *  @property form_validation $form_validation 
 *  @property load $load 
 *  @property input $input 
 *  @property db $db
 *  @property session $session
 *  @property menu $menu 
 */
class Users extends CI_Controller
{
    public function index()
    {
        $data['judul'] = 'Users';

        $data['users'] = $this->db->get('users')->result_array();

        $this->db->select('users.id, users.name, users.email, users.password, users.role_id, user_role.role AS nama_role, users.id_sales, sales.nama_sales AS nama_sales, users.is_active, users.date_created');
        $this->db->from('users');
        $this->db->join('user_role', 'users.role_id = user_role.id');
        $this->db->join('sales', 'users.id_sales = sales.id_sales');
        $data['users'] = $this->db->get()->result_array();

        $data['role'] = $this->db->get('user_role')->result_array();
        $data['sales'] = $this->db->get('sales')->result_array();

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
            redirect('users');
        }
    }
    public function edit()
    {
        $data['judul'] = 'Users';

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
            redirect('users', $data);
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
            redirect('users');
        }
    }


    public function hapus($id)
    {
        $this->db->delete("sales", ["id_sales" => $id]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('sales');
    }
}