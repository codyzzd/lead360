<?php
// Include the configuration file
include 'config.php';
$apiKey = API_KEY;

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
curl_setopt($ch, CURLOPT_POSTFIELDS, '{
  "sender":{
      "name":"Avaliação LiderScan",
      "email":"avaliacao@liderscan.com.br"
   },
   "to":[
      {
         "email":"codyzzd@gmail.com",
         "name":"Bruno Gonçalves"
      }
   ],
   "subject":"Hello world",
   "htmlContent":"<html><head></head><body><p>Hello,</p>This is my first transactional email sent from Brevo.</p></body></html>"
}');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if (curl_errno($ch)) {
  echo 'Error: ' . curl_error($ch);
} else {
  echo $response;
}

curl_close($ch);

?>