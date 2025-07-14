<?php
$ch = curl_init("https://test-payment.momo.vn/v2/gateway/api/create");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"test":"test"}');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$result = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);
echo "RESULT: " . $result . "<br>";
echo "ERROR: " . $err;
