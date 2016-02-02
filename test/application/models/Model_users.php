<?php
class Model_users extends CI_Model
{

   public function can_log_in()
   {
	   $this->db->where('username',$this->input->post('username'));
	   $this->db->where('password',md5($this->input->post('password')));

	   $query=$this->db->get('users');



	   if($query->num_rows() == 1)
	   {
		   return true;
	   }
	   else
		   return false;


   }





 public function add_temp_user($key)
    {


    	$data  = array(
		    "firstName" =>$this->input->post("registerFirstName"),
			"username" => $this->input->post("registerUsername"),
            'email' => $this->input->post('registerEmail'),
            'password' => md5($this->input->post('registerPassword')),
            'key' => $key
    		);



    	$query = $this->db->insert('temp_users',$data);


    	if($query)
    	{

    		return true;
    	}
    	else
    	{

    		return false;
    	}
    }


 public function is_key_valid($key)
 {
 	$this->db->where('key',$key);
 	$query = $this->db->get('temp_users');

 	if($query->num_rows() == 1)
 	{
 		return true;
 	}
 	else
        return false;

}

public function add_user($key)
{
	$this->db->where('key',$key);

    $temp_user = $this->db->get('temp_users');

    if($temp_user)
    {
        $row = $temp_user->row();

        $data = array(
              'email' => $row->email,
              'password' => $row->password,
			  "firstName" => $row->firstName,
			  "lastName" => $row->lastName,
			  "username" => $row->username,
			  "phone" => $row->phone

            );

        $added_user = $this->db->insert('users',$data);


        if($added_user)
        {
            $this->db->where('key',$key);
            $this->db->delete('temp_users');
            return $data['email'];
        }
        else
            return false;
    }
}

public function globalSearch(){
  $this->db->where("propertyType",$this->input->post("propertyType"));
  $this->db->where("price",$this->input->post("price"));
  $this->db->where("location",$this->input->post("location"));

   $query = $this->db->get("properties");

   foreach ($query->result() as $row){
     print_r($row);
     echo "<br>";
   }



}


public function contact_user(){
    $data=array(
      'name'=>$this->input->post('name'),
	  'email'=>$this->input->post('email'),
	  'number'=>$this->input->post('number'),
	  'msg'=>$this->input->post('msg'));
    if($this->db->insert('contact',$data)){
	return true;
	}
    else return false;

 }

/***************************************get all locations from the database ***************************************/
 public function get_locations(){
   $query =  $this->db->query("SELECT `city`,`state`,`pin` FROM `locations`");
   $a = array();
    foreach($query->result_array() as $x){
      $a[] = $x['city'].','.$x['state'].','.$x['pin'];
    }

 return $a;
  }


public function user_data($username){
  $data = array();

	$func_num_args = func_num_args();
	$func_get_args = func_get_args();

	if($func_get_args > 1)
	{
		unset($func_get_args[0]);

		$fields = '`'.implode('`,`',$func_get_args).'`';

		$result = $this->db->query("SELECT $fields FROM users WHERE `username` = '$username' ");
		//$data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM users WHERE user_id = $user_id "));
		$data = $result->result_array();
    foreach($data as $x)
    {
       return $x;
    }

	}
	return false;
}


function addNewAgent(){
$user_data = array();

   if($this->session->userdata('username'))
   $user_data =$this->Model_users->user_data($this->session->userdata('username'),'user_id','username','password','firstName','lastName','email','city','state','postalCode','country','provider','type');
   $user_data['logged_in'] = $this->session->userdata('username');

 $data= array(
   'firstName' =>$this->input->post('firstName'),
   'lastName' =>$this->input->post('lastName'),
   'email' =>$this->input->post('email'),
   'phone'=> $this->input->post('phone')
 );
 if($this->db->insert($user_data['username']."_agents",$data)){
 return true;
 }
 else return false;


}

public function addProperty($image){
  $user_data = array();

     if($this->session->userdata('username'))
     $user_data =$this->Model_users->user_data($this->session->userdata('username'),'user_id','username','password','firstName','lastName','email','city','state','postalCode','country','provider','type');
     $user_data['logged_in'] = $this->session->userdata('username');

  $propertyType = strtolower($this->input->post("propertyType"));
  $propertyName = $this->input->post("propertyName");
  $addPropertyLocationId = $this->input->post("addPropertyLocationId");
  $BHK  = $this->input->post("BHK");
  $price  = $this->input->post("price");

  // $data = array(
  //   "city" => $city,
  //   "state" => $state,
  //   "pin" => $pin
  // );
  //
  //
  //
  // if($this->db->insert("locations",$data)){
  //   $locationId;
  //     $query = $this->db->query("SELECT * FROM `locations` ORDER BY locationId DESC LIMIT 1");
  //     foreach($query->result() as $x){
  //          $locationId = $x->locationId;
  //          break;
  //     }

      $data2 = array(
        "name" => $propertyName,
        "price" => $price,
        "BHK" => $BHK,
        "locationId" => $addPropertyLocationId,
        "sellerId" => $user_data['user_id'],
        "image"  => $image
       );

      if($this->db->insert($propertyType,$data2)){
        $id = $propertyType."Id";
        $query = $this->db->query("SELECT * FROM $propertyType ORDER BY $id DESC LIMIT 1");
        foreach($query->result() as $x){
          return $x->$id;
        }
      }else {
        return false;
      }


}

/*****************************retrieving listed properties by a user********************************/
public function listedProperties($user_id){
  $user_id = (int)$user_id;
  $listedApartments = $this->db->query("SELECT apartment.*,locations.* FROM apartment INNER JOIN locations ON apartment.locationId = locations.locationId WHERE sellerId=$user_id");

  if($listedApartments){
    return $listedApartments;
  }
  else {
    return false;
  }
}

/**************************************retrieving listed agents by a user***************************/
public function listedAgents($username){
  $agentTable = $username."_agents";
  $listedAgents = $this->db->query("SELECT * FROM $agentTable");

  if($listedAgents){
    return $listedAgents;
  }
  else{
    return false;
  }
}

public function locationsQuery(){
  $locationsQuery = $this->db->query("SELECT * FROM locations");
  return $locationsQuery;
}


public function requestCallback($propertyType,$id,$user_data){



  if(empty($user_data['logged_in']) === false){
    $data = array(
      "buyerName" => $user_data['firstName'],
      "buyerEmail" => $user_data['email'],
      "buyerPhone" => $user_data['phone'],
      "propertyType" => $propertyType,
      "propertyId" => $id
    );

    if($this->db->insert("callbackRequests",$data)){
      return true;
    }else{
      return false;
    }
  }else{
    $data = array(
      "buyerName" => $this->input->post("buyerName"),
      "buyerEmail" =>$this->input->post("buyerEmail"),
      "buyerPhone" => $this->input->post("buyerPhone"),
      "propertyType" => $propertyType,
      "propertyId" => $id
    );

    if($this->db->insert("callbackRequests",$data)){

    }else{
      return false;
    }
  }
}




public function callbackRequests($user_id){
   $query = $this->db->query("SELECT * FROM callbackRequests,apartment WHERE callbackRequests.propertyId = apartment.apartmentId AND apartment.sellerId=$user_id");
   return $query;

}

public function allCallbackRequests(){
  $query = $this->db->query("SELECT * FROM callbackRequests,apartment WHERE callbackRequests.propertyId=apartment.apartmentId");
  return $query;
}


public function agentEmail($propertyType,$id){
  $propertyId = $propertyType."Id";
  $email = "";
  $sellerId = 0;
  $query = $this->db->query("SELECT sellerId FROM $propertyType WHERE $propertyId = $id LIMIT 1");
  foreach($query->result() as $x){
    $sellerId = $x->sellerId;
    break;
  }


  $query  = $this->db->query("SELECT * FROM users WHERE user_id=$sellerId");

  foreach($query->result() as $x){
    $email = $x->email;
    break;
  }

  return $email;

}


function adminEmail(){
  $query = $this->db->query("SELECT email FROM users WHERE type=2 LIMIT 1");

  $email = "";

  foreach($query->result() as $x){
    $email = $x->email;
    break;
  }
echo $email;

  return $email;
}


public function recentProperties(){
    $query   = $this->db->query("SELECT * FROM apartment,users,locations WHERE apartment.sellerId = users.user_id AND apartment.locationId = locations.locationId");
    return $query;
}


 }

?>
