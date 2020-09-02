<?php

  $inData = getRequestInfo();

  $firstName = $inData["firstName"];
  $lastName = $inData["lastName"];
  $username = $inData["username"];
  $password = $inData["password"];

  //MUST SORT OUT ISSUE WITH CONNECTING TO DB
  //ERROR: 403
  //$conn = new myspli("localhost", "smallpro_aden", "smallproCOP4331", "smallpro_cop4331");

  //establishing connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    $sql = "insert into Users (FirstName, LastName, Login, Password) VALUES ('" . $firstName . "','" . $lastName . "','" . $username . "','" . $password . "')";

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
