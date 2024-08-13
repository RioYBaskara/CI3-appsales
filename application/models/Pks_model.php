<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pks_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('pks')->num_rows();
    }
}