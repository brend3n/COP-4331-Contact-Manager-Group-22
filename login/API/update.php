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

  $conn = new myspli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establishing connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    $sql = "SELECT FK_UserID FROM Contacts where FirstName='" . $inData["firstName"] . "' and LastName='" . $inData["lastName"] . "' and Email='" . $inData["email"] . "'and PhoneNumber='" . $inData["number"] . "'";
    $result = $conn->query($sql);

    //use ID, first, last, and email lookup
    if($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      //send sql queries
      sendUpdates($array, $row);
    }

    else {
      returnWithError( "No Records Found");
    }
  }

  //aux functs
  function sendUpdates($array, $row) {
    //set id, firstname, lastname, and email
    $id = $row["FK_UserID"];
    $firstName = $row["FirstName"];
    $lastName = $row["LastName"];
    $email = $row["Email"];

    //loop through the array for new strings
    for($i = 0; $i < 4; $i++) {
      //if the string is new
      if(strlen($array[$i]) > 0) {
        //firstname
        if($i == 0) {
          $sql = "UPDATE Contacts SET FirstName='" . $array[$i] . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName "' and Email='" . $email . "'";
          $result = $conn->query($sql);

          //update firstName
          $firstName = $array[$i];
        }

        //lastname
        else if($i == 1) {
          $sql = "UPDATE Contacts SET LastName='" . $array[$i] . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName "' and Email='" . $email . "'";
          $result = $conn->query($sql);

          //update lastName
          $lastName = $array[$i];
        }

        //email
        else if($i == 2) {
          $sql = "UPDATE Contacts SET Email='" . $array[$i] . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName "' and Email='" . $email . "'";
          $result = $conn->query($sql);

          //update lastName
          $email = $array[$i];
        }

        //number
        else {
          $sql = "UPDATE Contacts SET PhoneNumber='" . $array[$i] . "' WHERE FirstName='" . $firstName . "' and LastName='" . $lastName "' and Email='" . $email . "'";
          $result = $conn->query($sql);

          //update lastName
          $lastName = $array[$i];
        }
      }
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
    $retValue = '{"FK_UserID":' . $id . ',"FirstName":"' . $firstName . '","LastName":"' . $lastName .  '","Email":"' . $email . '","PhoneNumber":"' . $number . '","error":""}';
    sendResultInfoAsJson( $retValue );
  }

?>
