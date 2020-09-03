<?php

inData = getRequestInfo();

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
$loopArray = array("FirstName" => 0, "LastName" => 0, "Email" => 0, "PhoneNumber" => 0);

$conn = new myspli("localhost", "smallpro_cop4331", "Popgame1!", "smallpro_cop4331");

//establishing connection
if($conn->connect_error) {
  returnWithError($conn->connect_error);
}

else {
  $sql = "SELECT FK_UserID FROM Contacts where FirstName='" . $inData["firstName"] . "' and LastName='" . $inData["lastName"] . "' and Email='" . $inData["email"] . "'and PhoneNumber='" . $inData["number"] . "'";
  $result = $conn->query($sql);

  //use ID lookup
  if($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    //set id
    $id = $row["FK_UserID"];


      //new sql command
      $sql = "UPDATE Contacts SET"
  }

  else {
    returnWithError( "No Records Found");
  }
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
