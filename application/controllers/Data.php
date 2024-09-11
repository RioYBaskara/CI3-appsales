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
 *  @property pagination $pagination
 */

class Data extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->load->library('pagination');
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

        // Load Pagination Library
        $this->load->library('pagination');

        // Pagination Configuration
        $config['base_url'] = base_url('Data/index');  // Base URL for pagination
        $config['total_rows'] = $this->db->count_all('nasabah');  // Total number of records
        $config['per_page'] = 6;  // Number of records per page
        $config['uri_segment'] = 3;  // The URI segment to detect the page number

        // Initialize pagination with the config
        $this->pagination->initialize($config);

        // Get the page number from the URL, default to 0 if not set or invalid
        $data['start'] = $this->uri->segment(3);
        $page = $this->uri->segment(3);
        $page = (is_numeric($page) && $page !== null) ? $page : 0; // Ensure $page is numeric and not null

        // Fetch data with limit and offset
        $this->db->select('nasabah.id_nasabah, nasabah.nama_nasabah, nasabah.no_rekening, nasabah.id_sales, sales.nama_sales AS nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

        // Apply limit and offset for pagination
        $this->db->limit($config['per_page'], $page);
        $data['nasabah'] = $this->db->get()->result_array();

        // Get sales data
        $data['sales'] = $this->db->get('sales')->result_array();

        // Pagination links
        $data['pagination'] = $this->pagination->create_links();

        // Form validation rules
        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('nama_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('no_rekening', 'No Rekening', 'required|numeric');

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
        $this->form_validation->set_rules('no_rekening', 'No Rekening', 'required|numeric');

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
        // Check if the ID exists in the related tables
        $this->db->from('aktivitas_marketing');
        $this->db->where('id_nasabah', $id_nasabah);
        $result = $this->db->count_all_results();

        if ($result > 0) {
            $this->session->set_flashdata("pesan", "ID Nasabah digunakan di Aktivitas Marketing dan tidak bisa dihapus.");
            redirect('Data');  // Redirect to a page where the message can be displayed
            return;
        }

        $this->db->from('closing');
        $this->db->where('id_nasabah', $id_nasabah);
        $result = $this->db->count_all_results();

        if ($result > 0) {
            $this->session->set_flashdata("pesan", "ID Nasabah digunakan di Closing dan tidak bisa dihapus.");
            redirect('Data');  // Redirect to a page where the message can be displayed
            return;
        }

        $this->db->from('pks');
        $this->db->where('id_nasabah', $id_nasabah);
        $result = $this->db->count_all_results();

        if ($result > 0) {
            $this->session->set_flashdata("pesan", "ID Nasabah digunakan di PKS dan tidak bisa dihapus.");
            redirect('Data');  // Redirect to a page where the message can be displayed
            return;
        }

        // If no records are found, proceed with deletion
        $this->db->delete("nasabah", ["id_nasabah" => $id_nasabah]);
        $this->session->set_flashdata("flashwal", "Dihapus");
        redirect('Data');
    }


    // Aktivitas Marketing
    public function aktivitasmarketing()
    {
        $data['title'] = 'Data Aktivitas Marketing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $data['role_id'] = $role_id;
        $id_sales = $data['user']['id_sales'];
        $data['id_sales'] = $id_sales;

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data['roleuser'] = $result['role'];

        // Konfigurasi Pagination
        $config['base_url'] = base_url('Data/aktivitasmarketing');
        $this->db->from('aktivitas_marketing');

        // Jika role_id bukan admin (1), hanya tampilkan data aktivitas untuk sales terkait
        if ($role_id != 1) {
            $this->db->where('aktivitas_marketing.id_sales', $id_sales);
        }

        $config['total_rows'] = $this->db->count_all_results();
        $config['per_page'] = 6; // Sesuaikan dengan jumlah yang ingin ditampilkan per halaman
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $data['start'] = $this->uri->segment(3);

        // Mengambil Data dengan Limit untuk Pagination
        $this->db->select('aktivitas_marketing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('aktivitas_marketing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = aktivitas_marketing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = aktivitas_marketing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('aktivitas_marketing.id_sales', $id_sales);
        }

        $this->db->limit($config['per_page'], $data['start']);
        $data['aktivitas_marketing'] = $this->db->get()->result_array();
        $data['sales'] = $this->db->get('sales')->result_array();

        $this->db->select('nasabah.*, sales.nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'sales.id_sales = nasabah.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

        $data['nasabah'] = $this->db->get()->result_array();

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

    public function aktivitasmarketingedit()
    {
        $data['title'] = 'Edit Aktivitas Marketing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        $this->db->select('aktivitas_marketing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('aktivitas_marketing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = aktivitas_marketing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = aktivitas_marketing.id_sales', 'left');

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

            $this->db->where('id_aktivitas', $this->input->post('id_aktivitas'));
            $this->db->update('aktivitas_marketing', $data_update);
            $this->session->set_flashdata("flashswal", "Diubah");
            redirect('data/aktivitasmarketing');
        }
    }


    public function aktivitasmarketinghapus($id_aktivitas)
    {
        $this->db->delete("aktivitas_marketing", ["id_aktivitas" => $id_aktivitas]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('data/aktivitasmarketing');
    }

    // Closing
    public function closing()
    {
        $data['title'] = 'Data Closing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $data['role_id'] = $role_id;
        $id_sales = $data['user']['id_sales'];
        $data['id_sales'] = $id_sales;

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data['roleuser'] = $result['role'];

        // Konfigurasi Pagination
        $config['base_url'] = base_url('Data/closing');
        $this->db->from('closing');

        // Jika role_id bukan admin (1), hanya tampilkan data aktivitas untuk sales terkait
        if ($role_id != 1) {
            $this->db->where('closing.id_sales', $id_sales);
        }

        $config['total_rows'] = $this->db->count_all_results();
        $config['per_page'] = 6; // Sesuaikan dengan jumlah yang ingin ditampilkan per halaman
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $data['start'] = $this->uri->segment(3);

        // Mengambil Data dengan Limit untuk Pagination
        $this->db->select('closing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('closing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = closing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = closing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('closing.id_sales', $id_sales);
        }

        $this->db->limit($config['per_page'], $data['start']);
        $data['closing'] = $this->db->get()->result_array();
        $data['sales'] = $this->db->get('sales')->result_array();

        $this->db->select('nasabah.*, sales.nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'sales.id_sales = nasabah.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

        $data['nasabah'] = $this->db->get()->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nominal_closing', 'Nominal', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/closing', $data);
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

            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/closing/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $new_image = $this->upload->data('file_name');

                    $data_input = [
                        'id_sales' => $this->input->post('id_sales'),
                        'id_nasabah' => $this->input->post('id_nasabah'),
                        'tanggal' => $tanggal,
                        'hari' => $hari,
                        'nominal_closing' => $this->input->post('nominal_closing'),
                        'upload_foto' => $new_image,
                    ];

                    $this->db->insert('closing', $data_input);
                    $this->session->set_flashdata("flashswal", "Ditambah");
                    redirect('Data/closing');

                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('Data/closing');
                }
            } else {
                $data_input = [
                    'id_sales' => $this->input->post('id_sales'),
                    'id_nasabah' => $this->input->post('id_nasabah'),
                    'tanggal' => $tanggal,
                    'hari' => $hari,
                    'nominal_closing' => $this->input->post('nominal_closing'),
                    'upload_foto' => 'default.jpg',
                ];

                $this->db->insert('closing', $data_input);
                $this->session->set_flashdata("flashswal", "Ditambah");
                redirect('Data/closing');
            }
        }
    }

    public function closingedit()
    {
        $data['title'] = 'Edit Closing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        $this->db->select('closing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('closing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = closing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = closing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('closing.id_sales', $id_sales);
        }

        $data['closing'] = $this->db->get()->row_array();
        $data['sales'] = $this->db->get('sales')->result_array();
        $data['sales'] = $this->db->get('nasabah')->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nominal_closing', 'Nominal', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/closing', $data);
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
                'nominal_closing' => $this->input->post('nominal_closing'),
            ];

            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/closing';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['closing']['upload_foto'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/closing/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $data_update['upload_foto'] = $new_image;
                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('data/closing');
                }
            }

            $this->db->where('id_closing', $this->input->post('id_closing'));
            $this->db->update('closing', $data_update);
            $this->session->set_flashdata("flashswal", "Diubah");
            redirect('data/closing');
        }
    }


    public function closinghapus($id_closing)
    {
        $this->db->delete("closing", ["id_closing" => $id_closing]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('data/closing');
    }

    // pks
    public function pks()
    {
        $data['title'] = 'Data PKS';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $data['role_id'] = $role_id;
        $id_sales = $data['user']['id_sales'];
        $data['id_sales'] = $id_sales;

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data['roleuser'] = $result['role'];

        // Konfigurasi Pagination
        $config['base_url'] = base_url('Data/pks');
        $this->db->from('pks');

        // Jika role_id bukan admin (1), hanya tampilkan data aktivitas untuk sales terkait
        if ($role_id != 1) {
            $this->db->where('pks.id_sales', $id_sales);
        }

        $config['total_rows'] = $this->db->count_all_results();
        $config['per_page'] = 6; // Sesuaikan dengan jumlah yang ingin ditampilkan per halaman
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $data['start'] = $this->uri->segment(3);

        // Mengambil Data dengan Limit untuk Pagination
        $this->db->select('pks.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('pks');
        $this->db->join('nasabah', 'nasabah.id_nasabah = pks.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = pks.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('pks.id_sales', $id_sales);
        }

        $this->db->limit($config['per_page'], $data['start']);
        $data['pks'] = $this->db->get()->result_array();
        $data['sales'] = $this->db->get('sales')->result_array();

        $this->db->select('nasabah.*, sales.nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'sales.id_sales = nasabah.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

        $data['nasabah'] = $this->db->get()->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('no_pks', 'Nomor PKS', 'required');
        $this->form_validation->set_rules('tanggal_awal_pks', 'Tanggal Awal PKS', 'required');
        $this->form_validation->set_rules('tanggal_akhir_pks', 'Tanggal Akhir PKS', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/pks', $data);
            $this->load->view('templates/footer');
        } else {
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/pks/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $new_image = $this->upload->data('file_name');

                    $data_input = [
                        'id_sales' => $this->input->post('id_sales'),
                        'id_nasabah' => $this->input->post('id_nasabah'),
                        'no_pks' => $this->input->post('no_pks'),
                        'tanggal_awal_pks' => $this->input->post('tanggal_awal_pks'),
                        'tanggal_akhir_pks' => $this->input->post('tanggal_akhir_pks'),
                        'upload_foto' => $new_image,
                    ];

                    $this->db->insert('pks', $data_input);
                    $this->session->set_flashdata("flashswal", "Ditambah");
                    redirect('Data/pks');

                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('Data/pks');
                }
            } else {
                $data_input = [
                    'id_sales' => $this->input->post('id_sales'),
                    'id_nasabah' => $this->input->post('id_nasabah'),
                    'no_pks' => $this->input->post('no_pks'),
                    'tanggal_awal_pks' => $this->input->post('tanggal_awal_pks'),
                    'tanggal_akhir_pks' => $this->input->post('tanggal_akhir_pks'),
                    'upload_foto' => 'default.jpg',
                ];

                $this->db->insert('pks', $data_input);
                $this->session->set_flashdata("flashswal", "Ditambah");
                redirect('Data/pks');
            }
        }
    }

    public function pksedit()
    {
        $data['title'] = 'Edit PKS';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        $this->db->select('pks.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('pks');
        $this->db->join('nasabah', 'nasabah.id_nasabah = pks.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = pks.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('pks.id_sales', $id_sales);
        }

        $data['pks'] = $this->db->get()->row_array();
        $data['sales'] = $this->db->get('sales')->result_array();
        $data['sales'] = $this->db->get('nasabah')->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('no_pks', 'Nomor PKS', 'required');
        $this->form_validation->set_rules('tanggal_awal_pks', 'Tanggal Awal PKS', 'required');
        $this->form_validation->set_rules('tanggal_akhir_pks', 'Tanggal Akhir PKS', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/pks', $data);
            $this->load->view('templates/footer');
        } else {
            $data_update = [
                'id_sales' => $this->input->post('id_sales'),
                'id_nasabah' => $this->input->post('id_nasabah'),
                'no_pks' => $this->input->post('no_pks'),
                'tanggal_awal_pks' => $this->input->post('tanggal_awal_pks'),
                'tanggal_akhir_pks' => $this->input->post('tanggal_akhir_pks'),
            ];

            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/pks';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['pks']['upload_foto'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/pks/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $data_update['upload_foto'] = $new_image;
                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('data/pks');
                }
            }

            $this->db->where('id_pks', $this->input->post('id_pks'));
            $this->db->update('pks', $data_update);
            $this->session->set_flashdata("flashswal", "Diubah");
            redirect('data/pks');
        }
    }


    public function pkshapus($id_pks)
    {
        $this->db->delete("pks", ["id_pks" => $id_pks]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('data/pks');
    }

}