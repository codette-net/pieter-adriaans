<?php
require 'main.php';

if (isset($_POST['imageDataUrl'], $_POST['mediaId'])) {
    $mediaId = (int) $_POST['mediaId'];
    $imageDataUrl = $_POST['imageDataUrl'];

    // Validate data URL (basic)
    if (strpos($imageDataUrl, 'data:image/') !== 0 || strpos($imageDataUrl, ',') === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid imageDataUrl']);
        exit;
    }

    // Extract base64 part and decode
    $base64 = substr($imageDataUrl, strpos($imageDataUrl, ',') + 1);
    $imageData = base64_decode($base64, true);

    if ($imageData === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to decode image data']);
        exit;
    }

    // Ensure directory exists
    $dir = dirname(__DIR__) . '/media/factsheets';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0775, true) && !is_dir($dir)) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create factsheets directory']);
            exit;
        }
    }

    $date = date('Y-m-d');
    $basename = $date . '-' . $mediaId . '-factsheet.png';

    // Filesystem path (where to write) + public path (what to store in DB)
    $filepath = $dir . '/' . $basename;
    $publicPath = 'media/factsheets/' . $basename;

    if (file_put_contents($filepath, $imageData) === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to write file']);
        exit;
    }

    // Update DB
    $stmt = $pdo->prepare('UPDATE media SET factsheet_url = ? WHERE id = ?');
    $stmt->execute([$publicPath, $mediaId]);

    header('Content-Type: application/json');
    echo json_encode([
        'ok' => true,
        'mediaId' => $mediaId,
        'factsheet_url' => $publicPath,
    ]);
    exit;
}

http_response_code(400);
header('Content-Type: application/json');
echo json_encode(['error' => 'Missing imageDataUrl or mediaId']);
