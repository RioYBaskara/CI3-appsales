<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aktivitas_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('aktivitas_marketing')->num_rows();
    }
}