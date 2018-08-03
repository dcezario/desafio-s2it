<?php

namespace AppBundle\Tests\Entity;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\XmlParser;

class XmlParserTest extends WebTestCase
{
    private $xmlParser;
    public function setUp()
    {
        $filePath = __DIR__.'/../Controller/ExampleFile/teacher.xml';
        $this->xmlParser = new XmlParser($filePath);
    }
    public function testClassCanBeInitialized()
    {
        $this->assertInstanceOf(XmlParser::class, $this->xmlParser);
    }
    /**
     * @expectedException Exception
     */
    public function testClassCantBeInitializedWithWrongXml()
    {
        $wrongXml = new XmlParser('foo');
        $wrongXml->getXML();
        
        $this->assertTrue($wrongXml);
    }
    public function testXmlCanBeReturned()
    {
        $this->assertNotEmpty($this->xmlParser->getXml());
    }
    public function testValidModelCanBepassed()
    {
        $this->assertTrue($this->xmlParser->isValidXMLModel());
    }
    public function testInvalidModelCantBePassed()
    {
        $xmlPath = __DIR__ . '/../Controller/ExampleFile/wrong_model.xml';
        $xmlParser = new XmlParser($xmlPath);
        $this->assertNotTrue($xmlParser->isValidXMLModel());
    }
    public function testGetTeacherModel()
    {
        $this->assertEquals('teachers', $this->xmlParser->getModel());
    }
    public function testGetStudentModel()
    {
        $xmlPath = __DIR__ . '/../Controller/ExampleFile/student.xml';
        $xmlParser = new XmlParser($xmlPath);
        $this->assertEquals('students', $xmlParser->getModel());
    }
    public function testGetTeacherResponse()
    {
        $response = $this->xmlParser->getResponse();
        $teacher = $response['teachers'][0];
        $this->assertEquals('Professor 1', $teacher->getFirstName());
        $this->assertEquals('Example 1', $teacher->getLastName());
        $this->assertEquals('professor@example.com', $teacher->getEmail());
        $this->assertEquals('123', $teacher->getRoom());
    }
    public function testGetStudentResponse()
    {
        $file = __DIR__ . '/../Controller/ExampleFile/student.xml';
        $xmlParser = new XmlParser($file);
        $response = $xmlParser->getResponse();
        $student = $response['students'][0];
        $this->assertEquals('Student', $student->getFirstName());
        $this->assertEquals('Example', $student->getLastName());
        $this->assertEquals('student@email.com', $student->getEmail());
        $this->assertEquals('00 1111 2222', $student->getPhone());
    }
}
