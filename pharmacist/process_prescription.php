<?php
header('Content-Type: application/json');
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if (!isset($_FILES['prescription_file'])) {
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['prescription_file'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
    echo json_encode(['error' => 'Only JPG/PNG allowed']);
    exit;
}

$tempPath = $uploadDir . 'ai_' . time() . '.' . $ext;
if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
    echo json_encode(['error' => 'Failed to save file']);
    exit;
}

require_once 'groq_vision_processor.php';
$result = analyze_prescription_image($tempPath);
unlink($tempPath);

echo json_encode($result);
?>