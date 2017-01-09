<?php
namespace uploadBundle\Tests\Services; 

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserManagerTest extends WebTestCase {
      private $default_em;  
      
      public function setUp()
    {        
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        
        $this->container            = static::$kernel->getContainer();
        $this->usermanager          = static::$kernel->getContainer()->get('user');
        $this->default_em           = static::$kernel->getContainer()->get('doctrine')->getManager();        
        
    }
    
    public function tearDown() {
       $this->container->get('doctrine')->getConnection()->close();
       parent::tearDown();
    }
    /**
     * unit test cases for testSaveUserDetails
     * @group unit 
     * 
     *****/
    
    public function testSaveUserDetails(){
     $data =    array(
        "data" => array("first_name" => "Test First",
            "last_name" => "Test Last", "address" => "TEST Address one",
            "addresstwo" => "test address two","email" => "test@gmail.com",
            "phone" => "8989898989898")
         );
        $result  = $this->usermanager->saveUserDetails($data);
         $this->assertEquals( $result , true );
    }
    /**
     * 
     * Unite test cases for testupdateUserDetails
     * @group unit
     * ****/
    public function testupdateUserDetails(){
        
       $row = $this->default_em->createQuery("SELECT a.id FROM uploadBundle:Uploadinfo a  order by a.id desc")->getScalarResult(); 
       $id = $row[0]['id'];  
       $data =    array(
        "data" => array("first_name" => "Test First Update",
            "last_name" => "Test Last", "address" => "TEST Address one",
            "addresstwo" => "test address two","email" => "test@gmail.com",
            "phone" => "8989898989898")
         ); 
       $result  = $this->usermanager->updateUserDetails($data,$id);
       $this->assertEquals( $result , true );
    }
    /**
     * unit test cases for SaveNote 
     * @group unit
     * 
     ***/
    
    public function testSaveNote(){
        
      $data =  array( "name" => "test","note" => "test test test test");
      $result  = $this->usermanager->saveNote($data);
      $this->assertEquals( $result , true ); 
    }
    
}