<?php

  $inData = getRequestInfo();

  $id = $inData["id"];
  $search = $inData["search"];

  $conn = new mysqli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

  //establishing connection
  if($conn->connect_error) {
    returnWithError($conn->connect_error);
  }

  else {
    if(strlen($search) == 0)
        returnWithError(" Search string required ");
    
    $sql = "SELECT * FROM `Contacts` WHERE FK_UserID=" . $id . " AND (FirstName LIKE '%" . $search . "%' OR LastName LIKE '%" . $search . "%' OR Email LIKE '%" . $search . "%' OR PhoneNumber LIKE '%" . $search . "%')";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
      //number of search results
      $numResults = $result->num_rows;

      $results = array();

      while($row = $result->fetch_assoc()) {
        $results[] = array(
          "firstName" => $row["FirstName"],
          "lastName" => $row["LastName"],
          "email" => $row["Email"],
          "number" => $row["PhoneNumber"]
        );
      }

      //return json_encode($results);
      returnWithInfo($results);
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

  function returnWithInfo( $results) {
    $retValue = '{"results":' . json_encode($results) . ',"error":""}';
    sendResultInfoAsJson( $retValue );
  }

?>
