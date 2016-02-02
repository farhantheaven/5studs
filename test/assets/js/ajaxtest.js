 var xmlhttp=loadxmldoc();
  function loadxmldoc()
	{
	var xmlhttp;
	if(window.ActiveXObject)
	{
	try
	{
	xmlhttp=ActiveXObject("Microsoft.XMLHTTP");
	}catch(Exception)
	{
	xmlhttp=false;
	}
	}else
	{
	try
	{
	xmlhttp=new XMLHttpRequest();
	}catch(Exception)
	{
	xmlhttp=false;
	}
	}
	if(!xmlhttp)
	alert("there is no connection");
	else
	return xmlhttp;
	}
	
	
	
	function process()
	{
	if(xmlhttp.readyState==4||xmlhttp.readyState==0)
	{
	cities=encodeURIComponent(document.getElementById("search1").value);
	xmlhttp.open("GET","../includes/gethint.php?cities="+cities,true);
	alert('hiiii');
	xmlhttp.onreadystatechange=handleserverresponse;
    xmlhttp.send();	
	}
	else
	{
	setTimeout('process()',100);
	}
	}
	
	
	
	function handleserverresponse()
	{	
	if(xmlhttp.readyState==4)
	{
	if(xmlhttp.status==200`)
	{
	message=xmlhttp.responseText;
	document.getElementById("search2").innerHTML='<span style="color:blue">'+message+'</span>';
	setTimeout  ('process()',1000);
	}
	else
	{
	alert('something went wrong');
	}
	}
	}