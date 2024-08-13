<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('user_menu')->num_rows();
    }
}