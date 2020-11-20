<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Snap extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
    {
        parent::__construct();
        $params = array('server_key' => 'SB-Mid-server-sRAhlaR2X_DecS9uu662TEqd', 'production' => false);
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
		$this->load->model('Payment_m');
    }

    public function index()
    {
    	$this->load->view('checkout_snap');
    }

    public function bayar()
    {
    	$ch = curl_init();

    	 // set url 
    	curl_setopt($ch, CURLOPT_URL, "http://localhost/slim2/public/countries");
    	 // return the transfer as a string 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	    // $output contains the output string 
	    $output = curl_exec($ch); 

	    // tutup curl 
	    curl_close($ch);      

	    // menampilkan hasil curl
	    $test = json_decode($output);
	    $val = $test->data;

	    print_r($val);

	    foreach ($val as $key => $value) {
	    	$this->Payment_m->insert2($value);
    	}

	    //$this->Payment_m->insert2($test);

    	//$this->load->view('pembayaran');
    }

    public function token()
    {
    	$nama = $this->input->post('nama');
    	$this->session->set_flashdata('nama',$nama);

    	$email = $this->input->post('email');
    	$harga = $this->input->post('harga');
    	$qty = $this->input->post('qty');
		
		// Required
		$transaction_details = array(
		  'order_id' => rand(),
		  'gross_amount' => $harga*$qty, // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		  'id' => 'a1',
		  'price' => $harga,
		  'quantity' => $qty,
		  'name' => "Gb Skripsi"
		);

		// Optional
		$item_details = array ($item1_details);


		// Optional
		$customer_details = array(
		  'first_name'    => $nama,
		  'email'         => $email,
		  'phone'         => "081122334455",
		);

		// Data yang akan dikirim untuk request redirect_url.
        $credit_card['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O",$time),
            'unit' => 'minute', 
            'duration'  => 180
        );
        
        $transaction_data = array(
            'transaction_details'=> $transaction_details,
            'item_details'       => $item_details,
            'customer_details'   => $customer_details,
            'credit_card'        => $credit_card,
            'expiry'             => $custom_expiry
        );

		error_log(json_encode($transaction_data));
		$snapToken = $this->midtrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;
    }

    public function finish()
    {
    	$result = json_decode($this->input->post('result_data'));
    	$test = $this->input->post('nama');
    	echo 'RESULT <br><pre>';
    	var_dump($result);
    	echo '</pre>' ;

    	$this->Payment_m->insert($result,$test);
    }
}
