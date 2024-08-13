<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{
    public function jumlahData()
    {
        // jumlah admin(1)
        // return $this->db->get_where('users', array('role_id' => '1'))->num_rows();
        return $this->db->get('users')->num_rows();
    }
}