<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('users/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->db->insert('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Sub menu added!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div>');
            redirect('menu/submenu');
        }
    }
    public function edit()
    {
        $this->form_validation->set_rules('sales', 'Sales', 'required');
        if ($this->form_validation->run() == FALSE) {
            redirect('sales', $data);
        } else {
            $data = array(
                "nama_sales" => $this->input->post('sales')
            );
            $this->db->where('id_sales', $this->input->post('id'));
            $this->db->update('sales', $data);
            $this->session->set_flashdata("flashswal", "Diedit");
            redirect('sales');
        }
    }

    public function hapus($id)
    {
        $this->db->delete("sales", ["id_sales" => $id]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('sales');
    }
}