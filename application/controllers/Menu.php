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

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // is_logged_in();
    }

    // menu
    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu baru ditambah!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
            redirect('menu');
        }
    }

    public function edit()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');
        if ($this->form_validation->run() == FALSE) {
            redirect('menu', $data);
        } else {
            $data = array(
                "menu" => $this->input->post('menu')
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('user_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu telah diperbarui!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div>');
            redirect('menu');
        }
    }

    public function hapus($id)
    {
        $this->db->delete("user_menu", ["id" => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu Deleted!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div>');
        redirect('menu');
    }

    // Submenu
    public function submenu()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
                FROM `user_sub_menu` JOIN `user_menu`
                ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
        ";

        $data['subMenu'] = $this->db->query($query)->result_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
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

    public function submenuedit()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect('menu/submenu', $data);
        } else {
            $data = array(
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('user_sub_menu', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">SubMenu telah diperbarui!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div>');
            redirect('menu/submenu');
        }


    }
    public function submenuhapus($id)
    {
        $this->db->delete("user_sub_menu", ["id" => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Sub Menu Deleted!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div>');
        redirect('menu/submenu');
    }

}