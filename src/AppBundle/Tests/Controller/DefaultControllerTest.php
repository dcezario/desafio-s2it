<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultControllerTest extends WebTestCase
{
    /**
     * Holds a "browser" client to perform our requests
     * @var Client Object 
     */
    private $client;
    /**
     * Path to create an error xml file
     * @var string
     */
    private $wrongXmlPath = __DIR__ . '/ExampleFile/error.xml';
    /**
     * Path to create a valid XML file to our tests
     * @var string
     */
    private $xmlPath = __DIR__ . '/ExampleFile/teacher.xml';
    
    public function setUp()
    {
        $this->client = static::createClient();
        $this->createSampleXML();
        $this->createWrongSampleXml();
    }
    /**
     * Creates a sample xml to tests
     */
    private function createSampleXml()
    {
        if (!file_exists($this->xmlPath)) {
            $fp = fopen($this->xmlPath, 'a+');
            $xmlData = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
  <teachers>
    <teacher>
      <first_name>Professor 1</first_name>
      <last_name>Example 1</last_name>
      <email>professor@example.com</email>
      <room>123</room>
    </teacher>
  </teachers>
XML;
            fwrite($fp, $xmlData);
            fclose($fp);
        }
    }
    /**
     * Creates a invalid XML file to test
     */
    private function createWrongSampleXml()
    {
        if(!file_exists($this->wrongXmlPath)) {
            $fp = fopen($this->xmlPath, 'a+');
            fwrite($fp, 'foo');
            fclose($fp);
        }
    }
    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('XML upload form', $crawler->filter('.container h1')->text());
    }
    public function testXMLCanBeUploaded()
    {
        $filePath = $this->xmlPath;
        $csrfToken = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('file_item');
        $uploadedFile = new UploadedFile($filePath, 'teacher.xml', 'text/xml', null, null, true);

        $form = array();
        $form['xml_file']['_token'] = $csrfToken;
        $form['xml_file']['file'] = $uploadedFile;
        $this->client->request('POST', '/', $form, array(), array());
        $expectedResponse = '{"success":"true","received":1,"processed":1}';
        $this->assertEquals($expectedResponse, $this->client->getResponse()->getContent());
    }
    public function testInvalidFileCantBeUploaded()
    {
        $filePath = $this->wrongXmlPath;
        $csrfToken = $this->client->getContainer()->get('form.csrf_provider')->generateCsrfToken('file_item');
        $uploadedFile = new UploadedFile($filePath, 'teacher.xml', 'text/xml', null, null, true);

        $form = array();
        $form['xml_file']['_token'] = $csrfToken;
        $form['xml_file']['file'] = $uploadedFile;
        $this->client->request('POST', '/', $form, array(), array());
        $this->assertContains('ERROR: Please upload a valid XML', $this->client->getResponse()->getContent());
    }
}
