<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Access_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('user_access_menu')->num_rows();
    }
}