<?php

namespace uploadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use uploadBundle\Entity\Uploadinfo;
use uploadBundle\Entity\Notes;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DefaultController extends Controller {

    /**
     *  Default action 
     * ** */
    public function indexAction(Request $request) {
        return $this->render('uploadBundle:Default:home.html.twig');
    }

    /**
     * 
     * Function to Register user 
     * 
     * ** */
    public function registerAction(Request $request) {

//         $path  =  $this->container->get('kernel')->getRootdir().'/../web/uploads/testfile.txt';
//       
//        $handle = fopen($path, "a"); 
//        fwrite($handle, "============Currently we are in registration page =======");
//        
//        fclose($handle);
        $userManager = $this->get('user');
        $userInfo['data'] = $request->request->all();
        $userInfo['fileinfo'] = $request->files->all();
        if ($request->getMethod() == 'POST' && (!empty($userInfo['data']) && isset($userInfo['fileinfo']["file"]))) {
            $userManager->saveUserDetails($userInfo);
            $url = $this->generateUrl('upload_view');
            return $this->redirect($url);
        }
        return $this->render('uploadBundle:Default:index.html.twig');
    }

    /**
     * 
     *  Function to view All user details
     * 
     * ** */
    public function viewAction(Request $request) {
        $em_app = $this->getDoctrine()->getManager();
        $resultObj = $em_app->getRepository('uploadBundle:Uploadinfo')->findBy(array(), array('id' => 'DESC'));
        return $this->render('uploadBundle:Default:view.html.twig', array('data' => $resultObj));
    }

    /**
     * 
     *  Function to edit user Details
     * 
     * * */
  public function editAction(Request $request, $id) {

        $em_app = $this->getDoctrine()->getManager();
        $userManager = $this->get('user');
        $data = $em_app->getRepository('uploadBundle:Uploadinfo')->findOneById($id);
        $userInfo['data'] = $request->request->all();
        $userInfo['fileinfo'] = $request->files->all();
        
        if ($request->getMethod() == 'POST' && !empty($userInfo['data'])) {
            $userManager->updateUserDetails($userInfo,$id);
            $url = $this->generateUrl('upload_view');
            return $this->redirect($url);
        }
        return $this->render('uploadBundle:Default:edit.html.twig', array('data' => $data));
    }

    /**
     * 
     *  Function to view userdetails.
     * 
     * ** */
    public function viewuserAction(Request $request, $id) {
        $em_app = $this->getDoctrine()->getManager();
        $data = $em_app->getRepository('uploadBundle:Uploadinfo')->findOneById($id);
        return $this->render('uploadBundle:Default:viewuser.html.twig', array('data' => $data));
    }

    /**
     * 
     *  Function to delete user details
     * 
     * *** */
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

    /**
     *  Add user notes
     * *** */
    public function userNoteAction(Request $request) {
        $em_app = $this->getDoctrine()->getManager();
        $userManager = $this->get('user');
        $dataArr = $request->request->all();
        if ($request->getMethod() == 'POST' && (!empty($dataArr) )) {
            $userManager->saveNote($dataArr);
            $url = $this->generateUrl('view_note');
            return $this->redirect($url);
        }
        return $this->render('uploadBundle:Default:note.html.twig');
    }

    public function viewNoteAction(Request $request) {
        $em_app = $this->getDoctrine()->getManager();
        $resultObj = $em_app->getRepository('uploadBundle:Notes')->findBy(array(), array('id' => 'DESC'));
        return $this->render('uploadBundle:Default:viewnote.html.twig', array('data' => $resultObj));
    }

}
