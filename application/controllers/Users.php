<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Buat ngilangin red line, karena intelphense
 *  @property form_validation $form_validation 
 *  @property load $load 
 *  @property input $input 
 *  @property db $db
 *  @property session $session
 *  @property upload $upload
 */

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // cek jika ada gambar yang akan diupload

            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                // . itu root
                $config['upload_path'] = './assets/img/profile';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);

                } else {
                    echo $this->upload->display_errors();
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button></div>');
                    redirect('users/edit');
                }
            }

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('users');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
            redirect('users');
        }
    }

    public function changepassword()
    {
        $role_id = $this->session->userdata("role_id");

        $this->db->select('role');
        $this->db->from('user_role');
        $this->db->where('id', $role_id);
        $query = $this->db->get();
        $result = $query->row_array();

        $data['roleuser'] = $result['role'];

        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('users', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[3]|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');

            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                redirect('users/changepassword');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                    redirect('users/changepassword');
                } else {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('users');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password Changed!<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>');
                    redirect('users/changepassword');
                }
            }
        }

    }

}
