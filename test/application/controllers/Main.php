<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

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
		date_default_timezone_set("Asia/Kolkata");
	}

	public function user_data(){
		$user_data = array();
		if($this->session->userdata('username'))
		$user_data =$this->Model_users->user_data($this->session->userdata('username'),'user_id','username','password','phone','firstName','lastName','email','city','state','postalCode','country','provider','type');
		$user_data['logged_in'] = $this->session->userdata('username');

		return $user_data;
	}
	public function index()
	{


		$user_data = array();
		$this->load->model("Model_users");
		if($this->session->userdata('username'))
		$user_data =$this->Model_users->user_data($this->session->userdata('username'),'user_id','username','password','firstName','lastName','email','city','phone','state','postalCode','country','provider','type');
		$user_data['logged_in'] = $this->session->userdata('username');

		$recentProperties  = $this->Model_users->recentProperties();

		$data = array(
			"user_data"  => $user_data,
			"recentProperties" => $recentProperties
		);
		$this->load->view('index.php',$data);
	}

	public function login()
	{
		$this->load->view("login.php");
	}
	public function register()
	{
		$this->load->view("register.php");
	}
	public function about()
	{
		$this->load->view("about.php");
	}
	public function contact()
	{
		$this->load->view("contact.php");
	}

	public function profile(){
    $panunkartAgents = "";
		$callbackRequests = "";

	  $user_data = $this->user_data();
    $listedApartments = $this->Model_users->listedProperties($user_data['user_id']);
		if($user_data['type'] == 1)
		$listedAgents = $this->Model_users->listedAgents($user_data['username']);
		else{
			$listedAgents = "";
		}

		if($user_data['type'] == 2){
			//$panunkartAgents = $this->Model_users->panunkartAgents();
		}
		$locationsQuery = $this->Model_users->locationsQuery();


		if($user_data['type'] == 1){
			$callbackRequests = $this->Model_users->callbackRequests($user_data['user_id']);
		}else if($user_data['type'] == 2){
			$callbackRequests = $this->Model_users->allCallbackRequests();
		}

		$this->load->view("profile.php",array("user_data" => $user_data,
                                           "listedApartments" => $listedApartments,
                                            "listedAgents" => $listedAgents,
																						"locationsQuery" => $locationsQuery,
																						//"panunkartAgents" => $panunkartAgents,
																						"callbackRequests" => $callbackRequests
																				 ));

	}


	/*public function loginValidation()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','Username','required|trim|callback_validate_credentials');
		$this->form_validation->set_rules('password','Password','required|md5|trim');

		if($this->form_validation->run()){

            $data = array(
                 'username' => $this->input->post('username'),
                 'is_logged_in' => 1
            	);
			$this->session->set_userdata($data);
			redirect(base_url().'index.php/main/');
		}
		else{
			$this->load->view('login');
		}
	}
	*/


	public function loginValidation()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','Username','required|trim|callback_validate_credentials');
		$this->form_validation->set_rules('password','Password','required|md5|trim');

		if($this->form_validation->run()){

            $data = array(
                 'username' => $this->input->post('username'),
                 'is_logged_in' => 1
            	);
			$this->session->set_userdata($data);
			echo "<h4 id='success' style='color:green;'>Logging you in...</h4>";
		}
		else{
			echo validation_errors();
		}
	}
// checks if the user exists or not in the database
	public function validate_credentials(){
		$this->load->model('Model_users');

		if($this->Model_users->can_log_in()){
			return true;

		}
		else{
				$this->form_validation->set_message('validate_credentials','Incorrect username/password.');
				return false;
			}
	}

