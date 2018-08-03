<?php

namespace AppBundle\Entity;

use AppBundle\Entity;
/** 
 * Class to parse the XML uploaded
 * @version <1.0.0>
 * @author Diego Cezario <dcezarioo@gmail.com>
 */

class XmlParser
{
    /**
     * Path to the XML file
     * @var string 
     */
    private $filePath;
    private $validModels = array('teachers', 'students');
    /**
     * Initialize the class
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    /**
     * Creates the simple xml object
     * @param  string $file
     * @return SimpleXmlObject $xml
     * @throws \Exception for misformatted file
     */
    public function getXML()
    {
       try{
          $xml = simplexml_load_file($this->filePath);
          return $xml; 
       }
       catch (\Exception $e) {
           throw new \Exception ($e->getMessage());
       }
    }
    /**
     * Verify if the given XML has a valid model pattern
     * @return boolean
     */
    public function isValidXMLModel()
    {
        $xml = $this->getXML();
        if (!in_array($xml->getName(), $this->validModels)) {
            return false;
        }
        return true;
    }
    /**
     * Return the model of the XML doc being parsed
     * @return string $model
     */
    public function getModel()
    {
        $model = $this->getXML()->getName();
        return $model;
    }
    /**
     * Return the model response for the given XML
     * @return array $response
     * @throws Exception for not valid XML or parsing error
     */
    public function getResponse()
    {
        $model = $this->getModel();
        $xml   = $this->getXML();
        try {
            $class = ($model == 'teachers' ? new TeacherParser($xml) : new StudentParser($xml));
            return $class->parse();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
