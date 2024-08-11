<?php

class Sales_model extends CI_Model
{
    public function getAllSales()
    {
        return $this->db->get('sales')->result_array();
    }
}