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
        $sql = "DELETE FROM `Contacts` WHERE `FK_UserID`='" . $id . "'";
        $result = $conn->query($sql);
    }

    //no contacts associated with user, do nothing
    else
      ;

    //all contacts gone now delete user
    $sql = "DELETE FROM `Users` WHERE UserID='" . $id . "'";
    $result = $conn->query($sql);

    returnWithInfo($id);

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

  function returnWithInfo($id) {
    $retValue = '{"UserID":' . $id . ',"error":""}';
    sendResultInfoAsJson( $retValue );
  }

?>
