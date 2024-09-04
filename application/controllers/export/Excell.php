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

        // Nama Header Kolom
        $sheet->setCellValue('A1', 'ID Nasabah');
        $sheet->setCellValue('B1', 'Nama Nasabah');
        $sheet->setCellValue('C1', 'No Rekening');
        $sheet->setCellValue('D1', 'ID Sales');
        $sheet->setCellValue('E1', 'Nama Sales');

        // Ambil data nasabah dari database
        $this->db->select('nasabah.id_nasabah, nasabah.nama_nasabah, nasabah.no_rekening, nasabah.id_sales, sales.nama_sales AS nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'nasabah.id_sales = sales.id_sales');
        $dataNasabah = $this->db->get()->result_array();

        // Isi data ke baris Excel mulai dari baris kedua
        $row = 2;
        foreach ($dataNasabah as $nasabah) {
            $sheet->setCellValue('A' . $row, $nasabah['id_nasabah']);
            $sheet->setCellValue('B' . $row, $nasabah['nama_nasabah']);
            $sheet->setCellValue('C' . $row, $nasabah['no_rekening']);
            $sheet->setCellValue('D' . $row, $nasabah['id_sales']);
            $sheet->setCellValue('E' . $row, $nasabah['nama_sales']);
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