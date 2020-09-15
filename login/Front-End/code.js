var urlBase = 'https://getcontactsmatrixstyle.com/LAMPAPI';
var extension = 'php';

var userId = 0;
var firstName = "";
var lastName = "";

function login()
{
	userId = 0;
	firstName = "";
	lastName = "";
	
	var login = document.getElementById("UsernameField").value;
	var password = document.getElementById("passwordField").value;
	var hash = md5(password);
	
	document.getElementById("loginResult").innerHTML = "";
	
	var jsonPayload = '{"login" : "' + login + '", "password" : "' + hash + '"}';
	var url = urlBase + '/login.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);
		
		var jsonObject = JSON.parse(xhr.responseText);
		userId = jsonObject.UserID;
		
		if(userId < 1)
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;
		
		saveCookie();
		
		window.location.href = "login.html";
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
}

function saveCookie()
{
	var minutes = 20;
	var date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
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
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		document.getElementById("UsernameField").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

//ADDING THING CONTACTS
function addContact()
{
	var newContact = document.getElementById("newContact").value;
	document.getElementById("contactAddResult").innerHTML = "";
	
	var jsonpayload = '{"contact" : "' + newContact + '", "userId" : ' + userId + '}';
	var url = urlBase + '/register.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if(this.readyState == 4 && this.status == 200)
			{
				document.getElementById("contactAddResult").innerHTML = "Contact has been added";
			}
		};
		xhr.send(jsonpayload);
	}
	catch(err)
	{
		document.getElementById("contactAddResult").innerHTML = err.message;
	}
}