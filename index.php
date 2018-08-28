<?php

//Primera parte 

	$servername = 'sql9.freemysqlhosting.net';
	$username = 'sql9254111';
	$password = 'qDix3M1yLl';
	$dbname = 'sql9254111';

	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: ".$conn->connect_error);
	} 
//Segunda Parte 

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST')
{
	$requestBody = file_get_contents('php://input'); 
	$json = json_decode($requestBody); 
	$data = $json->result->parameters->number;

	$number = $data;

	$sql = "SELECT * FROM Bimbonice WHERE sr_number = '".$number."';";
	$result = $conn->query($sql);
	$sr = array();

	
		while($row = mysqli_fetch_array($result)) 
		{ 
		    $id=$row['id'];
		    $srProblemSummary=$row['problema_summary'];
		    $srSRNumber=$row['sr_number'];
		    $srContact=$row['Contact'];
		    $srSeverity=$row['severity'];
		    $srStatus=$row['status'];	  
		    $srLastUpdate=$row['last_update'];  
		    $srProduct=$row['product'];

		    $sr[] = array( 'sr_number'=> $srSRNumber, 'problema_summary' => $srProblemSummary, 'Contact'=> $srContact, 'severity'=> $srSeverity, 'status'=> $srStatus, 'last_update'=> $srLastUpdate, 'product'=> $srProduct);		   
		}

		$speech = $sr[0]['sr_number'].",".$sr[0]['problema_summary'].",".$sr[0]['Contact'].",".$sr[0]['severity'].",".$sr[0]['status'].",".$sr[0]['last_update'].",".$sr[0]['product'];

		$requestBody = file_get_contents('php://input'); 
		$json = json_decode($requestBody);
		$data = $json->result->metadata->intentName;
		$intent = $data;

		if($number != "" && $intent == "SRNumberGeneral")
		{
			$speech = $sr[0]['sr_number'].",".$sr[0]['problema_summary'].",".$sr[0]['Contact'].",".$sr[0]['severity'].",".$sr[0]['status'].",".$sr[0]['last_update'].",".$sr[0]['product'];
		}	
		if($number != "" && $intent == "SRNumberProblemSummary")
		{
				$speech = $sr[0]['sr_number'].",".$sr[0]['problema_summary'];
		}
		if($number != "" && $intent == "SRNumberStatus")
		{
				$speech = $sr[0]['sr_number'].",".$sr[0]['status'];
		}	
		if($number != "" && $intent == "SRNumberSeverity")
		{
				$speech = $sr[0]['sr_number'].",".$sr[0]['severity'];
		}
		if($number != "" && $intent == "SRNumberLastUpdate")
		{
				$speech = $sr[0]['sr_number'].",".$sr[0]['last_update'];
		}	
		if($number != "" && $intent == "SRNumberProduct")
		{
			$speech = $sr[0]['sr_number'].",".$sr[0]['product'];
		}
		if(strlen ($number) < 11)
		{
			$speech = "El numero de sr no encontrado intenta de nuevo"
		}

		$response = new \stdClass();
		$response->speech = $speech;
		$response->displayText = $speech;
		$response->source = "webhook";
		echo json_encode($response);
	
	
}
else
{
	echo "Method not allowed";
}

	$conn->close();
?> 

