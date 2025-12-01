<?php
// tests/bootstrap.php  ← FINAL VERSION (3 lines only)
require_once __DIR__ . '/setup-db.php';   // ← this does everything
global $con;                              // make $con available to tests
ob_start();