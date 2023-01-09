<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {

        function __construct(){ //function yang pertama kali dijalankan saat sebuah class dijalankan
                parent::__construct();
                $this->load->model('m_rental');  //memanggil m_rental karena akan berhubungan dengan model m_rental
        }
        public function index(){  
                $this->load->view('login'); //di sini terdapat perintah untuk menampilkan view login
        }
        function login(){
                $username = $this->input->post('username');  //menangkap data yang di kirim dari form
                $password = $this->input->post('password');
                $this->form_validation->set_rules('username','Username','trim|required');
                $this->form_validation->set_rules('password','Password','trim|required');
                if($this->form_validation->run() != false){  //cek validasi benar tidak
                    $where = array(
                        'admin_username' => $username,
                        'admin_password' => md5($password)
                    );
                    $data = $this->m_rental->edit_data($where,'admin');
                    $d = $this->m_rental->edit_data($where,'admin')->row();
                    $cek = $data->num_rows();
                    if($cek > 0){
                        $session = array(
                                'id'=> $d->admin_id,
                                'nama'=> $d->admin_nama,
                                'status' => 'login'
                        );
                        $this->session->set_userdata($session);
						redirect(base_url().'admin');
                    }else{
                         redirect(base_url().'welcome?pesan=gagal');
                    }
               }else{
                    $this->load->view('login');
               }
        }
}