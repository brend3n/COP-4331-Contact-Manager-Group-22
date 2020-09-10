<?php

  $inData = getRequestInfo();

  //original data in contacts
  $id = 0;
  $firstName = "";
  $lastName = "";
  $email = "";
  $number = "";

  //data to be updated
  $newFirst = $inData["newFirst"];
  $newLast = $inData["newLast"];
  $newEmail = $inData["newEmail"];
  $newNumber = $inData["newNumber"];

  //populate array
  $array = array($newFirst, $newLast, $newEmail, $newNumber);

  $conn = new mysqli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establishing connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    //set user's id
    $id = $inData["userId"];

    //send the updates
    sendUpdates($array, $id);

    returnWithInfo($newFirst, $newLast, $id, $newEmail, $newNumber);

    $conn->close();
  }

  //aux functs
  function sendUpdates($array, $id) {
    //select the data
    $sql = "SELECT FirstName, LastName, Email, PhoneNumber FROM Contacts where FirstName='" . $inData["firstName"] . "' and LastName='" . $inData["lastName"] . "' and Email='" . $inData["email"] . "' and PhoneNumber='" . $inData["number"] . "' and FK_UserID='" . $id . "'";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();

    //setting values
    if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$email = $row["Email"];
      $number = $row["PhoneNumber"];
    }

    else {
      returnWithError( "No Records Found" );
    }

    if($result->num_rows > 0) {
      for($i = 0; $i < 4; $i++) {
        //first name change
        if($i == 0 && strlen($array[$i]) > 0) {
          $sql = "UPDATE Contacts SET FirstName='" . $newFirst . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName . "' and PhoneNumber='" . $number . "' and Email='" . $email . "' and FK_UserID'" . $id . "'";
          $result = $conn->query($sql);

          $firstName = $newFirst;
        }

        //last name change
        else if($i == 1 && strlen($array[$i]) > 0) {
          $sql = "UPDATE Contacts SET LastName='" . $newLast . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName . "' and PhoneNumber='" . $number . "' and Email='" . $email . "' and FK_UserID'" . $id . "'";

          $lastName = $newLast;
        }

        //email change
        else if($i == 2 && strlen($array[$i]) > 0) {
          $sql = "UPDATE Contacts SET Email='" . $newEmail . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName . "' and PhoneNumber='" . $number . "' and Email='" . $email . "' and FK_UserID'" . $id . "'";

          $email = $newEmail;
        }

        //number change
        else if($i == 3 && strlen($array[$i]) > 0) {
          $sql = "UPDATE Contacts SET PhoneNumber='" . $newNumber . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName . "' and PhoneNumber='" . $number . "' and Email='" . $email . "' and FK_UserID'" . $id . "'";

          $number = $newNumber;
        }

        else
          ;
      }
    }

    else {
      returnWithError( "No Records Found" );
    }
  }

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

  function returnWithInfo( $firstName, $lastName, $id, $email, $number) {
    $retValue = 'Successfully updated contact information at: {"FK_UserID":' . $id . ',"FirstName":"' . $firstName . '","LastName":"' . $lastName .  '","Email":"' . $email . '","PhoneNumber":"' . $number . '","error":""}';
    sendResultInfoAsJson( $retValue );
  }

?>
