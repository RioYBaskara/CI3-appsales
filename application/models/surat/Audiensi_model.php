<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Audiensi_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('surat_audiensi')->num_rows();
    }
}