public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url().'index.php/main/index');
	}


	 public function signupValidation()
    {

    	$this->load->library('form_validation');
    	$this->load->model('model_users');


		$this->form_validation->set_rules('registerFirstName','First Name','required|trim|alpha');
	//	$this->form_validation->set_rules("phone","Phone","required|trim|numeric");
		$this->form_validation->set_rules("registerUsername","Username","required|trim|alpha_numeric|is_unique[users.username]|is_unique[temp_users.username]");
    	$this->form_validation->set_rules('registerEmail','Email','required|trim|valid_email|is_unique[users.email]|is_unique[temp_users.email]');
    	$this->form_validation->set_rules('registerPassword','password','required|trim');
    	$this->form_validation->set_rules('registerCPassword','confirm password','required|trim|matches[registerPassword]');
		$this->form_validation->set_rules("registerTerms","registerTerms","callback_terms");
        $this->form_validation->set_message('is_unique','That %s address already exists');


        if($this->form_validation->run())
        {

        	$this->load->library('email',array('mailtype' => 'html'));

        	$this->email->from('support@test.panunkart.com','confirm your account');
        	$this->email->to($this->input->post('registerEmail'));
        	$this->email->subject('Confirm you email');

        	$key = md5(uniqid()+time());

			$welcome = "<h2>Welcome to Properties.panunkart.com.<br>
			Our sole mission to help you find your dream property with hassle free lookup.Thanks for Joining properties.panunkart.com
			and we hope you enjoy our services.Good luck!!<br>
			Please click on the link below to confirm your account.<br>
			</h5>";
        	$message = $welcome."<br>Confirm your email ".base_url()."index.php/main/register_user/$key";

        	$this->email->message($message);




		        		if($this->email->send())
		        	{
								if($this->model_users->add_temp_user($key))
		        		echo "<p style='color:green;'>Confirmation Email has been sent to your email...please visit the link in the email to confirm verify your account</p>";
								else {
									echo "problem registering you at this moment..please try later";
								}
		        	}
		        	else
		        	{
		        		echo "Sorry,we could not send the email confirmation to your account..please try later";
		        	}


        }
        else
        {
        	echo validation_errors();
        }
  }


	public function terms()
	{
		if($this->input->post("registerTerms") == 'true')
		{
			return true;
		}
		else
		{
      $this->form_validation->set_message("terms","Please accept the terms and Conditions to proceed");
			return false;
		}
	}

	public function register_user($key)
  {
     $this->load->model('model_users');

     if($this->model_users->is_key_valid($key))
     {
     	if($email = $this->model_users->add_user($key))
     	{
     		//$data = array(
                //  'email' => $email,
                 // 'is_logged_in'=>1

     			//);

     		//$this->session->set_userdata($data);
     		//redirect('main/members');
			echo "<h2>your account has been confirmed successfully.You can now log in</h2>";
     	}
     	else
     	{
     		echo "failed...please try agian";
     	}
     }
     else
     {
     	echo "invalid key";
     }
  }


		public function globalSearch(){
		$this->load->model("Model_users");
    $page = (int)$this->input->get("page");
	  $per_page =2;

     $start = ($page > 1)?($page * $per_page) - $per_page:0;
     $priceArr = array();
	   $price = $this->input->get("Budget");
		 $propertyType  = $this->input->get("Property");
		 $bhk = $this->input->get("bhk");
		 $location = "".$this->input->get("global_search_location");
     if(empty($price) === false)
		 $priceArr = explode("-",$price);

		 $propertyType = strtolower($propertyType);

		 if(empty($propertyType) === true || empty($location) === true){
			 echo "<h3><b>Invalid Data Receieved</b></h3>";
			 die();
		 }




		 for ($i=0;$i<sizeof($priceArr);$i++){
		   if(strpos($priceArr[$i],'lac') !== false){
		        $priceArr[$i] = (int)str_replace("lac","00000",$priceArr[$i]);
		   }
		   if(strpos($priceArr[$i],'K') !== false){
		        $priceArr[$i]= (int)str_replace("K","000",$priceArr[$i]);
		   }
		   if(strpos($priceArr[$i],'Cr') !== false){
		        $priceArr[$i] = (int)str_replace("Cr","0000000",$priceArr[$i]);
		   }
		 }

    $loc = explode(",",$location);
		$location = $loc[0];
		$locationId = 0;
     $locationIds = $this->db->query("SELECT locationId FROM locations WHERE city = '$location'");
		 foreach($locationIds->result() as $x){
			 $locationId = $x->locationId;
		 }

    $locationId = (int)$locationId;



   if(empty($price) == true && empty($bhk) == true){
		 //echo "SELECT * FROM $propertyType WHERE locationId=$locationId";
		 //die();
			$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS $propertyType.*,users.provider FROM $propertyType INNER JOIN users ON $propertyType.sellerId = users.user_id WHERE $propertyType.locationId=$locationId LIMIT {$start},{$per_page} ");
		}
		else if(empty($bhk)){
			//echo "SELECT * FROM $propertyType WHERE `price` BETWEEN $priceArr[0] AND $priceArr[1] AND locationId=$locationId";
			//die();
			$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS $propertyType.*,users.provider FROM $propertyType INNER JOIN users ON $propertyType.sellerId = users.user_id WHERE `price` BETWEEN $priceArr[0] AND $priceArr[1] AND locationId=$locationId LIMIT {$start},{$per_page}");
		}
		else if(empty($price)){
			//echo "SELECT * FROM $propertyType WHERE `BHK` = $bhk  AND locationId=$locationId";
		//	die();
			$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS $propertyType.*,users.provider FROM $propertyType INNER JOIN users ON $propertyType.sellerId = users.user_id WHERE `BHK` = '$bhk'  AND locationId=$locationId LIMIT {$start},{$per_page}");
		}
		else{
			//echo "SELECT * FROM $propertyType WHERE BHK = '$bhk' AND `price` BETWEEN $priceArr[0] AND $priceArr[1] AND locationId=$locationId";
			//die();
    $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS $propertyType.*,users.provider FROM $propertyType INNER JOIN users ON $propertyType.sellerId = users.user_id WHERE BHK = '$bhk' AND `price` BETWEEN $priceArr[0] AND $priceArr[1] AND locationId=$locationId LIMIT {$start},{$per_page}");
}
    $propertyName = ucfirst($propertyType);

/***********************************Main Search Page Content goes here**********************************/



    $total = $this->db->query("SELECT FOUND_ROWS() as total")->row()->total;

		$pages = ceil($total/$per_page);

     $user_data = array();


		if($this->session->userdata('username'))
		$user_data =$this->Model_users->user_data($this->session->userdata('username'),'user_id','username','password','firstName','lastName','email','city','state','postalCode','country');

		$user_data['logged_in'] = $this->session->userdata('username');
		//$this->load->view("core/functions/functions.php");


    $search_data = array(
       'user_data' => $user_data,
			 'total'  => $total,
			 'pages'  => $pages,
			 'propertyName' => $propertyName,
			 'query' => $query,
			 'locationId' => $locationId,
			 'propertyType' => $propertyType,
			 'bhk' => $bhk,
			 'price' => $price,
			 'location' => $location
		);


		$this->load->view('search.php',$search_data);

}


