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

class Surat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->load->library('pagination');
    }

    public function index()
    {
        $data['title'] = 'Generate Surat Audiensi';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $data['role_id'] = $role_id;

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data['roleuser'] = $result['role'];

        // Konfigurasi Pagination
        $config['base_url'] = base_url('Surat');
        $this->db->from('surat_audiensi');

        $config['total_rows'] = $this->db->count_all_results();
        $config['per_page'] = 6; // Sesuaikan dengan jumlah yang ingin ditampilkan per halaman
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $data['start'] = $this->uri->segment(3);

        // Mengambil Data dengan Limit untuk Pagination
        $this->db->select('surat_audiensi.*');
        $this->db->from('surat_audiensi');
        $this->db->order_by('id', 'DESC');

        $this->db->limit($config['per_page'], $data['start']);
        $data['surataudiensi'] = $this->db->get()->result_array();

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nama_tujuan', 'Nama Tujuan', 'required');
        $this->form_validation->set_rules('alamat_tujuan', 'Alamat Tujuan', 'required');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required');
        $this->form_validation->set_rules('nama_institusi', 'Nama Institusi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('surat/index', $data);
            $this->load->view('templates/footer');
        } else {
            $data_input = [
                'tanggal' => $this->input->post('tanggal'),
                'nama_tujuan' => $this->input->post('nama_tujuan'),
                'alamat_tujuan' => $this->input->post('alamat_tujuan'),
                'perihal' => $this->input->post('perihal'),
                'nama_institusi' => $this->input->post('nama_institusi'),
            ];

            $this->db->insert('surat_audiensi', $data_input);
            $this->session->set_flashdata("flashswal", "Ditambah");
            redirect('Surat');
        }
    }

    public function suratedit()
    {
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nama_tujuan', 'Nama Tujuan', 'required');
        $this->form_validation->set_rules('alamat_tujuan', 'Alamat Tujuan', 'required');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required');
        $this->form_validation->set_rules('nama_institusi', 'Nama Instansi', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect('surat', $data);
        } else {
            $data_input = [
                'tanggal' => $this->input->post('tanggal'),
                'nama_tujuan' => $this->input->post('nama_tujuan'),
                'alamat_tujuan' => $this->input->post('alamat_tujuan'),
                'perihal' => $this->input->post('perihal'),
                'nama_institusi' => $this->input->post('nama_institusi'),
            ];

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('surat_audiensi', $data_input);
            $this->session->set_flashdata("flashswal", "Diedit");
            redirect('surat');
        }
    }

    public function surathapus($id)
    {
        $this->db->delete("surat_audiensi", ["id" => $id]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('surat');
    }
}