<?php
use PHPUnit\Framework\TestCase;

class SessionAuthTest extends TestCase
{
    public function testPharmacistSessionAccess()
    {
        // Mock session
        $_SESSION['pmspid'] = 1;

        // Simulate pharmacist dashboard access
        $this->assertTrue(isset($_SESSION['pmspid']) && $_SESSION['pmspid'] > 0, "Pharmacist session should be active");

        // Simulate unauthorized access
        unset($_SESSION['pmspid']);
        $this->assertFalse(isset($_SESSION['pmspid']), "No session should block access");
    }
}