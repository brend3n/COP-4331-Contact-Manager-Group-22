// By Jainav Patel

// Define the url as well as the extension 
	var urlBase = 'https://getcontactsmatrixstyle.com/LAMPAPI';
	var extension = 'php';

// Define the variables needed for login
	var userId = 0;
	var firstName = "";
	var lastName= "";
	
// Function that handles login 

function login()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	//login and password values below
	var login = document.getElementById("UsernameField").value;
	var password = document.getElementById("passwordField").value;
	
	//hasing the password
	//var hash = md5(password);
	
	document.getElementById("loginResult").innerHTML = "";
	
	//let's setup json
	var jsonPayload = '{"login" : "' + login + '", "password : "' + password + '"}';
	var url = urlBase + '/login.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	
	try
	{
		xhr.send(jsonPayload);
		
		var jsonObject = JSON.parse(xhr.responseText);
		userId = jsonObject.id;
		
		if( userId < 1)
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		
		//get first and last names from json file
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;
		
		saveCookie();
		
		window.location.href = "loggedin.html";
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}
	
// Cookie function below! Please keep as it is for now! 

function saveCookie()
{
	var minutes = 20;
	var date = new Date();
	date.setTime(date.getTime() + (minutes*60*100));
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName +",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	var data = document.cookie;
	var splits = data.split(",");
	for(var i = 0; i < splits.length; i++)
	{
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		if(tokens[0] == "firstName")
		{
			firstName = tokens[1];
		}
		else if(tokens[0] == "lastName")
		{
			lastName = tokens[1];
		}
		else if(tokens[0] == "userId")
		{
			userId = parseInt(tokens[1].trim());
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}


	
