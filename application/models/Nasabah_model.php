<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nasabah_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('nasabah')->num_rows();
    }
}