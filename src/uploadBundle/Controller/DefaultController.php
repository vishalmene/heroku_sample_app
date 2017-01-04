<?php

namespace uploadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use uploadBundle\Entity\Uploadinfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DefaultController extends Controller {
    
    /**
     *  Default action 
     * ***/
    
    public function indexAction(Request $request)
    {
        return $this->render('uploadBundle:Default:home.html.twig');
    }
    
    
    /**
     * 
     * Function to Register user 
     * 
     * ***/
    public function registerAction(Request $request) {
        $filename = "";
        $em_app = $this->getDoctrine()->getManager();
        $dataArr = $request->request->all();
        $files = $request->files->all();
        if ($request->getMethod() == 'POST' && (!empty($dataArr) && isset($files["file"]))) {
            $data = new Uploadinfo();
            if (is_dir($data->getDocumentPdfAbsolutePath())) {
                $book_cover_page = $files["file"];
                $filename = $book_cover_page->getClientOriginalName();
                $bookCoverFilePath = $data->getDocumentPdfAbsolutePath() . DIRECTORY_SEPARATOR;
                $book_cover_page->move($bookCoverFilePath, $filename);
            }
            $data->setFirstName($dataArr['first_name']);
            $data->setLastName($dataArr['last_name']);
            $data->setAddress($dataArr['address']);
            $data->setEmail($dataArr['email']);
            $data->setFile($filename);
            $data->setCreatedDate(new \DateTime("now"));
            $data->setModifiedDate(new \DateTime("now"));
            $data->setPhone($dataArr['phone']);
            $em_app->persist($data);
            $em_app->flush();
            $url = $this->generateUrl('upload_view');
            return $this->redirect($url);
        }
        return $this->render('uploadBundle:Default:index.html.twig');
    }
    /**
     * 
     *  Function to view All user details
     * 
     * ***/
    public function viewAction(Request $request) {
        $em_app = $this->getDoctrine()->getManager();
        $resultObj = $em_app->getRepository('uploadBundle:Uploadinfo')->findBy(array(),array('id' => 'DESC'));
        return $this->render('uploadBundle:Default:view.html.twig', array('data' => $resultObj));
    }
    /**
     * 
     *  Function to edit user Details
     * 
     * **/
    public function editAction(Request $request, $id) {
        $em_app = $this->getDoctrine()->getManager();
        $data = $em_app->getRepository('uploadBundle:Uploadinfo')->findOneById($id);
       
        $dataArr = $request->request->all();
        $files = $request->files->all();
        if ($request->getMethod() == 'POST' && !empty($dataArr)) {
           
            if(isset($files["file"])){
                 $dataFile = new Uploadinfo();
            if (is_dir($dataFile->getDocumentPdfAbsolutePath())) {
                $book_cover_page = $files["file"];
                $filename = $book_cover_page->getClientOriginalName();
                $bookCoverFilePath = $dataFile->getDocumentPdfAbsolutePath() . DIRECTORY_SEPARATOR;
                $book_cover_page->move($bookCoverFilePath, $filename);
            }
            }else{
               $filename = $data->getFile();
            }
            $data->setFirstName($dataArr['first_name']);
            $data->setLastName($dataArr['last_name']);
            $data->setAddress($dataArr['address']);
            $data->setEmail($dataArr['email']);
            $data->setFile($filename);
            $data->setModifiedDate(new \DateTime("now"));
            $data->setPhone($dataArr['phone']);
            $em_app->persist($data);
            $em_app->flush();
            $url = $this->generateUrl('upload_view');
            return $this->redirect($url);
        }
        return $this->render('uploadBundle:Default:edit.html.twig', array('data' => $data));
    }

    /**
     * 
     *  Function to view userdetails.
     * 
     * ***/
    public function viewuserAction(Request $request, $id) {
        
        $em_app = $this->getDoctrine()->getManager();
        $data = $em_app->getRepository('uploadBundle:Uploadinfo')->findOneById($id);
        return $this->render('uploadBundle:Default:viewuser.html.twig', array('data' => $data)); 
    }
    /**
     * 
     *  Function to delete user details
     * 
     * ****/
    public function deleteAction(Request $request) {
        $flag = false;
        $em_app = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') {
            $id = $request->request->all()['id'];
            if (isset($id) && $id != "") {
                $em_app->createQuery("DELETE FROM uploadBundle:Uploadinfo a  WHERE a.id = $id")->getScalarResult();
                $flag = true;
            }
        }
        return new Response(json_encode($flag), 200, array('Content-Type' => 'application/json'));
    }

}