///////////////////////////////contact validation/////////////////////////////////////


public function contact_validation(){

	$this->load->library('form_validation');
	 $this->load->model('Model_users');
	  $this->form_validation->set_rules('name','name','required');
	   $this->form_validation->set_rules('email','email','required|trim');
	    $this->form_validation->set_rules('number','number','required');
		  $this->form_validation->set_rules('msg','msg');




	 if($this->form_validation->run()){
	    if($this->Model_users->contact_user()){
	       echo "thank you! we'll contact you soon.";
	    }else {
	       echo "contact not received";
	    }
	 } else {
	         echo validation_errors();

	        $this->load->view('contact');
	 }





	}


/*************************************** location hint ***********************************************************/

public function get_location_hint($q){

	// Array with names
$this->load->model("Model_users");
$a = $this->Model_users->get_locations();


$hint = "";

// lookup all hints from array if $q is different from ""
if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    foreach($a as $name) {
        if (stristr($q, substr($name, 0, $len))) {
            if ($hint === "") {
                $hint = "<option style='padding:3px;'value='$name'>";
            } else {
                $hint .= "<option style='padding:3px;' value='$name'>";
            }
        }
    }
}

// Output "no suggestion" if no hint was found or output correct values
echo $hint === "" ? "no matching location available,try some other" : $hint;
}





