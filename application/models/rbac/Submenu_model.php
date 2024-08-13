<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Submenu_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('user_sub_menu')->num_rows();
    }
}