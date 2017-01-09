<?php

namespace uploadBundle\Services;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use uploadBundle\Entity\Uploadinfo;
use uploadBundle\Entity\Notes;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class UserManager {
    
   public function __construct(EntityManager $entityManager) {
        $this->default_em   = $entityManager;
    }
    /**
     * Function to save user data data  
     * 
     *****/
    public function  saveUserDetails($userData){
        
       $filename = ""; 
       $dataArr = isset($userData['data'])?$userData['data'] :"";
       if(isset($dataArr) && !empty($dataArr)){
            $data = new Uploadinfo();
            if ((is_dir($data->getDocumentPdfAbsolutePath())) && (isset($userData['fileinfo'])&& !empty($userData['fileinfo']))) {
                $book_cover_page = $userData['fileinfo']["file"];
                $filename = $book_cover_page->getClientOriginalName();
                $bookCoverFilePath = $data->getDocumentPdfAbsolutePath() . DIRECTORY_SEPARATOR;
                $book_cover_page->move($bookCoverFilePath, $filename);
            }
            $data->setFirstName($dataArr['first_name']);
            $data->setLastName($dataArr['last_name']);
            $data->setAddress($dataArr['address']);
            $data->setAddressTwo($dataArr['addresstwo']);
            $data->setEmail($dataArr['email']);
            $data->setFile($filename);
            $data->setCreatedDate(new \DateTime("now"));
            $data->setModifiedDate(new \DateTime("now"));
            $data->setPhone($dataArr['phone']);
            $this->default_em->persist($data);
            $this->default_em->flush();
            return true;
       }
       return false;
    }
    /**
     *Function to update user data 
     * 
     ***/
    public function updateUserDetails($userInfo,$id){
        
        $dataArr = isset($userInfo['data'])?$userInfo['data']:"";
        $files=  isset($userInfo['fileinfo'])?$userInfo['fileinfo']:"";
        $data = $this->default_em->getRepository('uploadBundle:Uploadinfo')->findOneById($id);
        if(isset($dataArr) && !empty($dataArr)){
            if (isset($files["file"])) {
                    $dataFile = new Uploadinfo();   
                    if (is_dir($dataFile->getDocumentPdfAbsolutePath())) {
                        $book_cover_page = $files["file"];
                        $filename = $book_cover_page->getClientOriginalName();
                        $bookCoverFilePath = $dataFile->getDocumentPdfAbsolutePath() . DIRECTORY_SEPARATOR;
                        $book_cover_page->move($bookCoverFilePath, $filename);
                    }
                } else {
                    $filename = $data->getFile();
                }
            $data->setFirstName($dataArr['first_name']);
            $data->setLastName($dataArr['last_name']);
            $data->setAddress($dataArr['address']);
            $data->setAddressTwo($dataArr['addresstwo']);
            $data->setEmail($dataArr['email']);
            $data->setFile($filename);
            $data->setModifiedDate(new \DateTime("now"));
            $data->setPhone($dataArr['phone']);
            $this->default_em->persist($data);
            $this->default_em->flush();
            return true;
        }
        return false;
    }
    /**
     * function to save note
     * 
     ****/
    public function saveNote($dataArr){
        $data = new Notes();
        if(isset($dataArr) && !empty($dataArr)){
            $data->setName($dataArr['name']);
            $data->setNote($dataArr['note']);
            $this->default_em->persist($data);
            $this->default_em->flush();
            return true;
        }
        return false;
    }
    
    
}
