<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php'; // Autoload Composer

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;

/**
 * Buat ngilangin red line, karena intelphense
 *  @property form_validation $form_validation 
 *  @property load $load 
 *  @property input $input 
 *  @property db $db
 *  @property session $session
 *  @property pagination $pagination
 *  @property uri $uri
 */

class Surat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('pagination');
    }

    private function convertBulan($bulan)
    {
        $bulanIndo = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return $bulanIndo[$bulan];
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
            // Ambil tanggal dan konversi
            $tanggal_input = $this->input->post('tanggal');
            $tanggal = date("d", strtotime($tanggal_input)) . ' ' . $this->convertBulan(date("m", strtotime($tanggal_input))) . ' ' . date("Y", strtotime($tanggal_input));

            $data_input = [
                'tanggal' => $tanggal,
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
            // Ambil tanggal dan konversi
            $tanggal_input = $this->input->post('tanggal');
            $tanggal = date("d", strtotime($tanggal_input)) . ' ' . $this->convertBulan(date("m", strtotime($tanggal_input))) . ' ' . date("Y", strtotime($tanggal_input));

            $data_input = [
                'tanggal' => $tanggal,
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

    public function surataudiensiexport($id)
    {
        // Mengambil data surat audiensi berdasarkan ID
        $surat = $this->db->get_where('surat_audiensi', ['id' => $id])->row_array();

        if (!$surat) {
            // Jika tidak ada data, redirect atau set flashdata error
            $this->session->set_flashdata("flashswal", "Surat tidak ditemukan.");
            redirect('surat');
            return;
        }

        // Path ke template DOCX
        $templatePath = FCPATH . 'assets/filetemplate/template_surat_audiensi.docx'; // Pastikan path sesuai

        // Membaca template menggunakan TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Mengganti placeholder dengan data dari database
        $templateProcessor->setValue('tanggal', $surat['tanggal']);
        $templateProcessor->setValue('nama_tujuan', $surat['nama_tujuan']);
        $templateProcessor->setValue('alamat_tujuan', $surat['alamat_tujuan']);
        $templateProcessor->setValue('perihal', $surat['perihal']);
        $templateProcessor->setValue('nama_institusi', $surat['nama_institusi']);

        // Menyimpan dokumen ke memori tanpa menyimpan file di server
        $fileName = 'Surat_Audiensi_' . $id . '.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $templateProcessor->saveAs('php://output');
    }
}