public function agent_validation(){

     $user_data = array();


		if($this->session->userdata('username'))
		$user_data =$this->Model_users->user_data($this->session->userdata('username'),'user_id','username','password','firstName','lastName','email','city','state','postalCode','country','provider','type');
		$user_data['logged_in'] = $this->session->userdata('username');
		$table = $user_data['username']."_agents.email";
	$this->load->library('form_validation');
	 $this->load->model('Model_users');
	  $this->form_validation->set_rules('firstName','firstName','required|trim|alpha_numeric');
	   $this->form_validation->set_rules('lastName','lastName','required|trim|alpha_numeric');
	    $this->form_validation->set_rules('email','email','required|trim|valid_email|is_unique['.$table.']');
		  $this->form_validation->set_rules('phone','phone','required|trim');




	 if($this->form_validation->run()){
	    if($this->Model_users->addNewAgent()===true){
	       echo "Agent is Added SuccessFully";
	    }else {
	       echo "Unknown Error occured.Please try again...agent not added";
	    }
	 } else {
	         echo validation_errors();
	 }
}
/***********************************add Property**********************************************/

public function addProperty(){
	$image = $this->do_upload(strtolower($this->input->post("propertyType")),$this->input->post("propertyName"),$this->input->post("price"));
	if($image !== false){
	  echo "<p style='color:green;'>Image Uploaded</p>";
	}else {
		echo "Image not uploaded try again";
		die();
	}



  $this->form_validation->set_rules("propertyType","propertyType","required|trim|alpha_numeric");
  $this->form_validation->set_rules("propertyName","propertyName","required|alpha");
  $this->form_validation->set_rules("BHK","BHK","required|alpha_numeric");
	$this->form_validation->set_rules("addPropertyLocationId","Location","required|trim|alpha_numeric");
	//$this->form_validation->set_rules("pin","pin","required|numeric|trim");

	if($this->form_validation->run()){
		if($this->Model_users->addProperty($image)){
			echo "<p style='color:green;'>Property Added Successfully</p>";
		}
		else{
			echo "unKnown Error Occurred...please try again...property not added";
		}
	}else{
		echo validation_errors();
	}

}

/***************************************** uploadImage*************************************/
public function do_upload($propertyType,$propertyName,$price){

 // $propertyType = strtolower($this->input->post("propertyType"));
 //
 // $config['upload_path'] = 'assets/media/apartment/';
 // $config['allowed_types'] = 'gif|jpg|png';
 // $config['max_size']	= '1000';
 // $config['max_width']  = '1024';
 // $config['max_height']  = '768';
 //
 // $this->load->library('upload', $config);
 //
 // if ( ! $this->do_upload())
 // {
 //  $error = array('error' => $this->display_errors());
 //
 //  return false;
 // }
 // else
 // {
 //  $data = array('upload_data' => $this->data());
 //
 //   return true;
 // }

 $target_dir = "assets/media/$propertyType/";
 $target_file = $target_dir . basename($_FILES["propertyImageAgent"]["name"]);

 $temp = explode(".", $_FILES["propertyImageAgent"]["name"]);
 $newfilename =$propertyName.$price.'.' . end($temp);


 $uploadOk = 1;
 $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

 if(empty($_FILES['propertyImageAgent']) === true){
	 echo "No Image Selected";
	 return false;
 }
 // Check if image file is a actual image or fake image
 if(isset($_POST["submit"])) {
     $check = getimagesize($_FILES["propertyImageAgent"]["tmp_name"]);
     if($check !== false) {
         $uploadOk = 1;
     } else {
         $uploadOk = 0;
     }
 }
 // Check if file already exists
 if (file_exists($target_dir.$newfilename)) {
     echo "Sorry, file already exists.";
     $uploadOk = 0;
 }
 // Check file size
 if ($_FILES["propertyImageAgent"]["size"] > 1000000) {
     echo "Sorry, your file is too large.";
     $uploadOk = 0;
 }
 // Allow certain file formats
 if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
 && $imageFileType != "gif" ) {
     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
     $uploadOk = 0;
 }
 // Check if $uploadOk is set to 0 by an error
 if ($uploadOk == 0) {
     return false;
 // if everything is ok, try to upload file
 } else {
     if (move_uploaded_file($_FILES["propertyImageAgent"]["tmp_name"], $target_dir.$newfilename)) {
         return $newfilename;
     } else {
         return false;
     }
 }

}

