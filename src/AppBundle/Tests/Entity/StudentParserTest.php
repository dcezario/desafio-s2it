<?php

namespace AppBundle\Tests\Entity;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\StudentParser;

class StudentParserTest extends WebTestCase
{
    private $studentParser;
    
    public function setUp()
    {
        $file = __DIR__ . '/../Controller/ExampleFile/student.xml';
        $xml = simplexml_load_file($file);
        
        $this->studentParser = new StudentParser($xml);
    }
    public function testClassCanBeInitialized()
    {
        $this->assertInstanceOf(StudentParser::class, $this->studentParser);
    }
    /**
     * @expectedException TypeError
     */
    public function testClassCantBeInitializedWithoutSimpleXmlElement()
    {
        $xml = null;
        $studentParser = new StudentParser($xml);
        $this->assertInstanceOf(StudentParser::class, $studentParser);
    }
    public function testGetParseResponse()
    {
        $response = $this->studentParser->parse();
        $student = $response['students'][0];
        $this->assertEquals('Student', $student->getFirstName());
        $this->assertEquals('Example', $student->getLastName());
        $this->assertEquals('student@email.com', $student->getEmail());
        $this->assertEquals('00 1111 2222', $student->getPhone());
    }
    public function testGetParseWithError()
    {
        $xml = <<<XML
<students>
      <student>
           <first_name>Bruce</first_name>
           <last_name>Wayne</last_name>
           <email>memes@dahora.com</email>
           <phone></phone>
      </student>
</students>
XML;
        $docXml = simplexml_load_string($xml);
        $studentParser = new StudentParser($docXml);
        $response = $studentParser->parse();
        $error = $response['errors'][0];
        $this->assertEquals('student', $error->getType());
        $this->assertEquals('Wrong phone', $error->getReason());
        $this->assertEquals('{"first_name":"Bruce","last_name":"Wayne","email":"memes@dahora.com","phone":{}}', $error->getData());
    }
}
