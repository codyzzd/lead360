<?php
//montar e mandar email
include 'config.php';
$apiKey = API_KEY;

$data = array(
  "to" => array(
    array(
      "email" => "codyzzd@gmail.com",
      "name" => "Bruno Gonçalves"
    )
  ),
  "templateId" => 2,
  "params" => array(
    "PART" => "ParticipanteX",
    "LIDER" => "LiderY",
    "LINK" => "LinksW"
  ),
  "headers" => array(
    "charset" => "iso-8859-1"
  )
);

$json_data = json_encode($data);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.brevo.com/v3/smtp/email');
curl_setopt(
  $ch,
  CURLOPT_HTTPHEADER,
  array(
    'accept: application/json',
    'api-key: ' . $apiKey,
    'content-type: application/json',
  )
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Error: ' . curl_error($ch);
} else {
  echo $response;
}

curl_close($ch);
?>