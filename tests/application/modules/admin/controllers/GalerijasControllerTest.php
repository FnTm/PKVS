<?php
/**
 *
 */
class Admin_GalerijasControllerTest extends ControllerTestCase
{
    /**
     *
     */
    public function testIndex()
    {
        $this->dispatch('/admin/galerijas');
        $this->assertAction("index");
        $this->assertController("galerijas");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }

    /**
     * @todo implement checking if a form is present
     */
    public function testPievienot()
    {
        $this->dispatch('/admin/galerijas/pievienot');
        $this->assertAction("pievienot");
        $this->assertController("galerijas");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }

    public function testPievienotWithSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'galleryTitle' => 'user123',
            'galleryDescription' => '43215'
        ));

        $this->dispatch('/admin/galerijas/pievienot');

        $this->assertRedirectTo('/admin/galerijas');
    }

    public function testPievienotWithoutSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'galleryDescription' => '43215'
        ));

        $this->dispatch('/admin/galerijas/pievienot');
        $this->assertAction("pievienot");
        $this->assertController("galerijas");
    }

    public function testRedigetWithSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'galleryTitle' => 'user1234',
            'galleryDescription' => '432155'
        ));
        $galleryModel = new Model_Galerijas();
        $all = $galleryModel->getAll();
        $this->dispatch('/admin/galerijas/rediget/id/' . $all[0]['galleryId']);

        $this->assertRedirectTo('/admin/galerijas');
    }

    public function testRedigetWithoutSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'galleryDescription' => '43215'
        ));

        $galleryModel = new Model_Galerijas();
        $all = $galleryModel->getAll();
        $this->dispatch('/admin/galerijas/rediget/id/' . $all[0]['galleryId']);
        $this->assertAction("rediget");
        $this->assertController("galerijas");
    }

    public function testRedigetWithoutId()
    {
        $this->dispatch('/admin/galerijas/rediget/id/');
        $this->assertRedirectTo('/admin/galerijas');
    }

    public function testRedigetGetWithoutSubmit()
    {
        $galleryModel = new Model_Galerijas();
        $all = $galleryModel->getAll();
        $this->dispatch('/admin/galerijas/rediget/id/' . $all[0]['galleryId']);
        $this->assertAction("rediget");
        $this->assertController("galerijas");
    }

    public function testDzestWithoutId()
    {
        $this->dispatch('/admin/galerijas/dzest/id/');
        $this->assertRedirectTo('/admin/galerijas');
    }
    public function testDzest()
    {
        $galleryModel = new Model_Galerijas();
        $all = $galleryModel->getAll();
        $this->dispatch('/admin/galerijas/dzest/id/' . $all[0]['galleryId']);
        $this->assertRedirectTo('/admin/galerijas');
    }
}
