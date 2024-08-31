<?php

// Closing
    public function closing()
    {
        $data['title'] = 'Data Closing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $data['role_id'] = $role_id;
        $id_sales = $data['user']['id_sales'];
        $data['id_sales'] = $id_sales;

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();
        $data['roleuser'] = $result['role'];

        $this->db->select('closing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('closing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = closing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = closing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('closing.id_sales', $id_sales);
        }

        $data['closing'] = $this->db->get()->result_array();
        $data['sales'] = $this->db->get('sales')->result_array();

        $this->db->select('nasabah.*, sales.nama_sales');
        $this->db->from('nasabah');
        $this->db->join('sales', 'sales.id_sales = nasabah.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('nasabah.id_sales', $id_sales);
        }

        $data['nasabah'] = $this->db->get()->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nominal_closing', 'Nominal', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/closing', $data);
            $this->load->view('templates/footer');
        } else {
            $tanggal = $this->input->post('tanggal');
            $timestamp = strtotime($tanggal);
            $hariInggris = date('l', $timestamp);

            $namaHariIndonesia = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            $hari = $namaHariIndonesia[$hariInggris];

            // upload gambar masi gagal
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/closing/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $new_image = $this->upload->data('file_name');

                    $data_input = [
                        'id_sales' => $this->input->post('id_sales'),
                        'id_nasabah' => $this->input->post('id_nasabah'),
                        'tanggal' => $tanggal,
                        'hari' => $hari,
                        'nominal_closing' => $this->input->post('nominal_closing'),
                        'upload_foto' => $new_image,
                    ];

                    $this->db->insert('closing', $data_input);
                    $this->session->set_flashdata("flashswal", "Ditambah");
                    redirect('Data/closing');

                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('Data/closing');
                }
            } else {
                $data_input = [
                    'id_sales' => $this->input->post('id_sales'),
                    'id_nasabah' => $this->input->post('id_nasabah'),
                    'tanggal' => $tanggal,
                    'hari' => $hari,
                    'nominal_closing' => $this->input->post('nominal_closing'),
                    'upload_foto' => 'default.jpg',
                ];

                $this->db->insert('closing', $data_input);
                $this->session->set_flashdata("flashswal", "Ditambah");
                redirect('Data/closing');
            }
        }
    }

    public function closingedit()
    {
        $data['title'] = 'Edit Closing';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $role_id = $this->session->userdata("role_id");
        $id_sales = $data['user']['id_sales'];

        $this->db->select('closing.*, nasabah.nama_nasabah, sales.nama_sales');
        $this->db->from('closing');
        $this->db->join('nasabah', 'nasabah.id_nasabah = closing.id_nasabah', 'left');
        $this->db->join('sales', 'sales.id_sales = closing.id_sales', 'left');

        if ($role_id != 1) {
            $this->db->where('closing.id_sales', $id_sales);
        }

        $data['closing'] = $this->db->get()->row_array();
        $data['sales'] = $this->db->get('sales')->result_array();
        $data['sales'] = $this->db->get('nasabah')->result_array();

        $this->form_validation->set_rules('id_sales', 'Nama Sales', 'required');
        $this->form_validation->set_rules('id_nasabah', 'Nama Nasabah', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('nominal_closing', 'Nominal', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('data/closing', $data);
            $this->load->view('templates/footer');
        } else {
            $tanggal = $this->input->post('tanggal');
            $timestamp = strtotime($tanggal);
            $hariInggris = date('l', $timestamp);

            $namaHariIndonesia = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            $hari = $namaHariIndonesia[$hariInggris];

            $data_update = [
                'id_sales' => $this->input->post('id_sales'),
                'id_nasabah' => $this->input->post('id_nasabah'),
                'tanggal' => $tanggal,
                'hari' => $hari,
                'nominal_closing' => $this->input->post('nominal_closing'),
            ];

            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/closing';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['closing']['upload_foto'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/closing/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $data_update['upload_foto'] = $new_image;
                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button></div>');
                    redirect('data/closing');
                }
            }

            $this->db->where('id_closing', $this->input->post('id_closing'));
            $this->db->update('closing', $data_update);
            $this->session->set_flashdata("flashswal", "Diubah");
            redirect('data/closing');
        }
    }


    public function closinghapus($id_closing)
    {
        $this->db->delete("closing", ["id_closing" => $id_closing]);
        $this->session->set_flashdata("flashswal", "Dihapus");
        redirect('data/closing');
    }
