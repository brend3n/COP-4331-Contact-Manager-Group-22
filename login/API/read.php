<?php

  $inData = getRequestInfo();

  $id = 0;
  $firstName = "";
  $lastName = "";
  $email = "";
  $number = "";

  $conn = new myspli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establishing connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    $sql = "SELECT FK_UserID,FirstName,LastName,Email,PhoneNumber FROM Contacts where FirstName='" . $inData["firstName"] . "' and LastName='" . $inData["lastName"] . "'Email='" . $inData["email"] . "'PhoneNumber='" . $inData["number"] . "'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $firstName = $row["FirstName"];
      $lastName = $row["LastName"];
      $email = $row["Email"];
      $number = $row["PhoneNumber"];
      $id = $row["FK_UserID"];

      returnWithInfo($firstName, $lastName, $id, $email, $number);
    }

    else {
      returnWithError( "No Records Found");
    }

    $conn->close();
  }

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

  function returnWithInfo( $firstName, $lastName, $id, $email, $number) {
    $retValue = '{"FK_UserID":' . $id . ',"FirstName":"' . $firstName . '","LastName":"' . $lastName .  '","Email":"' . $email . '","PhoneNumber":"' . $number . '","error":""}';
    sendResultInfoAsJson( $retValue );
  }

?>
