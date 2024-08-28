<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Buat ngilangin red line, karena intelphense
 *  @property form_validation $form_validation 
 *  @property load $load 
 *  @property input $input 
 *  @property db $db
 *  @property session $session
 *  @property upload $upload
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
        $id_sales = $data['user']['id_sales'];

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $this->db->select('nasabah.id_nasabah, nasabah.nama_nasabah, nasabah.no_rekening, nasabah.id_sales, sales.nama_sales AS nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

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
        $id_sales = $data['user']['id_sales'];

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $this->db->select('nasabah.id_nasabah, nasabah.nama_nasabah, nasabah.no_rekening, nasabah.id_sales, sales.nama_sales AS nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

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
        $data['title'] = 'Data Aktivitas Marketing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data['roleuser'] = $result['role'];

        $this->db->select('aktivitas_marketing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('aktivitas_marketing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = aktivitas_marketing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = aktivitas_marketing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('aktivitas_marketing.id_sales', $id_sales);
        }

        $data['aktivitas_marketing'] = $this->db->get()->result_array();
        $data['sales'] = $this->db->get('sales')->result_array();
        $data['nasabah'] = $this->db->get('nasabah')->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('aktivitas', 'Aktivitas', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/aktivitasmarketing', $data);
            $this->load->view('templates/footer');
        } else {
            $tanggal = $this->input->post('tanggal');
            $timestamp = strtotime($tanggal);
            $hariInggris = date('l', $timestamp);

            $namaHariIndonesia = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            $hari = $namaHariIndonesia[$hariInggris];

            // upload gambar masi gagal
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/aktivitas/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $new_image = $this->upload->data('file_name');

                    $data_input = [
                        'id_sales' => $this->input->post('id_sales'),
                        'id_nasabah' => $this->input->post('id_nasabah'),
                        'tanggal' => $tanggal,
                        'hari' => $hari,
                        'aktivitas' => $this->input->post('aktivitas'),
                        'status' => $this->input->post('status'),
                        'keterangan' => $this->input->post('keterangan'),
                        'upload_foto' => $new_image,
                    ];

                    $this->db->insert('aktivitas_marketing', $data_input);
                    $this->session->set_flashdata("flashswal", "Ditambah");
                    redirect('Data/aktivitasmarketing');

                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('Data/aktivitasmarketing');
                }
            } else {
                $data_input = [
                    'id_sales' => $this->input->post('id_sales'),
                    'id_nasabah' => $this->input->post('id_nasabah'),
                    'tanggal' => $tanggal,
                    'hari' => $hari,
                    'aktivitas' => $this->input->post('aktivitas'),
                    'status' => $this->input->post('status'),
                    'keterangan' => $this->input->post('keterangan'),
                    'upload_foto' => 'default.jpg',
                ];

                $this->db->insert('aktivitas_marketing', $data_input);
                $this->session->set_flashdata("flashswal", "Ditambah");
                redirect('Data/aktivitasmarketing');
            }
        }
    }

    public function aktivitasmarketingedit($id)
    {
        $data['title'] = 'Edit Aktivitas Marketing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        $this->db->select('aktivitas_marketing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('aktivitas_marketing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = aktivitas_marketing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = aktivitas_marketing.id_sales', 'left');
        $this->db->where('aktivitas_marketing.id', $id);

        if ($role_id != 1) {
            $this->db->where('aktivitas_marketing.id_sales', $id_sales);
        }

        $data['aktivitas_marketing'] = $this->db->get()->row_array();
        $data['sales'] = $this->db->get('sales')->result_array();
        $data['sales'] = $this->db->get('nasabah')->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('aktivitas', 'Aktivitas', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/aktivitasmarketing', $data);
            $this->load->view('templates/footer');
        } else {
            $tanggal = $this->input->post('tanggal');
            $timestamp = strtotime($tanggal);
            $hariInggris = date('l', $timestamp);

            $namaHariIndonesia = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            $hari = $namaHariIndonesia[$hariInggris];

            $data_update = [
                'id_sales' => $this->input->post('id_sales'),
                'id_nasabah' => $this->input->post('id_nasabah'),
                'tanggal' => $tanggal,
                'hari' => $hari,
                'aktivitas' => $this->input->post('aktivitas'),
                'status' => $this->input->post('status'),
                'keterangan' => $this->input->post('keterangan'),
            ];

            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/aktivitas';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['aktivitas_marketing']['upload_foto'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/aktivitas/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $data_update['upload_foto'] = $new_image;
                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('data/aktivitasmarketing');
                }
            }

            $this->db->where('id', $id);
            $this->db->update('aktivitas_marketing', $data_update);
            $this->session->set_flashdata("flashswal", "Diubah");
            redirect('data/aktivitasmarketing');
        }
    }


    public function aktivitasmarketinghapus($id_aktivitas)
    {
        $this->db->delete("users", ["id" => $id_aktivitas]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('data/aktivitasmarketing');
    }
}