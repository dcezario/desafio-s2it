<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\Log;

class TeacherParser
{
    /**
     * Receives the xml
     * @var SimpleXmlElement
     */
    private $xml;
    /**
     * Errors from uploaded xml
     * @var array
     */
    private $errors = array();
    /**
     * Teacher objects
     */
    private $teachers = array();
    /**
     * Initialize the class
     * @param \SimpleXMLElement $xml
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->xml = $xml;
    }
    /**
     * Persist and return the data from XML 
     * @return string $parsedData
     * @throws \Exception
     */
    public function parse()
    {
        try{
            $this->checkXml();
            $sent = $this->countEntries();
            $processed = 0;
            foreach ($this->xml->teacher as $teacher) {
                if ($this->validateTeacher($teacher)) {
                    $this->setTeacher($teacher);
                    $processed++;
                }
            }
            $parsedData['success']   = ($processed > 0 ? 'true' : 'false');
            $parsedData['processed'] = $processed;
            $parsedData['sent']      = $sent;
            $parsedData['teachers']  = $this->getTeachers();
            $parsedData['errors']    = $this->getErrors();
            return $parsedData;
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * Check the given XML to look for inconsistenses
     */
    private function checkXml()
    {
        $xml = $this->xml;
        if (!isset($xml->teacher)) {
            throw new \Exception ('No teachers found to register');
        }
    }
    /**
     * 
     * @param SimpleXmlElement $xml
     * @return string
     * @throws \Exception case no entry can be found
     */
    private function countEntries()
    {
        $xml = $this->xml;
        $count = $xml->children()->count();
        if ($count < 1) {
            throw new \Exception('None entry has been found');
        }
        return $count;
    }
    /**
     * Validate the data from the given teacher
     * @param SimpleXmlElement $teacher
     */
    private function validateTeacher($teacher)
    {
        try {
            $this->validateFirstName($teacher);
            $this->validateLastName($teacher);
            $this->validateEmail($teacher);
            $this->validateRoom($teacher);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
    /**
     * Validates the teacher first name
     * @param SimpleXmlElement $teacher
     * @return boolean
     */
    private function validateFirstName($teacher)
    {
        if (!isset($teacher->first_name) || empty($teacher->first_name)) {
            $log = new Log();
            $log->setType('teacher');
            $log->setData(json_encode($teacher));
            $log->setReason('Wrong first name');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Validates the teacher last name
     * @param SimpleXmlElement $teacher
     * @return boolean
     */
    private function validateLastName($teacher)
    {
        if (!isset($teacher->last_name) || empty($teacher->last_name)) {
            $log = new Log();
            $log->setType('teacher');
            $log->setData(json_encode($teacher));
            $log->setReason('Wrong last name');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Validates the teacher email
     * @param SimpleXmlElement $teacher
     * @return boolean
     */
    private function validateEmail($teacher)
    {
        if (!isset($teacher->email) || empty($teacher->email) || !filter_var($teacher->email, FILTER_VALIDATE_EMAIL)) {
            $log = new Log();
            $log->setType('teacher');
            $log->setData(json_encode($teacher));
            $log->setReason('Wrong email');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Validates the teacher room
     * @param SimpleXmlElement $teacher
     * @return boolean
     */
    private function validateRoom($teacher)
    {
        if (!isset($teacher->room) || empty($teacher->room)) {
            $log = new Log();
            $log->setType('teacher');
            $log->setData(json_encode($teacher));
            $log->setReason('Wrong room');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Set a new teacher
     * @param TeacherObject $teacher
     */
    private function setTeacher($teacher)
    {
        $newTeacher = new Teacher();
        $newTeacher->setFirstName((string)$teacher->first_name);
        $newTeacher->setLastName((string)$teacher->last_name);
        $newTeacher->setEmail((string)$teacher->email);
        $newTeacher->setRoom((string)$teacher->room);
        $this->teachers[] = $newTeacher;
    }
    /**
     * Return all teachers
     * @return array
     */
    public function getTeachers()
    {
        return $this->teachers;
    }
    /**
     * Set the errors for log purpose
     * @param Log Object $log
     */
    private function setError($log)
    {
        $this->errors[] = $log;
    }
    /**
     * Retrieve the errors from xml processing
     * @return array $errors;
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
