<?php
use PHPUnit\Framework\TestCase;

class ClaimInsertionTest extends TestCase
{
    public function testClaimInsertionIncreasesCount()
    {
        // Get before count
        $before = mysqli_fetch_assoc(mysqli_query($GLOBALS['con'], "SELECT COUNT(*) as total FROM tblclaims"))['total'];

        // Dummy insert (replace with your real query)
        $testClaimNo = 'TEST-UNIT-' . rand(1000,9999);
        $sql = "INSERT INTO tblclaims (ClaimNumber, CustomerID, PolicyID, ClaimAmount, Status, SubmittedAt)
                VALUES ('$testClaimNo', 1, 1, 5000.00, 'PENDING_INSURER', NOW())";
        $insert = mysqli_query($GLOBALS['con'], $sql);
        $this->assertTrue($insert, "Claim insert query failed: " . mysqli_error($GLOBALS['con']));

        // Get after count
        $after = mysqli_fetch_assoc(mysqli_query($GLOBALS['con'], "SELECT COUNT(*) as total FROM tblclaims"))['total'];

        $this->assertEquals($before + 1, $after, "Claim count should increase by 1");

        // Cleanup
        mysqli_query($GLOBALS['con'], "DELETE FROM tblclaims WHERE ClaimNumber = '$testClaimNo'");
    }
}