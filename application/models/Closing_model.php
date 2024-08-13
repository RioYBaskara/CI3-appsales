<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Closing_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('closing')->num_rows();
    }
}