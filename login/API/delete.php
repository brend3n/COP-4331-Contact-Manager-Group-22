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
    if(strlen($firstName) == 0 || strlen($lastName) == 0 || strlen($email) == 0 || strlen($number) == 0)
        returnWithError(" All fields must be filled in ");
    
    $sql = "DELETE FROM Contacts WHERE FirstName='" . $firstName . "' and LastName='" . $lastName . "' and Email='" . $email . "' and PhoneNumber='" . $number . "' and FK_UserID='" . $id . "'";
    //$result = $conn->query($sql);

    if($result = $conn->query($sql) != TRUE) {
      returnWithError($conn->error);
    }
    
    //echo "Contact successfully deleted";
    //making a change by Jainav Patel
    returnWithInfo($firstName,$lastName,$id,$email,$number);

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
