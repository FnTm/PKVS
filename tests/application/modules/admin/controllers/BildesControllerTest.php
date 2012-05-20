<?php
/**
 *
 */

class BildesControllerTest extends ControllerTestCase
{

    /**
     * Id of the pic to delete
     * @var null
     */
    public $_deleteId = null;

    /**
     *
     */
    public function testIndex()
    {
        $this->dispatch('/admin/bildes');
        $this->assertAction("index");
        $this->assertController("bildes");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }

    /**
     * @todo implement checking if a form is present
     */
    public function testPievienot()
    {
        $this->dispatch('/admin/bildes/pievienot');
        $this->assertAction("pievienot");
        $this->assertController("bildes");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }


    public function testRedigetWithSuccess()
    {
        $pictureModel = new Model_Bildes();

        $galleryModel = new Model_Galerijas();
        $galerijas = $galleryModel->getAll();
        $this->assertInternalType("string", $id = $pictureModel->createPicture(array('pictureTitle' => 'user123',
            'pictureDescription' => '43215',
            'galleryId' => $galerijas[0]['galleryId'],"pictureExt"=>"jpg")));
        //var_export($id);
        $this->request->setMethod('POST')
            ->setPost(array(
            'pictureTitle' => 'user1234',
            'pictureDescription' => '432155',
            'galleryId' => $galerijas[0]['galleryId']
        ));


        $this->dispatch('/admin/bildes/rediget/id/' . $id);

        $this->assertRedirectTo('/admin/bildes');

    }

    public function testRedigetWithoutSuccess()
    {
        $galleryModel = new Model_Galerijas();
        $galerijas = $galleryModel->getAll();
        $this->request->setMethod('POST')
            ->setPost(array(
            'pictureDescription' => '43215',
            'galleryId' => $galerijas[0]['galleryId']
        ));

        $pictureModel = new Model_Bildes();
        $all = $pictureModel->getAll();
        $this->dispatch('/admin/bildes/rediget/id/' . $all[0]['pictureId']);
        $this->assertAction("rediget");
        $this->assertController("bildes");
    }

    public function testRedigetWithoutId()
    {
        $this->dispatch('/admin/bildes/rediget/id/');
        $this->assertRedirectTo('/admin/bildes');
    }

    public function testRedigetGetWithoutSubmit()
    {
        $pictureModel = new Model_Bildes();
        $all = $pictureModel->getAll();
        $this->dispatch('/admin/bildes/rediget/id/' . $all[0]['pictureId']);
        $this->assertAction("rediget");
        $this->assertController("bildes");
    }

    public function testDzestWithoutId()
    {
        $this->dispatch('/admin/bildes/dzest/id/');
        $this->assertRedirectTo('/admin/bildes');
    }

    public function testDzestWithId()
    {
        $pictureModel = new Model_Bildes();
        $all = $pictureModel->getAll();
        $this->dispatch('/admin/bildes/dzest/id/' . $all[0]['pictureId']);
        $this->assertRedirectTo('/admin/bildes');


    }


}
