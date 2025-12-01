<?php
use PHPUnit\Framework\TestCase;

class AIPrescriptionResponseTest extends TestCase
{
    public function testValidAIResponseParsing()
    {
        // Mock AI JSON
        $mockJson = '{"drug_names": ["Paracetamol 500mg", "Amoxicillin 250mg"], "patient_name": "John Doe"}';

        $parsed = json_decode($mockJson, true);
        $this->assertIsArray($parsed);
        $this->assertArrayHasKey('drug_names', $parsed);
        $this->assertCount(2, $parsed['drug_names'], "Should detect 2 medicines");
    }

    public function testInvalidAIResponse()
    {
        $mockInvalid = 'Not JSON';
        $parsed = json_decode($mockInvalid, true);
        $this->assertNull($parsed, "Invalid JSON should return null");
    }
}