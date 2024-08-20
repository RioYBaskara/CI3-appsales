<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{

    public function index()
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
            redirect('sales');
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