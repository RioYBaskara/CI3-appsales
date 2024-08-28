<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Buat ngilangin red line, karena intelphense
 *  @property form_validation $form_validation 
 *  @property load $load 
 *  @property input $input 
 *  @property db $db
 *  @property session $session
 */

class Data extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Data Nasabah';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $this->db->select('nasabah.id_nasabah, nasabah.nama_nasabah, nasabah.no_rekening, nasabah.id_sales, sales.nama_sales AS nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');
        $data['nasabah'] = $this->db->get()->result_array();

        $data['sales'] = $this->db->get('sales')->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('nama_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('no_rekening', 'No Rekening', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'id_sales' => $this->input->post('id_sales'),
                'nama_nasabah' => $this->input->post('nama_nasabah'),
                'no_rekening' => $this->input->post('no_rekening'),
            ];
            $this->db->insert('nasabah', $data);
            $this->session->set_flashdata("flashswal", "Ditambah");
            redirect('Data');
        }
    }

    public function nasabahedit()
    {
        $data['title'] = 'Admin, Data Nasabah';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $this->db->select('nasabah.id_nasabah, nasabah.nama_nasabah, nasabah.no_rekening, nasabah.id_sales, sales.nama_sales AS nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');
        $data['nasabah'] = $this->db->get()->result_array();

        $data['sales'] = $this->db->get('sales')->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('nama_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('no_rekening', 'No Rekening', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data = [
                'id_sales' => $this->input->post('id_sales'),
                'nama_nasabah' => $this->input->post('nama_nasabah'),
                'no_rekening' => $this->input->post('no_rekening'),
            ];
            $this->db->where('id_nasabah', $this->input->post('id_nasabah'));
            $this->db->update('nasabah', $data);
            $this->session->set_flashdata("flashswal", "Diedit");
            redirect('Data');
        }
    }

    public function nasabahhapus($id_nasabah)
    {
        $this->db->delete("nasabah", ["id_nasabah" => $id_nasabah]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('Data');
    }

    // Aktivitas Marketing
    public function aktivitasmarketing()
    {

    }

    public function aktivitasmarketingedit()
    {

    }

    public function aktivitasmarketinghapus($id_aktivitas)
    {

    }
}