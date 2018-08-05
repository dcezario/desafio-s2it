<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use AppBundle\Entity\XmlParser;
use AppBundle\Form\XmlFileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $file = new File();
        
        $form = $this->createForm(XMLFileType::class, $file);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $xml = $file->getFile();
            $fileName = $this->getFileName() . '.xml';
            $xml->move(
                $this->getParameter('xml_directory'),
                $fileName
            );
            $file->setFile($fileName);
            try {
                $fileToParse = $this->getParameter('xml_directory') . '/' . $fileName;
                $xmlParser = new XmlParser($fileToParse);
                if (!$xmlParser->isValidXMLModel()) {
                    throw new \Exception('Invalid XML model uploaded. Please, try again');
                }
                $receivedData = $xmlParser->getResponse();
                $entityManager = $this->getDoctrine()->getManager();
                if (isset($receivedData['teachers'])) {
                    foreach ($receivedData['teachers'] as $teacher) {
                        $entityManager->persist($teacher);
                    }
                }
                if (isset($receivedData['students'])) {
                    foreach ($receivedData['students'] as $student) {
                        $entityManager->persist($student);
                    }
                }
                if (isset($receivedData['errors'])) {
                    foreach ($receivedData['errors'] as $error) {
                        $entityManager->persist($error);
                    }
                }
                $entityManager->flush();
                $data = array(
                    'success'   => $receivedData['success'],
                    'received'  => $receivedData['sent'],
                    'processed' => $receivedData['processed']
                );
                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
                
            } catch (\Exception $e) {
                $response = array(
                    'success' => 'false',
                    'message' => $e->getMessage()
                );
                return new JsonResponse ($response);
            }
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $error = (!empty($form->getErrors()) ? $form->getErrors(true) : 'File could not be uploaded');
            $response['success'] = 'false';
            $response['message']   = (string)$error;
            $errorMessage = new Response(json_encode($response));
            $errorMessage->headers->set('Content-Type', 'application/json');
            return $errorMessage;
        }
        return $this->render('default/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Return an unique name for the uploaded file
     * @return string $fileName
     */
    private function getFileName()
    {
        $fileName = md5(uniqid());
        return $fileName;
    }
}
