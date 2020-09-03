<?php

  $inData = getRequestInfo();

  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $email = $inData["email"];
  $number = $inData["number"];

  $conn = new myspli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establish connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    $sql = "insert into Contacts (FirstName, LastName, Email, PhoneNumber) VALUES '" . $firstName . "','" . $lastName . "','" . $email . "','" . $number . "')";

    if($result = $conn->query($sql) != TRUE) {
      returnWithError($conn->error);
    }

    $conn->close();
  }

  //returns with no error
  returnWithError("");

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
?>
