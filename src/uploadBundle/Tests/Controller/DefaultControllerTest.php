<?php

namespace uploadBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use uploadBundle\Tests\DataFixtures\LoadMyUsersForUploadusertest;
use Doctrine\Common\Persistence\ObjectManager;

use uploadBundle\Entity\Uploadinfo;
use uploadBundle\Entity\Notes;

class DefaultControllerTest extends WebTestCase
{
    public function setUp() {
        $this->client       = static::createClient();
        $this->container    = static::$kernel->getContainer();
        $this->em           = static::$kernel->getContainer()->get('doctrine')->getManager();
        
        $data = new Uploadinfo();
        $data->setFirstName("Test First Name");
        $data->setLastName("Test last Name");
        $data->setAddress("Test Address");
        $data->setAddressTwo("Address Two");
        $data->setEmail("test@gmail.com");
        $data->setCreatedDate(new \DateTime("now"));
        $data->setModifiedDate(new \DateTime("now"));
        $data->setPhone("98899898989");
        $this->em ->persist($data);
        $this->em ->flush();

    }
    
    public function tearDown() {
        
       $data =   $this->em->createQuery("SELECT a.id FROM uploadBundle:Uploadinfo a order by a.id desc")->getScalarResult(); 
       $id = isset($data[0])?$data[0]['id']:"";
        
       $this->em->createQuery("DELETE FROM uploadBundle:Uploadinfo a WHERE a.id = $id")->getScalarResult(); 
       $this->container->get('doctrine')->getConnection()->close();
       parent::tearDown();    
    }
    /**
     * Functional unit test cases for Index Action 
     * @group functional
     **/
    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');
        $title  = $crawler->filter('title');
        $this->assertEquals( 'Symfony Heroku Demo| Home Page', $title->text());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
    
    /**
     *  Functional PHP Unit Test cases for registerAction 
     *  @group functional
     **/
    public function testRegister(){
        
        $data = array("first_name" => "FirtName","last_name" => "LastName","address" => "Address one","addresstwo" => "Address two","email" => "test@gmail.com","phone" => "34534534535");
        $crawler = $this->client->request('POST', '/register/',  $data );
        $title  = $crawler->filter('title');
        $this->assertEquals( 'Symfony Heroku Demo| Sign Up', $title->text());
        
    }
    
    /**
     *  Functional PHP Unit test cases for list users details
     *  @group functional
     **/
    public function testView(){
        $crawler = $this->client->request('POST', '/list/',array());
        $title  = $crawler->filter('title');
        $this->assertEquals( 'Symfony Heroku Demo| List User', $title->text());
    }
    
    /**
     * FUnctional php unit test cases for Edit user detauls 
     *  @group functional
     **/
    public function testEdit(){
        $row = $this->em->createQuery("SELECT a.id FROM uploadBundle:Uploadinfo a  order by a.id desc")->getScalarResult(); 
        $id = $row[0]['id'];
        $crawler = $this->client->request('POST', '/edit/'.$id,array());
        $title  = $crawler->filter('title');
        $this->assertEquals( 'Symfony Heroku Demo| Edit User', $title->text()); 
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
    /**
     * Functional unit test cases for Index Action 
     * @group functional
     **/
    public function  testDelete(){
        
        $data = new Uploadinfo();
        $data->setFirstName("Test First Name");
        $data->setLastName("Test last Name");
        $data->setAddress("Test Address");
        $data->setAddressTwo("Address Two");
        $data->setEmail("test@gmail.com");
        $data->setCreatedDate(new \DateTime("now"));
        $data->setModifiedDate(new \DateTime("now"));
        $data->setPhone("98899898989");
        $this->em ->persist($data);
        $this->em ->flush();
        
        $row = $this->em->createQuery("SELECT a.id FROM uploadBundle:Uploadinfo a  order by a.id desc")->getScalarResult(); 
        $id = $row[0]['id'];
        $crawler = $this->client->request('POST', '/delete',array('id'=>$id));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        
    }
    
}