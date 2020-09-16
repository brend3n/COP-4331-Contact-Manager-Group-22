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
    //hash password
    $hashedPass = md5($password);

    $sql = "INSERT INTO `Users`(`FirstName`, `LastName`, `Email`, `Login`, `Password`) VALUES ('" . $firstName . "','" . $lastName . "','" . $email . "','" . $username . "','" . $hashedPass . "')";
    //$result = $conn->query($sql);

    if($result = $conn->query($sql) != TRUE) {
        //echo $hashedPass;
        returnWithError($conn->error);
    }
    
    else {
      echo "User successfully created";
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
		$retValue = '{"email":' . $email . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>
