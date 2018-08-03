<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\Student;
/**
 * Description of ApiController
 *
 * @author diego
 */
class ApiController extends Controller
{
    /**
     * @Route("/students")
     */
    public function getAllStudentAction()
    {
        $students = $this->getDoctrine()
                ->getRepository(Student::class)
                ->createQueryBuilder('e')
                ->select('e')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $response = new Response(json_encode($students));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route ("/student/{id}", name="student_get", requirements={"id":"\d+"})
     */
    public function getStudentAction($id)
    {
        $student = $this->getDoctrine()
        ->getRepository(Student::class)
        ->find($id);

        if (!$student) {
            throw $this->createNotFoundException(
                'No student found for id '.$id
            );
        }
        $return = array(
            'id'         => $student->getId(),
            'firstName' => $student->getFirstName(),
            'lastName'  => $student->getLastName(),
            'email'      => $student->getEmail(),
            'phone'      => $student->getPhone(),
        );
        $response = new Response(json_encode($return));
        
        return $response;
    }
    /**
     * @Route("/teachers")
     */
    public function getAllTeacherAction()
    {
        $teachers = $this->getDoctrine()
                ->getRepository(Teacher::class)
                ->createQueryBuilder('e')
                ->select('e')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $response = new Response(json_encode($teachers));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route ("/teacher/{id}", name="teacher_get", requirements={"id":"\d+"})
     */
    public function getTeacherAction($id)
    {
        $teacher = $this->getDoctrine()
        ->getRepository(Teacher::class)
        ->find($id);

        if (!$teacher) {
            throw $this->createNotFoundException(
                'No teacher found for id '.$id
            );
        }
        $return = array(
            'id'        => $teacher->getId(),
            'firstName' => $teacher->getFirstName(),
            'lastName'  => $teacher->getLastName(),
            'email'     => $teacher->getEmail(),
            'room'      => $teacher->getRoom(),
        );
        $response = new Response(json_encode($return));
        
        return $response;
    }
}
