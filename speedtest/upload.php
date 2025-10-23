<?php
// Speed Test Upload Endpoint for Terexo Labs
// This file accepts POST requests and measures upload speed

// Set proper headers for CORS and JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token');
header('Access-Control-Max-Age: 86400');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed. Only POST requests are accepted.',
        'timestamp' => time()
    ]);
    exit();
}

try {
    // Get the raw POST data
    $input = file_get_contents('php://input');
    $receivedBytes = strlen($input);
    
    // Get request headers for additional info
    $contentLength = isset($_SERVER['CONTENT_LENGTH']) ? (int)$_SERVER['CONTENT_LENGTH'] : 0;
    $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
    
    // Log the upload (optional - for debugging)
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'received_bytes' => $receivedBytes,
        'content_length' => $contentLength,
        'user_agent' => $userAgent,
        'remote_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    
    // Optional: Log to file (uncomment if you want logging)
    // file_put_contents('speedtest.log', json_encode($logEntry) . "\n", FILE_APPEND);
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'received_bytes' => $receivedBytes,
        'content_length' => $contentLength,
        'timestamp' => time(),
        'server' => 'Terexo Labs Speed Test Server',
        'version' => '1.0'
    ]);
    
} catch (Exception $e) {
    // Handle any errors
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error occurred',
        'error' => $e->getMessage(),
        'timestamp' => time()
    ]);
}
?>