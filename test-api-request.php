<?php

$url = 'http://localhost:8000/v1/employee-registration';

$data = [
    'registrationCode' => 'ACME',
    'firstName' => 'Test',
    'lastName' => 'User',
    'email' => 'test' . time() . '@example.com', // Unique email
    'role' => 'Software Engineer',
    'highestQualification' => 'Bachelor Degree',
    'desiredSalary' => 75000,
    'note' => 'Testing the registration API'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

echo "Sending POST request to: $url\n";
echo "Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response:\n";
echo json_encode(json_decode($response), JSON_PRETTY_PRINT) . "\n";

curl_close($ch);
