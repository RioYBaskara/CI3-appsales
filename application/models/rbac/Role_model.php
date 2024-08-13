<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role_model extends CI_Model
{
    public function jumlahData()
    {
        return $this->db->get('user_role')->num_rows();
    }
}