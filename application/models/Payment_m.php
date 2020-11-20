<?php
class Payment_m extends CI_Model {
    
    function __construct(){
        parent::__construct();
        $this->load->library('session');
    }
    
    public function insert($data,$test){
       $data2=array(
        'no_order'=>$data->order_id,
        'vac'=>$data->bca_va_number,
        'nama'=>$test
       );

       $result = $this->db->insert('testing',$data2);

    }

    public function insert2($data){
       $data2=array(
        'no_order'=>$data->no_order,
        'vac'=>$data->vac,
        'nama'=>$data->nama,
        'no_hp'=>$data->no_hp,
        'total_bayar'=>$data->total_bayar
       );

       $result = $this->db->insert('testing',$data2);

    }
}
?>