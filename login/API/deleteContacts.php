<?php
  $inData = getRequestInfo();

  $id = $inData["id"];

  $conn = new mysqli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establishing connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    $sql = "SELECT * FROM `Contacts` WHERE FK_UserID='" . $id . "'";
    $result = $conn->query($sql);

    //contacts associated with user, delete all
    if($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $sql = "DELETE FROM `Contacts` WHERE FirstName='" . $row["FirstName"] . "' and LastName='" . $row["LastName"] . "' and Email='" . $row["Email"] . "' and PhoneNumber='" . $row["PhoneNumber"] . "' and FK_UserID='" . $id . "'";
        $result = $conn->query($sql);
      }

        returnWithInfo($id);
    }

    //no contacts associated with user, do nothing
    else
      returnWithError(" No associated contacts ");

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
    $retValue = '{"UserID":' . $id . '","error":""}';
    sendResultInfoAsJson( $retValue );
  }

 ?>
