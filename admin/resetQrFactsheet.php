<?php
require 'main.php';

header('Content-Type: application/json');

function safeDeleteMediaFile(string $dbPath, string $mediaBaseDir): array {
    // Normalize
    $p = str_replace('\\', '/', trim($dbPath));

    if ($p === '') {
        return ['ok' => true, 'deleted' => false, 'reason' => 'Empty path'];
    }

    // Remove any leading slash
    $p = ltrim($p, '/'); // "/media/.." -> "media/.."

    // Accept:
    // - media/...
    // - qrcodes/... (no media prefix)
    // - factsheets/...
    // - qrcards/...
    if (strpos($p, 'media/') === 0) {
        $subPath = substr($p, strlen('media/')); // "qrcodes/..."
    } else {
        $subPath = $p; // already relative to media folder
    }

    // Basic sanity: prevent obvious traversal before realpath checks
    if (str_contains($subPath, '../') || str_contains($subPath, '..\\')) {
        return ['ok' => false, 'deleted' => false, 'reason' => 'Path traversal detected', 'path' => $dbPath];
    }

    // Build absolute path under /media
    $abs = rtrim($mediaBaseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
         . str_replace('/', DIRECTORY_SEPARATOR, $subPath);

    $realBase = realpath($mediaBaseDir);
    if ($realBase === false) {
        return ['ok' => false, 'deleted' => false, 'reason' => 'Media base dir missing', 'path' => $dbPath];
    }

    if (!file_exists($abs)) {
        return ['ok' => true, 'deleted' => false, 'reason' => 'File not found', 'path' => $dbPath];
    }

    $realFile = realpath($abs);
    if ($realFile === false) {
        return ['ok' => false, 'deleted' => false, 'reason' => 'realpath failed', 'path' => $dbPath];
    }

    // Ensure file truly lives inside /media
    // Add separator to avoid prefix-tricks like C:\mediaX matching C:\media
    $basePrefix = rtrim($realBase, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    if (strpos($realFile, $basePrefix) !== 0) {
        return ['ok' => false, 'deleted' => false, 'reason' => 'Outside media base dir', 'path' => $dbPath];
    }

    if (!is_file($realFile)) {
        return ['ok' => false, 'deleted' => false, 'reason' => 'Not a file', 'path' => $dbPath];
    }

    if (!@unlink($realFile)) {
        return ['ok' => false, 'deleted' => false, 'reason' => 'unlink failed (permissions?)', 'path' => $dbPath];
    }

    return ['ok' => true, 'deleted' => true, 'path' => $dbPath];
}

try {
    if (!isset($_POST['media_id'])) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Missing media_id']);
        exit;
    }

    $mediaId = (int) $_POST['media_id'];
    if ($mediaId <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Invalid media_id']);
        exit;
    }

    // Base dir: project_root/media
    $projectRoot = realpath(__DIR__ . '/../'); // if this file is in /admin
    if ($projectRoot === false) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Could not resolve project root']);
        exit;
    }

    $mediaBaseDir = $projectRoot . DIRECTORY_SEPARATOR . 'media';

    // Fetch existing URLs first
    $stmt = $pdo->prepare('SELECT factsheet_url, qr_card_url, qr_url FROM media WHERE id = ? LIMIT 1');
    $stmt->execute([$mediaId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Media item not found', 'media_id' => $mediaId]);
        exit;
    }

    $deleteResults = [];

    $deleteResults['factsheet'] = !empty($row['factsheet_url'])
        ? safeDeleteMediaFile($row['factsheet_url'], $mediaBaseDir)
        : ['ok' => true, 'deleted' => false, 'reason' => 'No factsheet_url set'];

    $deleteResults['qr_card'] = !empty($row['qr_card_url'])
        ? safeDeleteMediaFile($row['qr_card_url'], $mediaBaseDir)
        : ['ok' => true, 'deleted' => false, 'reason' => 'No qr_card_url set'];

    $deleteResults['qr_code'] = !empty($row['qr_url'])
        ? safeDeleteMediaFile($row['qr_url'], $mediaBaseDir)
        : ['ok' => true, 'deleted' => false, 'reason' => 'No qr_url set'];

    // Now reset DB fields
    $stmt = $pdo->prepare('UPDATE media SET factsheet_url = NULL, qr_card_url = NULL, qr_url = NULL WHERE id = ?');
    $stmt->execute([$mediaId]);

    echo json_encode([
        'ok' => true,
        'media_id' => $mediaId,
        'deleted' => $deleteResults,
        'message' => 'QR code and factsheet reset (DB cleared; files deletion attempted).',
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Server error',
        'details' => $e->getMessage(),
    ]);
}
