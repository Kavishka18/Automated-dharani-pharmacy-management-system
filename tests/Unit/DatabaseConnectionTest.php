<?php
use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    public function testDatabaseConnectionEstablished()
    {
        global $con;
        $this->assertNotNull($con, "Database connection should not be null");
        $this->assertTrue(mysqli_ping($con), "MySQL connection should be alive");
    }
}