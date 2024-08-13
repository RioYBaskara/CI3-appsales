<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('sales')->num_rows();
    }
}