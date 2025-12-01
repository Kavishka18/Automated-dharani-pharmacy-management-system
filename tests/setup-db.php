<?php
// tests/setup-db.php  ← FINAL VERSION

echo "Forcing TCP connection for PHPUnit (Mac+XAMPP fix)...\n";

global $con;                                      // ← THIS LINE IS CRUCIAL
$con = mysqli_connect("127.0.0.1:3306", "root", "", "pmsdb");

if (!$con || mysqli_connect_errno()) {
    die("FATAL: Cannot connect to MySQL on 127.0.0.1:3306\nError: " . mysqli_connect_error() . "\n");
}

echo "SUCCESS: Connected to pmsdb via TCP!\n\n";