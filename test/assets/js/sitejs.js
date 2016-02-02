

function createAjaxObject(){
  var xmlhttp;
  if(window.ActiveXObject){
    try{
      xmlhttp = new ActiveXObject();
    }catch(e){
      xmlhttp = false;
    }
  }
  else{
    try{
      xmlhttp = new XMLHttpRequest();
    }catch(e){
      xmlhttp = false;
    }
  }

  return xmlhttp;
}

function processForm(){

  document.getElementById('luda').style.display = '';
  var xmlhttp;
  var username = document.getElementById("username").value;
  var password = document.getElementById("password").value;
  xmlhttp = createAjaxObject();
  xmlhttp.onreadystatechange=function(){
  if(xmlhttp.readyState == 4 && xmlhttp.status ==200){
    document.getElementById('luda').style.display='none';
    document.getElementById("loginErrors").innerHTML = xmlhttp.responseText;
	var message = document.getElementById("success").innerHTML;
	if(message === "Logging you in..."){
		window.location = window.location;
	}
  }
}

  xmlhttp.open("POST",base_url+"index.php/Main/loginValidation",true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send("username="+username+"&password="+password);

}

function registerUser(){
  document.getElementById('luda').style.display = '';

  var xmlhttp = createAjaxObject();
  var registerUsername= document.getElementById("register_username").value;
  var registerFirstName= document.getElementById("register_firstName").value;
  var registerPassword= document.getElementById("register_password").value;
  var registerCPassword= document.getElementById("register_cpassword").value;
  var registerEmail= document.getElementById("register_email").value;
  var registerTerms = document.getElementById("register_terms").checked;



  xmlhttp.onreadystatechange = function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
      document.getElementById('luda').style.display='none';
      document.getElementById("registerErrors").innerHTML = xmlhttp.responseText;
    }
  }
  xmlhttp.open("POST",base_url+"index.php/Main/signupValidation",true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send("registerTerms="+registerTerms+"&registerUsername="+registerUsername+"&registerFirstName="+registerFirstName+"&registerPassword="+registerPassword+"&registerCPassword="+registerCPassword+"&registerEmail="+registerEmail);

}


$('#login-form').submit(function(event){
  processForm();
  event.preventDefault();
});
$('#register-form').submit(function(event){
  registerUser();
  event.preventDefault();
});
/*$('#global_search_form').submit(function(event){
  global_search();
  event.preventDefault();
});*/


function showHint(str){
  if(str.length === 0){
    document.getElementById("location_results").innerHTML = "";
    document.getElementById("location_results").style.display ="none";
    return ;
  }
  else {
    var xmlhttp = createAjaxObject();
    xmlhttp.onreadystatechange = function(){
      if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
        document.getElementById("location_results").style.display = '';
        document.getElementById('location_results').innerHTML = xmlhttp.responseText;
      }
    }
    xmlhttp.open('GET',base_url+"index.php/Main/get_location_hint/"+str,true);
    xmlhttp.send();
  }
}


$(document).ready(function(){
  $('.slider5').bxSlider({
    slideWidth: 400,
    minSlides: 2,
    maxSlides: 3,
    moveSlides: 3,
    infiniteLoop: false,
    slideMargin: 10
  });
});


/************************************** global search **********************************************/
function global_search(){
  //var page = document.getElementById("page").innerHTML;
  //var per_page = document.getElementById("per_page").innerHTML;
  var Property = document.getElementById("global_search_property_type").value;
  var Budget =  document.getElementById("global_search_budget").value;
  var bhk = document.getElementById("global_search_bhk").value;
  var global_search_location = document.getElementById("global_search_location").value;


  var xmlhttp = createAjaxObject();
  xmlhttp.onreadystatechange = function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
      if(xmlhttp.responseText == ""){
          alert("No search results found");
      }
      else{
        document.getElementById('dynamicContent').innerHTML = xmlhttp.responseText;
        window.location = "#dynamicContent";
      }
    }
  }
  xmlhttp.open("GET",base_url+"index.php/Main/globalSearch?Property="+Property+"&Budget="+Budget+"&bhk="+bhk+"&global_search_location="+global_search_location+"page=1",true);
  //xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  //xmlhttp.send("Property="+Property+"&Budget="+Budget+"&bhk="+bhk+"&global_search_location="+global_search_location);
  xmlhttp.send();
}



$(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
});

$("#add_agent_form").submit(function(event){
  var url = base_url+"index.php/Main/agent_validation";
  var a = document.getElementById("agentFirstName").value;
  var b = document.getElementById("agentLastName").value;
  var c =  document.getElementById("agentEmail").value;
  var d = document.getElementById("agentPhone").value;
  $.post(url,
  {
    firstName : a,
    lastName : b,
    email :c,
    phone : d
  },
  function(data, status){
    document.getElementById("add_agent_form_errors").innerHTML = data;
  }
);
  event.preventDefault();
});

$("#add_property_form_agent").submit(function(event){
   var url = base_url+"index.php/Main/addProperty";
   
  //  $.post(url,
  //  {
  //    propertyType : propertyType,
  //    propertyName : propertyName,
  //    price : price,
  //    city : city,
  //    state : state,
  //    BHK : BHK,
  //    pin : pin
  //  },
  //  function(data,status){
  //    document.getElementById("add_property_form_agent_errors").innerHTML = data;
  //  }
  //  );
var formData = new FormData($(this)[0]);
  $.ajax(
    {
      url:url,
      type:'POST',
      data : formData,
      async : false,
      success : function(data){
        document.getElementById("add_property_form_agent_errors").innerHTML = data;
      },
      cache : false,
      contentType : false,
      processData : false
    }
  );

event.preventDefault();

});

function requestCallback(propertyType,id){

  var xmlhttp = createAjaxObject();
  var buyerName = document.getElementById("buyerName").value;
  var buyerPhone = document.getElementById("buyerPhone").value;
  var buyerEmail = document.getElementById("buyerEmail").value;

  xmlhttp.onreadystatechange=function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
      if(xmlhttp.responseText == "yes"){
        document.getElementById("requestCallbackErrors").innerHTML = "<h4 style='color:green;'>Thanks for your response...Our Agents will contact you soon :)</h4>";
      }else {
        document.getElementById("requestCallbackErrors").innerHTML = xmlhttp.responseText;
      }
    }
  }

  xmlhttp.open("POST",base_url+"index.php/Main/requestCallback?propertyType="+propertyType+"&id="+id);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send("buyerName="+buyerName+"&buyerPhone="+buyerPhone+"&buyerEmail="+buyerEmail);
}



$("#requestCallbackForm").submit(function(event){
var url = base_url+"index.php/Main/requestCallback";
requestCallback(propertyType,id);
event.preventDefault();

});


















