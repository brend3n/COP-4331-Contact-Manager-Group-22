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
		userId = jsonObject.id;
		
		if(userId < 1)
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		firstName = jsonObject.FirstName;
		lastName = jsonObject.LastName;
		
		saveCookie();
		
		window.location.href = "/html/newLogin.html";
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

//CREATING NEW USER
function addContact()
{
	//userId = 0;
	document.getElementById("addContacts").innerHTML = "";
	
	//info gathering
	var firstName = document.getElementById("firstName").value;
	var lastName = document.getElementById("LastName").value;
	var email = document.getElementById("email").value;
	var number = document.getElementById("phone").value;
	var id = document.getElementById("id").value;
	
	var jsonPayload = JSON.stringify({firstName : firstName, lastName : lastName, email : email, number : number, userId : id});
	var url = urlBase + '/create.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json");
	
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200) 
		{
			var jsonObject = JSON.parse(xhr.responseText);
			if(!jsonObject.err)
			{
				//checking to see if it worked
				firstName = jsonObject.firstName;
				lastName = jsonObject.lastName;
				email = jsonObject.email;
				number = jsonObject.number;
				id = jsonObject.id;
				echo = jsonObject.echo;
				
				//clearing the add contact fields 
				document.getElementById("firstName").value = "";
				document.getElementById("LastName").value = "";
				document.getElementById("email").value = "";
				document.getElementById("phone").value = "";
				document.getElementById("id").value = "";
			}
		}
		};
		xhr.send(jsonPayload);
		
	}	
	catch(err)
	{
		document.getElementById("addContacts").innerHTML = err.message;
	}
	document.getElementById("addContacts").innerHTML = "contact added!";

}

//UPDATE CONTACT
function update()
{
	
}

//REGISTERATION
function register()
{
	userId = 0;
	document.getElementById("register").innerHTML = "";
	
	//info gathering
	var firstName = document.getElementById("firstName").value;
	var lastName = document.getElementById("lastName").value;
	var userName = document.getElementById("userName").value;
	var password = document.getElementById("password").value;
	var email = document.getElementById("email").value;
	//var hash = md5(password);

	var jsonPayload = JSON.stringify({firstName : firstName, lastName : lastName, username : userName, password : password, email : email});
	var url = urlBase + '/register.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json");
	
	try
{
	xhr.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			var jsonObject = JSON.parse(xhr.responseText);
			window.firstName = firstName;
			window.lastName = lastName;
			saveCookie();
			window.location.href = "/";
		}
	};
	xhr.send(jsonPayload);
}
catch(err)
{
	document.getElementById("register").innerHTML = err.message;
}
//document.getElementById("register").innerHTML = echo.message;
}

// DELETE 
function deleteContact()
{
	userId = 0;
	document.getElementById("deleteContact").innerHTML = "";
	
	//getting the info for php
	var firstName = document.getElementById("Fname").value;
	var lastName = document.getElementById("Lname").value;
	var email = document.getElementById("email").value;
	var number = document.getElementById("phone").value;
	var id = document.getElementById("id").value;

	var jsonPayload = JSON.stringify({firstName : firstName, lastName : lastName, email : email, number : number, userId : id});
	var url = urlBase + '/delete.' + extension;
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json");
	
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var jsonObject = JSON.parse(xhr.responseText);
				
			}
			
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("deleteContact").innerHTML = err.message;
	}
}


