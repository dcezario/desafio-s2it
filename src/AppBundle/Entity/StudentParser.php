<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Student;
use AppBundle\Entity\Log;

class StudentParser
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
     * Students objects
     */
    private $students = array();
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
            foreach ($this->xml->student as $student) {
                if ($this->validateStudent($student)) {
                    $this->setStudent($student);
                    $processed++;
                }
            }
            $parsedData['success']   = ($processed > 0 ? 'true' : 'false');
            $parsedData['processed'] = $processed;
            $parsedData['sent']      = $sent;
            $parsedData['students']  = $this->getStudents();
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
        if (!isset($xml->student)) {
            throw new \Exception ('No students found to register');
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
     * Validate the data from the given student
     * @param SimpleXmlElement $student
     */
    private function validateStudent($student)
    {
        try {
            $this->validateFirstName($student);
            $this->validateLastName($student);
            $this->validateEmail($student);
            $this->validatePhone($student);
            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
    /**
     * Validates the student first name
     * @param SimpleXmlElement $student
     * @return boolean
     */
    private function validateFirstName($student)
    {
        if (!isset($student->first_name) || empty($student->first_name)) {
            $log = new Log();
            $log->setType('student');
            $log->setData(json_encode($student));
            $log->setReason('Wrong first name');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Validates the student last name
     * @param SimpleXmlElement $student
     * @return boolean
     */
    private function validateLastName($student)
    {
        if (!isset($student->last_name) || empty($student->last_name)) {
            $log = new Log();
            $log->setType('student');
            $log->setData(json_encode($student));
            $log->setReason('Wrong last name');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Validates the student email
     * @param SimpleXmlElement $student
     * @return boolean
     */
    private function validateEmail($student)
    {
        if (!isset($student->email) || empty($student->email) || !filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
            $log = new Log();
            $log->setType('student');
            $log->setData(json_encode($student));
            $log->setReason('Wrong email');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Validates the student phone
     * @param SimpleXmlElement $student
     * @return boolean
     */
    private function validatePhone($student)
    {
        if (!isset($student->phone) || empty($student->phone)) {
            $log = new Log();
            $log->setType('student');
            $log->setData(json_encode($student));
            $log->setReason('Wrong phone');
            $this->setError($log);
            throw new \Exception('error');
        }
        return true;
    }
    /**
     * Set a new student
     * @param StudentObject $student
     */
    private function setStudent($student)
    {
        $newStudent = new Student();
        $newStudent->setFirstName((string)$student->first_name);
        $newStudent->setLastName((string)$student->last_name);
        $newStudent->setEmail((string)$student->email);
        $newStudent->setPhone((string)$student->phone);
        $this->students[] = $newStudent;
    }
    /**
     * Return all teachers
     * @return array
     */
    public function getStudents()
    {
        return $this->students;
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
