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
    public function exportNasabah()
    {
        // Load library PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $this->session->userdata('id_sales');

        // Ambil data nasabah dari database
        $this->db->select('nasabah.id_nasabah, nasabah.nama_nasabah, nasabah.no_rekening, nasabah.id_sales, sales.nama_sales AS nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

        $dataNasabah = $this->db->get()->result_array();

        // Nama Header Kolom
        $sheet->setCellValue('A1', 'No'); // Tambahkan header untuk No
        $sheet->setCellValue('B1', 'ID Nasabah');
        $sheet->setCellValue('C1', 'Nama Nasabah');
        $sheet->setCellValue('D1', 'No Rekening');
        $sheet->setCellValue('E1', 'ID Sales');
        $sheet->setCellValue('F1', 'Nama Sales');

        // Isi data ke baris Excel mulai dari baris kedua
        $row = 2;
        $no = 1;
        foreach ($dataNasabah as $nasabah) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $nasabah['id_nasabah']);
            $sheet->setCellValue('C' . $row, $nasabah['nama_nasabah']);
            $sheet->setCellValue('D' . $row, $nasabah['no_rekening']);
            $sheet->setCellValue('E' . $row, $nasabah['id_sales']);
            $sheet->setCellValue('F' . $row, $nasabah['nama_sales']);
            $row++;
        }

        // Buat objek writer untuk menyimpan Excel dalam format Xlsx
        $writer = new Xlsx($spreadsheet);

        // Set nama file untuk didownload
        $filename = 'Data_Nasabah_' . date('Ymd') . '.xlsx';

        // Header untuk download file Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Simpan file Excel ke output
        $writer->save('php://output');
    }

    public function exportAktivitasMarketing()
    {
        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $this->session->userdata('id_sales');

        // Konfigurasi Query
        $this->db->select('aktivitas_marketing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('aktivitas_marketing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = aktivitas_marketing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = aktivitas_marketing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('aktivitas_marketing.id_sales', $id_sales);
        }

        $aktivitasMarketing = $this->db->get()->result_array();

        // Set Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID Aktivitas');
        $sheet->setCellValue('C1', 'Nama Sales - ID Sales');
        $sheet->setCellValue('D1', 'Nama Nasabah - ID Nasabah');
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
            $sheet->setCellValue('C' . $row, $akm['nama_sales'] . ' - ' . $akm['id_sales']);
            $sheet->setCellValue('D' . $row, $akm['nama_nasabah'] . ' - ' . $akm['id_nasabah']);
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

        $role_id = $this->session->userdata("role_id");
        $id_sales = $this->session->userdata('id_sales');

        // query
        $this->db->select('closing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('closing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = closing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = closing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('closing.id_sales', $id_sales);
        }

        $dataClosing = $this->db->get()->result_array();

        // Nama Header Kolom
        $sheet->setCellValue('A1', 'No'); // Tambahkan header untuk No
        $sheet->setCellValue('B1', 'ID Closing');
        $sheet->setCellValue('C1', 'Sales - ID Sales');
        $sheet->setCellValue('D1', 'Nasabah - ID Nasabah');
        $sheet->setCellValue('E1', 'Hari');
        $sheet->setCellValue('F1', 'Tanggal');
        $sheet->setCellValue('G1', 'Nominal');

        // Isi data ke baris Excel mulai dari baris kedua
        $row = 2;
        $no = 1;
        foreach ($dataClosing as $closing) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $closing['id_closing']);
            $sheet->setCellValue('C' . $row, $closing['nama_sales'] . ' - ' . $closing['id_sales']);
            $sheet->setCellValue('D' . $row, $closing['nama_nasabah'] . ' - ' . $closing['id_nasabah']);
            $sheet->setCellValue('E' . $row, $closing['hari']);
            $sheet->setCellValue('F' . $row, date("j F Y", strtotime($closing['tanggal'])));
            $sheet->setCellValue('G' . $row, 'Rp ' . number_format($closing['nominal_closing'], 2, ',', '.'));
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

    public function cobaexport()
    {
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $sheet = $spreadsheet->getActiveSheet();

        // manually set table data value
        $sheet->setCellValue('A1', 'Gipsy Danger');
        $sheet->setCellValue('A2', 'Gipsy Avenger');
        $sheet->setCellValue('A3', 'Striker Eureka');

        $writer = new Xlsx($spreadsheet); // instantiate Xlsx

        $filename = 'list-of-dummy'; // set filename for excel file to be exported

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');	// download file 
    }
}