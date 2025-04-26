<?php
// Set your Gmail credentials
$smtpServer = "smtp.gmail.com";
$port = 587;
$username = "redpostworks@gmail.com";
$password = "tgpd doir vbjh dblj"; // Use the App Password you generated

echo "Testing SMTP connection to $smtpServer:$port\n";

// Create socket connection
$socket = fsockopen($smtpServer, $port, $errno, $errstr, 30);
if (!$socket) {
    echo "Error: $errstr ($errno)\n";
    exit(1);
}

// Read server greeting
$response = fgets($socket, 515);
echo "Server: $response";

// Send EHLO command
fputs($socket, "EHLO localhost\r\n");
do {
    $response = fgets($socket, 515);
    echo "Server: $response";
} while (substr($response, 3, 1) != ' ');

// Start TLS for secure connection
fputs($socket, "STARTTLS\r\n");
$response = fgets($socket, 515);
echo "Server: $response";

// Enable encryption
stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

// Send EHLO again after TLS
fputs($socket, "EHLO localhost\r\n");
do {
    $response = fgets($socket, 515);
    echo "Server: $response";
} while (substr($response, 3, 1) != ' ');

// Authenticate
fputs($socket, "AUTH LOGIN\r\n");
$response = fgets($socket, 515);
echo "Server: $response";

fputs($socket, base64_encode($username) . "\r\n");
$response = fgets($socket, 515);
echo "Server: $response";

fputs($socket, base64_encode($password) . "\r\n");
$response = fgets($socket, 515);
echo "Server: $response";

// Check authentication result
if (substr($response, 0, 3) == '235') {
    echo "Authentication successful!\n";
} else {
    echo "Authentication failed.\n";
}

// Close connection
fputs($socket, "QUIT\r\n");
fclose($socket);

echo "SMTP test completed.\n";