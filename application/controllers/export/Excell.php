<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Buat ngilangin red line, karena intelphense
 *  @property form_validation $form_validation 
 *  @property load $load 
 *  @property input $input 
 *  @property db $db
 *  @property session $session
 */

class Excell extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in_tanpa_rbac();
    }

    public function exportNasabah()
    {
        // Load library PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cek apakah tombol "Export Semua Data" ditekan
        if ($this->input->post('export_all')) {
            // Logika untuk export semua data
            $this->db->order_by('id_nasabah', 'DESC'); // Tambahkan urutan DESC
            $dataNasabah = $this->db->get('nasabah')->result_array();
        } else {
            // Ambil pilihan tanggal dan opsi export
            $selectedDate = $this->input->post('selected_date');
            $exportOption = $this->input->post('export_option');

            // Ambil data user dan sales terkait
            $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
            $role_id = $this->session->userdata("role_id");
            $id_sales = $data['user']['id_sales'];

            // Filter data berdasarkan pilihan export dan tanggal
            $this->db->select('nasabah.*, sales.nama_sales AS nama_sales');
            $this->db->from('nasabah');
            $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');

            if ($role_id != 1) {
                $this->db->where('nasabah.id_sales', $id_sales);
            }

            // Logika filter berdasarkan pilihan (minggu atau bulan)
            if ($exportOption == 'week') {
                // Filter untuk minggu berdasarkan tanggal yang dipilih
                $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($selectedDate)));
                $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($selectedDate)));
                $this->db->where('nasabah.created_at >=', $startOfWeek);
                $this->db->where('nasabah.created_at <=', $endOfWeek);
            } elseif ($exportOption == 'month') {
                // Filter untuk bulan berdasarkan tanggal yang dipilih
                $startOfMonth = date('Y-m-01', strtotime($selectedDate));
                $endOfMonth = date('Y-m-t', strtotime($selectedDate));  // Akhir bulan
                $this->db->where('nasabah.created_at >=', $startOfMonth);
                $this->db->where('nasabah.created_at <=', $endOfMonth);
            }

            // Tambahkan urutan DESC
            $this->db->order_by('id_nasabah', 'DESC');
            $dataNasabah = $this->db->get()->result_array();
        }

        // Tambah header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Nasabah');
        $sheet->setCellValue('C1', 'Nama Nasabah');
        $sheet->setCellValue('D1', 'Nama Sales');
        $sheet->setCellValue('E1', 'Tanggal Dibuat');

        // Isi data ke baris Excel
        $row = 2;
        $no = 1;
        foreach ($dataNasabah as $nasabah) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $nasabah['id_nasabah']);
            $sheet->setCellValue('C' . $row, $nasabah['nama_nasabah']);
            $sheet->setCellValue('D' . $row, $nasabah['nama_sales']);
            $sheet->setCellValue('E' . $row, $nasabah['created_at']);
            $row++;
        }

        // Export ke Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'data-nasabah-' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }


    public function exportAktivitasMarketing()
    {
        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        // Ambil pilihan tanggal dan opsi export (minggu/bulan)
        $selectedDate = $this->input->post('selected_date');
        $exportOption = $this->input->post('export_option');

        // Konfigurasi Query
        $this->db->select('aktivitas_marketing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('aktivitas_marketing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = aktivitas_marketing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = aktivitas_marketing.id_sales', 'left');

        // Filter berdasarkan role user
        if ($role_id != 1) {
            $this->db->where('aktivitas_marketing.id_sales', $id_sales);
        }

        // Logika filter berdasarkan pilihan (minggu atau bulan)
        if ($exportOption == 'week') {
            // Filter untuk minggu berdasarkan tanggal yang dipilih
            $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($selectedDate)));
            $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($selectedDate)));
            $this->db->where('aktivitas_marketing.tanggal >=', $startOfWeek);
            $this->db->where('aktivitas_marketing.tanggal <=', $endOfWeek);
        } elseif ($exportOption == 'month') {
            // Filter untuk bulan berdasarkan tanggal yang dipilih
            $startOfMonth = date('Y-m-01', strtotime($selectedDate));
            $endOfMonth = date('Y-m-t', strtotime($selectedDate)); // Akhir bulan
            $this->db->where('aktivitas_marketing.tanggal >=', $startOfMonth);
            $this->db->where('aktivitas_marketing.tanggal <=', $endOfMonth);
        }

        // Tambahkan urutan DESC
        $this->db->order_by('id_aktivitas', 'DESC');
        $aktivitasMarketing = $this->db->get()->result_array();

        // Set Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Aktivitas');
        $sheet->setCellValue('C1', 'Nama Sales');
        $sheet->setCellValue('D1', 'Nama Nasabah');
        $sheet->setCellValue('E1', 'Hari');
        $sheet->setCellValue('F1', 'Tanggal');
        $sheet->setCellValue('G1', 'Aktivitas');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Keterangan');

        // Isi Data
        $row = 2;
        $no = 1;
        foreach ($aktivitasMarketing as $akm) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $akm['id_aktivitas']);
            $sheet->setCellValue('C' . $row, $akm['nama_sales']);
            $sheet->setCellValue('D' . $row, $akm['nama_nasabah']);
            $sheet->setCellValue('E' . $row, $akm['hari']);
            $sheet->setCellValue('F' . $row, date("j F Y", strtotime($akm['tanggal'])));
            $sheet->setCellValue('G' . $row, $akm['aktivitas']);
            $sheet->setCellValue('H' . $row, $akm['status']);
            $sheet->setCellValue('I' . $row, $akm['keterangan']);
            $row++;
        }

        // Konfigurasi Download
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Aktivitas_Marketing_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function exportClosing()
    {
        // Load library PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cek apakah tombol "Export Semua Data" ditekan
        if ($this->input->post('export_all')) {
            // Logika untuk export semua data
            $this->db->select('closing.*, nasabah.nama_nasabah, sales.nama_sales');
            $this->db->from('closing');
            $this->db->join('nasabah', 'nasabah.id_nasabah = closing.id_nasabah', 'left');
            $this->db->join('sales', 'sales.id_sales = closing.id_sales', 'left');
            $this->db->order_by('closing.id_closing', 'DESC');
            $dataClosing = $this->db->get()->result_array();
        } else {
            // Ambil pilihan tanggal dan opsi export
            $selectedDate = $this->input->post('selected_date');
            $exportOption = $this->input->post('export_option');

            // Ambil data user dan sales terkait
            $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
            $role_id = $this->session->userdata("role_id");
            $id_sales = $data['user']['id_sales'];

            // Konfigurasi Query
            $this->db->select('closing.*, nasabah.nama_nasabah, sales.nama_sales');
            $this->db->from('closing');
            $this->db->join('nasabah', 'nasabah.id_nasabah = closing.id_nasabah', 'left');
            $this->db->join('sales', 'sales.id_sales = closing.id_sales', 'left');

            if ($role_id != 1) {
                $this->db->where('closing.id_sales', $id_sales);
            }

            // Logika filter berdasarkan pilihan (minggu atau bulan)
            if ($exportOption == 'week') {
                $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($selectedDate)));
                $endOfWeek = date('Y-m-d', strtotime('sunday this week', strtotime($selectedDate)));
                $this->db->where('closing.tanggal >=', $startOfWeek);
                $this->db->where('closing.tanggal <=', $endOfWeek);
            } elseif ($exportOption == 'month') {
                $startOfMonth = date('Y-m-01', strtotime($selectedDate));
                $endOfMonth = date('Y-m-t', strtotime($selectedDate));
                $this->db->where('closing.tanggal >=', $startOfMonth);
                $this->db->where('closing.tanggal <=', $endOfMonth);
            }

            $this->db->order_by('closing.id_closing', 'DESC');
            $dataClosing = $this->db->get()->result_array();
        }

        // Nama Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Closing');
        $sheet->setCellValue('C1', 'Sales');
        $sheet->setCellValue('D1', 'Nasabah');
        $sheet->setCellValue('E1', 'Hari');
        $sheet->setCellValue('F1', 'Tanggal');
        $sheet->setCellValue('G1', 'No Rekening');
        $sheet->setCellValue('H1', 'Nominal');

        // Isi data ke baris Excel
        $row = 2;
        $no = 1;
        foreach ($dataClosing as $closing) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $closing['id_closing']);
            $sheet->setCellValue('C' . $row, $closing['nama_sales']);
            $sheet->setCellValue('D' . $row, $closing['nama_nasabah']);
            $sheet->setCellValue('E' . $row, $closing['hari']);
            $sheet->setCellValue('F' . $row, date("j F Y", strtotime($closing['tanggal'])));
            $sheet->setCellValue('G' . $row, $closing['no_rekening']);
            $sheet->setCellValue('H' . $row, 'Rp ' . number_format($closing['nominal_closing'], 2, ',', '.'));
            $row++;
        }

        // Konfigurasi Download
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Closing_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function exportPKS()
    {
        // Load library PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        // query
        $this->db->select('pks.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('pks');
        $this->db->join('nasabah', 'nasabah.id_nasabah = pks.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = pks.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('pks.id_sales', $id_sales);
        }

        $dataPKS = $this->db->get()->result_array();

        // Nama Header Kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'Sales - ID Sales');
        $sheet->setCellValue('D1', 'Nasabah - ID Nasabah');
        $sheet->setCellValue('E1', 'No PKS');
        $sheet->setCellValue('F1', 'Tanggal Awal PKS');
        $sheet->setCellValue('G1', 'Tanggal Akhir PKS');

        // Isi data ke baris Excel mulai dari baris kedua
        $row = 2;
        $no = 1;
        foreach ($dataPKS as $pks) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $pks['id_pks']);
            $sheet->setCellValue('C' . $row, $pks['nama_sales'] . ' - ' . $pks['id_sales']);
            $sheet->setCellValue('D' . $row, $pks['nama_nasabah'] . ' - ' . $pks['id_nasabah']);
            $sheet->setCellValue('E' . $row, $pks['no_pks']);
            $sheet->setCellValue('F' . $row, date("j F Y", strtotime($pks['tanggal_awal_pks'])));
            $sheet->setCellValue('G' . $row, date("j F Y", strtotime($pks['tanggal_akhir_pks'])));
            $row++;
        }

        // Konfigurasi Download
        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_PKS_' . date('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}