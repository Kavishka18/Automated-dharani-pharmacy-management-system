<?php
header('Content-Type: application/json');
session_start();
include('includes/dbconnection.php');

// Load trained Q&A
$qa = json_decode(file_get_contents('questions.json'), true);

$user_message = strtolower(trim($_POST['message'] ?? ''));
$reply = $qa['default'];

$medicine_found = false;

// 1. Check for medicine name
$words = preg_split('/\s+/', $user_message);
foreach ($words as $word) {
    if (strlen($word) < 3) continue;
    
    $word = mysqli_real_escape_string($con, $word);
    $sql = "SELECT MedicineName, Quantity FROM tblmedicine WHERE LOWER(MedicineName) LIKE '%$word%' AND Quantity > 0 LIMIT 1";
    $result = mysqli_query($con, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $med = htmlspecialchars($row['MedicineName']);
        $qty = $row['Quantity'];
        $reply = str_replace(['{medicine}', '{qty}'], [$med, $qty], $qa['yes_medicine']);
        $medicine_found = true;
        break;
    }
}

if (!$medicine_found) {
    // 2. Match trained questions
    if (preg_match('/\b(open|hour|time|when)\b/', $user_message)) {
        $reply = $qa['opening_hours'];
    }
    elseif (preg_match('/\b(clos| poya |full moon)\b/', $user_message)) {
        $reply = $qa['closed_days'];
    }
    elseif (preg_match('/\b(phone|call|number)\b/', $user_message)) {
        $reply = $qa['phone'];
    }
    elseif (preg_match('/\b(where|address|location)\b/', $user_message)) {
        $reply = $qa['address'];
    }
    elseif (preg_match('/\b(hi|hello|hey)\b/', $user_message)) {
        $reply = $qa['greeting'];
    }
    elseif (preg_match('/\b(thank|thanks)\b/', $user_message)) {
        $reply = $qa['thanks'];
    }
    else {
        $reply = $qa['no_medicine'];
    }
}

echo json_encode(['reply' => $reply]);
?>