<?php

namespace AppBundle\Tests\Entity;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\TeacherParser;

class TeacherParserTest extends WebTestCase
{
    private $teacherParser;
    
    public function setUp()
    {
        $file = __DIR__ . '/../Controller/ExampleFile/teacher.xml';
        $xml = simplexml_load_file($file);
        
        $this->teacherParser = new TeacherParser($xml);
    }
    public function testClassCanBeInitialized()
    {
        $this->assertInstanceOf(TeacherParser::class, $this->teacherParser);
    }
    /**
     * @expectedException TypeError
     */
    public function testClassCantBeInitializedWithoutSimpleXmlElement()
    {
        $xml = null;
        $teacherParser = new TeacherParser($xml);
        $this->assertInstanceOf(TeacherParser::class, $teacherParser);
    }
    public function testGetParseResponse()
    {
        $response = $this->teacherParser->parse();
        $teacher = $response['teachers'][0];
        $this->assertEquals('Professor 1', $teacher->getFirstName());
        $this->assertEquals('Example 1', $teacher->getLastName());
        $this->assertEquals('professor@example.com', $teacher->getEmail());
        $this->assertEquals('123', $teacher->getRoom());
    }
    public function testGetParseWithError()
    {
        $xml = <<<XML
<teachers>
      <teacher>
           <first_name>Bruce</first_name>
           <last_name>Wayne</last_name>
           <email>memes@memes</email>
           <room>123</room>
      </teacher>
</teachers>
XML;
        $docXml = simplexml_load_string($xml);
        $teacherParser = new TeacherParser($docXml);
        $response = $teacherParser->parse();
        $error = $response['errors'][0];
        $this->assertEquals('teacher', $error->getType());
        $this->assertEquals('Wrong email', $error->getReason());
    }
}
