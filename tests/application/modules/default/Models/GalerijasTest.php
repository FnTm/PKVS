<?php

require_once '../application/modules/default/models/Galerijas.php';
/**
 * @author Janis Peisenieks
 * @package Test
 * @subpackage Admin
 */
class GalerijasTest extends ControllerTestCase
{

    /** Contains insert values for a test
     * @var array
     */
    public $mockInsert;
    /**
     * @var Model_Galerijas
     */
    public $galleryModel;
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
        $this->mockInsert = array("galleryTitle" => "TestaGalerija", "galleryDescription" => "TestaGalerija");
        $this->galleryModel = new Model_Galerijas();
    }

    /**
     * Tests creation and getting of a gallery
     */
    public function testCreateAndGetGallery()
    {
        $id = $this->galleryModel->createGallery($this->mockInsert);
        $this->insertId = $id;
        $this->assertInternalType("string", $id);
        $this->assertInternalType("array", $this->galleryModel->getGallery($id));

    }

    /**
     * Tests updating of a gallery
     */
    public function testUpdateGallery()
    {
        $title = "NewTestTitle";
        $id = $this->galleryModel->createGallery($this->mockInsert);
        $this->insertId = $id;
        $this->assertInternalType("string", $id);
        $this->assertEquals("1", $this->galleryModel->updateGallery($id, array("galleryTitle" => $title)));
        $returned = $this->galleryModel->getGallery($id);
        $this->assertEquals($title, $returned['galleryTitle']);
    }

    /**
     *
     */
    public function testGetAll()
    {
        $this->assertInternalType("array", $this->galleryModel->getAll());
    }

    /**
     *
     */
    public function tearDown()
    {
        if (!is_null($this->insertId)) {
            $this->galleryModel->deleteGallery($this->insertId);
        }
    }
}
