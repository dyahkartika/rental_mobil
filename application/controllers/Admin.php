<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {
    function __construct(){  //pada function ini terdapat pengecekan jika admin belum login maka dialikan ke halaman login
        parent::__construct();
        if($this->session->userdata('status') != "login"){
            redirect(base_url().'welcome?pesan=belumlogin');
        }
    }
    function index(){
        $data['transaksi'] = $this->db->query("select * from 
        transaksi order by transaksi_id desc limit 10")->result();
        $data['kostumer'] = $this->db->query("select * from 
        kostumer order by kostumer_id desc limit 10")->result();
        $data['mobil'] = $this->db->query("select * from mobil 
        order by mobil_id desc limit 10")->result();
        $this->load->view('admin/header');  //memparsing data yang diambil dari database
        $this->load->view('admin/index',$data);
        $this->load->view('admin/footer');
    }
    function logout(){
        $this->session->sess_destroy();
        redirect(base_url().'welcome?pesan=logout');
    }
    function ganti_password(){
        $this->load->view('admin/header');
        $this->load->view('admin/ganti_password');
        $this->load->view('admin/footer');
    }
    function ganti_password_act(){  //function untuk menangani form ganti password
        $pass_baru = $this->input->post('pass_baru');
        $ulang_pass = $this->input->post('ulang_pass');
        $this->form_validation->set_rules('pass_baru','Password Baru','required|matches[ulang_pass]');
        $this->form_validation->set_rules('ulang_pass','Ulangi Password Baru','required');
        if($this->form_validation->run() != false){  //cek validasi
        $data = array('admin_password' => md5($pass_baru));
        $w = array('admin_id' => $this->session->userdata('id'));
        $this->m_rental->update_data($w,$data,'admin');
        redirect(base_url().'admin/ganti_password?pesan=berhasil');
        } else{
        $this->load->view('admin/header');
        $this->load->view('admin/ganti_password');
        $this->load->view('admin/footer');
        }
    }
    // CRUD MOBIL
    function mobil(){
        $data['mobil'] = $this->m_rental->get_data('mobil')->result();  //ambil data dari database
        $this->load->view('admin/header');
        $this->load->view('admin/mobil',$data);
        $this->load->view('admin/footer');
    }
    function mobil_add(){
        $this->load->view('admin/header');
        $this->load->view('admin/mobil_add');
        $this->load->view('admin/footer');
    }
    function mobil_add_act(){
        $merk = $this->input->post('merk');
        $plat = $this->input->post('plat');
        $warna = $this->input->post('warna');
        $tahun = $this->input->post('tahun');
        $status = $this->input->post('status');
        $this->form_validation->set_rules('merk','Merk 
        Mobil','required');
        $this->form_validation->set_rules('status','Status 
        Mobil','required');
         if($this->form_validation->run() != false){
        $data = array(
        'mobil_merk' => $merk,
        'mobil_plat' => $plat,
        'mobil_warna' => $warna,
        'mobil_tahun' => $tahun,
        'mobil_status' => $status
        );
        $this->m_rental->insert_data($data,'mobil');
        redirect(base_url().'admin/mobil');
        }else{
        $this->load->view('admin/header');
        $this->load->view('admin/mobil_add');
        $this->load->view('admin/footer');
        } 
    }
    function mobil_edit($id){
        $where = array(
        'mobil_id' => $id
        );
        $data['mobil'] = $this->m_rental->edit_data($where,'mobil')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/mobil_edit',$data);
        $this->load->view('admin/footer');
    }
    function mobil_update(){
        $id = $this->input->post('id');
        $merk = $this->input->post('merk');
        $plat = $this->input->post('plat');
        $warna = $this->input->post('warna');
        $tahun = $this->input->post('tahun');
        $status = $this->input->post('status');
        $this->form_validation->set_rules('merk','Merk 
        Mobil','required');
        $this->form_validation->set_rules('status','Status 
        Mobil','required');
        if($this->form_validation->run() != false){
        $where = array(
        'mobil_id' => $id
        );
        $data = array(
        'mobil_merk' => $merk,
        'mobil_plat' => $plat,
        'mobil_warna' => $warna,
        'mobil_tahun' => $tahun,
        'mobil_status' => $status
        );
        $this->m_rental->update_data($where,$data,'mobil');
        redirect(base_url().'admin/mobil');
        }else{
        $where = array(
        'mobil_id' => $id
        );
        $data['mobil'] = $this->m_rental->edit_data($where,'mobil')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/mobil_edit',$data);
        $this->load->view('admin/footer');
        } 
    }
    function mobil_hapus($id){
        $where = array(
        'mobil_id' => $id
        );
        $this->m_rental->delete_data($where,'mobil');
        redirect(base_url().'admin/mobil');
    }
    // AKHIR CRUD MOBIL

    // CRUD KOSTUMER
    function kostumer(){
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/kostumer',$data);
        $this->load->view('admin/footer');
    }
    function kostumer_add(){
        $this->load->view('admin/header');
        $this->load->view('admin/kostumer_add');
        $this->load->view('admin/footer');
    }
    function kostumer_add_act(){
        $nama = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        $jk = $this->input->post('jk');
        $hp = $this->input->post('hp');
        $ktp = $this->input->post('ktp');
        $this->form_validation->set_rules('nama','nama','required');
        if($this->form_validation->run() != false){
            $data = array(
                'kostumer_nama' => $nama,
                'kostumer_alamat' => $alamat,
                'kostumer_jk' => $jk,
                'kostumer_hp' => $hp,
                'kostumer_ktp' => $ktp);
                $this->m_rental->insert_data($data,'kostumer');
                redirect(base_url().'admin/kostumer');
        } else{
            $this->load->view('admin/header');
            $this->load->view('admin/kostumer_add');
            $this->load->view('admin/footer');
        } 
    }
    function kostumer_edit($id){
        $where = array(
        'kostumer_id' => $id
        );
        $data['kostumer'] = $this->m_rental->edit_data($where,'kostumer')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/kostumer_edit',$data);
        $this->load->view('admin/footer');
    }
    function kostumer_update(){
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        $jk = $this->input->post('jk');
        $hp = $this->input->post('hp');
        $ktp = $this->input->post('ktp');
        $this->form_validation->set_rules('nama','nama','required');
        if($this->form_validation->run() != false){
            $where = array(
                'kostumer_id' => $id
            );
            $data = array(
                'kostumer_nama' => $nama,
                'kostumer_alamat' => $alamat,
                'kostumer_jk' => $jk,
                'kostumer_hp' => $hp,
                'kostumer_ktp' => $ktp
            );
            $this->m_rental->update_data($where,$data,'kostumer');
            redirect(base_url().'admin/kostumer');
        }else{
            $where = array(
                'kostumer_id' => $id
            );
            $data['kostumer'] = $this->m_rental->edit_data($where,'kostumer')->result();
            $this->load->view('admin/header');
            $this->load->view('admin/kostumer_edit',$data);
            $this->load->view('admin/footer');
        } 
    }
    function kostumer_hapus($id){
        $where = array(
        'kostumer_id' => $id
        );
        $this->m_rental->delete_data($where,'kostumer');
        redirect(base_url().'admin/kostumer');
    }
    // AKHIR CRUD MOBIL
    
    function transaksi(){
        $data['transaksi'] = $this->db->query("select * from 
        transaksi,mobil,kostumer where transaksi_mobil=mobil_id and 
        transaksi_kostumer=kostumer_id")->result();  //mengambil data dari tabel transaksi, mobil, dan kostumer dengan id sama
        $this->load->view('admin/header');
        $this->load->view('admin/transaksi',$data);
        $this->load->view('admin/footer');
    }
    function transaksi_add(){
        $w = array('mobil_status'=>'1');
        $data['mobil'] = $this->m_rental->edit_data($w,'mobil')->result();  //mengirim 2 parsing data ke view transaksi_add
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/transaksi_add',$data);
        $this->load->view('admin/footer');
    }
    function transaksi_add_act(){
        $kostumer = $this->input->post('kostumer');
        $mobil = $this->input->post('mobil');
        $tgl_pinjam = $this->input->post('tgl_pinjam');
        $tgl_kembali = $this->input->post('tgl_kembali');
        $harga = $this->input->post('harga');
        $denda = $this->input->post('denda');
        $this->form_validation->set_rules('kostumer','Kostumer','required');
        $this->form_validation->set_rules('mobil','Mobil','required');
        $this->form_validation->set_rules('tgl_pinjam','Tanggal 
        Pinjam','required');
        $this->form_validation->set_rules('tgl_kembali','Tanggal 
        Kembali','required');
        $this->form_validation->set_rules('harga','Harga','required');
        $this->form_validation->set_rules('denda','Denda','required');
        if($this->form_validation->run() != false){
        $data = array(
        'transaksi_karyawan' => $this->session->userdata('id'),
        'transaksi_kostumer' => $kostumer,
        'transaksi_mobil' => $mobil,
        'transaksi_tgl_pinjam' => $tgl_pinjam,
        'transaksi_tgl_kembali' => $tgl_kembali,
        'transaksi_harga' => $harga,
        'transaksi_denda' => $denda,
        'transaksi_tgl' => date('Y-m-d')
        );
        $this->m_rental->insert_data($data,'transaksi');
        // update status mobil yg di pinjam
        $d = array(
        'mobil_status' => '2');
        $w = array(
        'mobil_id' => $mobil);
        $this->m_rental->update_data($w,$d,'mobil');
        redirect(base_url().'admin/transaksi');
        }else{
        $w = array('mobil_status'=>'1');
        $data['mobil'] = $this->m_rental->edit_data($w,'mobil')->result();
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        $this->load->view('admin/header');
        $this->load->view('admin/transaksi_add',$data);
        $this->load->view('admin/footer');
        } 
    }
    function transaksi_hapus($id){
        $w = array(
        'transaksi_id' => $id
        );
        $data = $this->m_rental->edit_data($w,'transaksi')->row();
        $ww = array(
        'mobil_id' => $data->transaksi_mobil
        );
        $data2 = array(
        'mobil_status' => '1'
        );
        $this->m_rental->update_data($ww,$data2,'mobil');
        $this->m_rental->delete_data($w,'transaksi');
        redirect(base_url().'admin/transaksi');
    }
    function transaksi_selesai($id){
        $data['mobil'] = $this->m_rental->get_data('mobil')->result();
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        $data['transaksi'] = $this->db->query("select * from 
        transaksi,mobil,kostumer where transaksi_mobil=mobil_id and 
        transaksi_kostumer=kostumer_id and transaksi_id='$id'")->result();
        $this->load->view('admin/header');
        $this->load->view('admin/transaksi_selesai',$data);
        $this->load->view('admin/footer');
    }
    function transaksi_selesai_act(){
        $id = $this->input->post('id');
        $tgl_dikembalikan = $this->input->post('tgl_dikembalikan');
        $tgl_kembali = $this->input->post('tgl_kembali');
        $mobil = $this->input->post('mobil');
        $denda = $this->input->post('denda');
        $this->form_validation->set_rules('tgl_dikembalikan','Tanggal 
        Di Kembalikan','required');
        if($this->form_validation->run() != false){
        // menghitung selisih hari
        $batas_kembali = strtotime($tgl_kembali);  //Tanggal kembali kita masukkan ke variabel $batas_kembali
        $dikembalikan = strtotime($tgl_dikembalikan); //tanggal mobil di kembalikan kita masukkan ke variabel $dikembalikan
        $selisih = abs(($batas_kembali -
        $dikembalikan)/(60*60*24));  //fungsi abs() berguna untuk membulatkan bilangan
        $total_denda = $denda*$selisih;
        // update status transaksi
        $data = array(
        'transaksi_tgldikembalikan' => $tgl_dikembalikan,
        'transaksi_status' => '1',
        'transaksi_totaldenda' => $total_denda
        );
        $w = array(
        'transaksi_id' => $id
        );
        $this->m_rental->update_data($w,$data,'transaksi');
        // update status mobil
        $data2 = array(
        'mobil_status' => '1'
        );
        $w2 = array(
        'mobil_id' => $mobil
        );
        $this->m_rental->update_data($w2,$data2,'mobil');
        redirect(base_url().'admin/transaksi');
        }else{
        $data['mobil'] = $this->m_rental->get_data('mobil')->result();
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        $data['transaksi'] = $this->db->query("select * from 
        transaksi,mobil,kostumer where transaksi_mobil=mobil_id and 
        transaksi_kostumer=kostumer_id and transaksi_id='$id'")->result();
        $this->load->view('admin/header');
        $this->load->view('admin/transaksi_selesai',$data);
        $this->load->view('admin/footer');
        } 
    }
    // AKHIR TRANSAKSI RENTAL

    //LAPORAN
    function laporan(){
        $dari = $this->input->post('dari');
        $sampai = $this->input->post('sampai');
        $this->form_validation->set_rules('dari','Dari 
        Tanggal','required');
        $this->form_validation->set_rules('sampai','Sampai 
        Tanggal','required');
        if($this->form_validation->run() != false){
        $data['laporan'] = $this->db->query("select * from 
        transaksi,mobil,kostumer where transaksi_mobil=mobil_id and 
        transaksi_kostumer=kostumer_id and date(transaksi_tgl) >= '$dari'")->result();
        $this->load->view('admin/header');
        $this->load->view('admin/laporan_filter',$data);
        $this->load->view('admin/footer');
        }else{
        $this->load->view('admin/header');
        $this->load->view('admin/laporan');
        $this->load->view('admin/footer');
        } 
    }
    function laporan_print(){
        $dari = $this->input->get('dari');
        $sampai = $this->input->get('sampai');
        if($dari != "" && $sampai != ""){
        $data['laporan'] = $this->db->query("select * from 
        transaksi,mobil,kostumer where transaksi_mobil=mobil_id and 
        transaksi_kostumer=kostumer_id and date(transaksi_tgl) >= '$dari'")->result();
        $this->load->view('admin/laporan_print',$data);
        }else{
        redirect("admin/laporan");
        }
    }
}