/*************************************requesting callback**************************/
public function requestCallbackPage(){
	$user_data = $this->user_data();
	if(empty($user_data['logged_in']) === true)
	$this->load->view("requestCallback",array("user_data" => $user_data));
	else {
		$this->requestCallback();
	}
}
public function requestCallback(){

	$user_data = $this->user_data();
	if(isset($_GET['propertyType']) === false||isset($_GET['id']) === false){
     echo "Invalid Arguments";
		 die();
	}

	$propertyType = $this->input->get("propertyType");
	$id = $this->input->get("id");
if(empty($user_data['logged_in']) === true){
  $this->form_validation->set_rules("buyerName","Name","required|trim|alpha");
	$this->form_validation->set_rules("buyerPhone","Phone","required|numeric");
	$this->form_validation->set_rules("buyerEmail","Email","required|valid_email");

	if($this->form_validation->run()){
		if($this->Model_users->requestCallback($propertyType,$id,$user_data) === true){
/**********************sending mail to various sides********************************/

					$this->load->library('email',array('mailtype' => 'html'));

					$this->email->from('support@test.panunkart.com','panunkart.com');

					/*******************sending mail to user******************************/
					$this->email->to($this->input->post('buyerEmail'));
					$this->email->subject('Thanks for reaching out');



					$welcome = "Thanks for choosing your property with panunkart.Our agents will contact
					you soon";

						$message = $welcome;

						$this->email->message($message);
						$this->email->send();
/**************************sending mail to respective org representative**************/
						$this->email->to($this->Model_users->agentEmail($propertyType,$id));
						$this->email->subject("New Call back Request for your listed property");

						$this->email->message("You have a new call_back request.go to panunkart to respond");
						$this->email->send();

/******************************sending mail to panunkart adimin**********************/

            $this->email->to($this->Model_users->adminEmail());
						$this->email->subject("New call back request");
						$this->email->message("A new callback request has been posted to panunkart.Visit your site to check status");

						$this->email->send();
					echo "yes";

/******************************** mail sending ends here*****************************/


		}else{
			echo "<p style='color:red;'>Cannot make the Callback request please try again</p>";
		}
	}else {
		echo validation_errors();
	}
}else{
	if($this->Model_users->requestCallback($propertyType,$id,$user_data)){

		/**********************sending mail to various sides********************************/

						 $this->load->library('email',array('mailtype' => 'html'));

						 $this->email->from('support@test.panunkart.com','panunkart.com');

						 /*******************sending mail to user******************************/
						 $this->email->to($this->input->post('buyerEmail'));
						 $this->email->subject('Thanks for reaching out');



						 $welcome = "Thanks for choosing your property with panunkart.Our agents will contact
						 you soon";

							 $message = $welcome;

							 $this->email->message($message);
							 $this->email->send();
		/**************************sending mail to respective org representative**************/
							 $this->email->to($this->Model_users->agentEmail($propertyType,$id));
							 $this->email->subject("New Call back Request for your listed property");

							 $this->email->message("You have a new call_back request.go to panunkart to respond");
							 $this->email->send();

		/******************************sending mail to panunkart adimin**********************/

								$this->email->to($this->Model_users->adminEmail());
							 $this->email->subject("New call back request");
							 $this->email->message("A new callback request has been posted to panunkart.Visit your site to check status");

							 $this->email->send();


		/******************************** mail sending ends here*****************************/





		echo "<h3>Call Back Request sent successfully</h3>";
	}
	else{
		echo "<h3>Error sending callback Request..please try again";
	}
}

}


}
?>
