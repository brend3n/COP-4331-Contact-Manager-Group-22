<?php

  $inData = getRequestInfo();

  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $username = $inData["username"];
  $password = $inData["password"];
  $email = $inData["email"];

  $conn = new mysqli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establishing connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    if(strlen($firstName) == 0 || strlen($lastName) == 0 || strlen($username) == 0 || strlen($password) == 0 || strlen($email) == 0)
        returnWithError(" All fields required ");
      
    //hash password
    $hashedPass = md5($password);
    
    //check if user exists
    $sql = "SELECT FirstName,LastName,Email,Login,Password FROM Users where FirstName='" . $inData["firstName"] . "' and LastName='" . $inData["lastName"] . "' and Email='" . $inData["email"] . "' and Login='" . $inData["username"] . "' and Password='" . $hashedPass . "'";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        returnWithError(" User already exists");
    }
    
    else {
        //check if email is used
        $sql = "SELECT Email FROM Users where Email='" . $email . "'";
        $result = $conn->query($sql);
    
        if($result->num_rows > 0) {
            returnWithError(" Email already in use ");
        }
    
        //check if username is already used
        $sql = "SELECT Username FROM Users where Username='" . $username . "'";
        $result = $conn->query($sql);
    
        if($result->num_rows > 0) {
            returnWithError(" Username already in use ");
        }
        
        $sql = "INSERT INTO `Users`(`FirstName`, `LastName`, `Email`, `Login`, `Password`) VALUES ('" . $firstName . "','" . $lastName . "','" . $email . "','" . $username . "','" . $hashedPass . "')";
        //$result = $conn->query($sql);
    
        if($result = $conn->query($sql) != TRUE) {
            //echo $hashedPass;
            returnWithError($conn->error);
        }
        
       else {
          returnWithInfo($firstName, $lastName, $email);
        }
    }

    $conn->close();
  }

  //returns with no error
  //returnWithError("");

  //aux functs
  function getRequestInfo() {
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj ) {
		header('Content-type: application/json');
		echo $obj;
	}

  function returnWithError( $err ) {
		$retValue = '{"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

  function returnWithInfo( $firstName, $lastName, $email )
	{
		$retValue = '{"email":"' . $email . '","firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>
