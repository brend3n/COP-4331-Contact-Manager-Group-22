<?php

  $inData = getRequestInfo();

  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $email = $inData["email"];
  $number = $inData["number"];
  $id = $inData["id"];


  $conn = new mysqli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establish connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    if(strlen($firstName) == 0)
        returnWithError( "First name missing" );
    
    if(strlen($lastName) == 0)
        returnWithError( "Last name missing" );
        
    if(strlen($email) == 0 && strlen($number) == 0)
        returnWithError( " Both contact fields empty "); 
    
    $sql = "INSERT INTO `Contacts`(`FirstName`, `LastName`, `Email`, `PhoneNumber`, `FK_UserID`) VALUES ('" . $firstName . "','" . $lastName . "','" . $email . "','" . $number . "'," . $id . ")";
    //returnWithError($sql);
   // $result = $conn->query($sql);
    
    if($result = $conn->query($sql) != TRUE) {
        returnWithError($conn->error);
    }
    
     //echo "Contact successfully inserted";
    else {
        returnWithInfo($firstName, $lastName, $email, $number);
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

 function returnWithInfo( $firstName, $lastName, $email, $number ) {
		$retValue = '{"firstName":"' . $firstName . '","lastName":"' . $lastName . '","email":"' . $email . '","number":"' . $number . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>
