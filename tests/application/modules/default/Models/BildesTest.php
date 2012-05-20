<?php

require_once '../application/modules/default/models/Bildes.php';
/**
 * @author Janis Peisenieks
 * @package Test
 * @subpackage Admin
 */
class BildesTest extends ControllerTestCase
{

    /** Contains insert values for a test
     * @var array
     */
    public $mockInsert;
    /**
     * @var Model_Bildes
     */
    public $pictureModel;
    /**
     * @var null
     */
    public $insertId = null;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $galleryModel=new Model_Galerijas();
        $galerijas=$galleryModel->getAll();
        $this->mockInsert = array("pictureExt"=>"jpg","pictureTitle" => "TestaBilde", "pictureDescription" => "TestaBilde","galleryId" => $galerijas[0]['galleryId']);
        $this->pictureModel = new Model_Bildes();
    }

    /**
     * Tests creation and getting of a picture
     */
    public function testCreateAndGetPicture()
    {
        $id = $this->pictureModel->createPicture($this->mockInsert);
        $this->insertId = $id;
        $this->assertInternalType("string", $id);
        $this->assertInternalType("array", $this->pictureModel->getPicture($id));

    }

    /**
     * Tests updating of a picture
     */
    public function testUpdatePicture()
    {
        $title = "NewTestTitle";
        $id = $this->pictureModel->createPicture($this->mockInsert);
        $this->insertId = $id;
        $this->assertInternalType("string", $id);
        $this->assertEquals("1", $this->pictureModel->updatePicture($id, array("pictureTitle" => $title)));
        $returned = $this->pictureModel->getPicture($id);
        $this->assertEquals($title, $returned['pictureTitle']);
    }

    /**
     *
     */
    public function testGetAll()
    {
        $this->assertInternalType("array", $this->pictureModel->getAll());
    }

    /**
     *
     */
    public function tearDown()
    {
        if (!is_null($this->insertId)) {
            $this->pictureModel->deletePicture($this->insertId);
        }
    }
}
