<?php
ini_set('memory_limit', '1024M');

use PHPUnit\Framework\TestCase;

final class CardControllerTest extends TestCase
{
    public function laodReferenceReadCardContent(): array
    {
        return json_decode(file_get_contents('tests/ReferenceReadCardContent.json'), true);
    }
    public function testCardCanBeRequestedByIdProperly()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/card/1669af17-d287-5094-b005-4b143441442f');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $obtainedReadCardContent = curl_exec($ch);
        curl_close($ch);

        $referenceReadCardContent = $this->laodReferenceReadCardContent();
        $this->assertEquals($obtainedReadCardContent, json_encode($referenceReadCardContent));
    }
}


?>