<?php
class GalerijasControllerTest extends ControllerTestCase
{
    public function testIndexWithResponseCodeOf200()
    {
        $this->dispatch('/galerijas');
        $this->assertAction("index");
        $this->assertController("galerijas");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());

    }

    public function testSkatitWithId()
    {
        $galleryModel = new Model_Galerijas();
        $all = $galleryModel->getAll();
        if (count($all) > 0) {
            $this->dispatch('/galerijas/skatit/id/' . $all[0]['galleryId']);
            $this->assertAction("skatit");
            $this->assertController("galerijas");
            $this->assertResponseCode(200,
                'The response code is ' . $this->getResponse()
                    ->getHttpResponseCode());
        }

    }

    public function testSkatitWithOutId()
    {
        $this->dispatch('/galerijas/skatit/id/');
        $this->assertRedirectTo("/galerijas");
    }
}
