<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Sales_model");
        $this->load->library("form_validation");
    }

    public function index()
    {
        $data['judul'] = 'Halaman Sales';
        $data['sales'] = $this->Sales_model->getAllSales();

        $this->load->view("templates/header.php", $data);
        $this->load->view("templates/templateatas.php");
        $this->load->view('sales/index', $data);
        $this->load->view("templates/templatebawah.php");
        $this->load->view("templates/footer.php");
    }

    public function tambah()
    {
        $data["judul"] = "Form Tambah Data Kategori";

        // Rules untuk form_validation
        $this->form_validation->set_rules("deskripsi", "Deksripsi", "required");
        $this->form_validation->set_rules("kategori", "Kategori", "required");

        // CREATE || Pengkondisian form_validation, jika input salah, kembali ke view form input. jika input benar, menjalankan query untuk menambahkan data ke tabel mahasiswa lalu redirect dengan session, flashdata
        if ($this->form_validation->run() == FALSE) {
            $this->load->view("templates/header.php", $data);
            $this->load->view("templates/templateatas.php");
            $this->load->view("kategori/tambah.php");
            $this->load->view("templates/templatebawah.php");
            $this->load->view("templates/footer.php");
        } else {
            $this->Kategori_model->insertKategori();
            // flashdata, session flash dengan isi Ditambahkan
            $this->session->set_flashdata("flashkategori", "Ditambahkan");
            // mengalihkan ke view mahasiswa
            redirect('kategori');
        }
    }
}
