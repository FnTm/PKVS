<?php
/**
 *
 */
class Admin_PasakumaTipiControllerTest extends ControllerTestCase
{
    /**
     *
     */
    public function testIndex()
    {
        $this->dispatch('/admin/pasakuma-tipi');
        $this->assertAction("index");
        $this->assertController("pasakuma-tipi");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }


    public function testPievienot()
    {
        $this->dispatch('/admin/pasakuma-tipi/pievienot');
        $this->assertAction("pievienot");
        $this->assertController("pasakuma-tipi");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }


    public function testPievienotWithSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'typeTitle' => 'user123',
            'typeDescription' => '43215',
            'typeParent' => 0
        ));

        $this->dispatch('/admin/pasakuma-tipi/pievienot');

        $this->assertRedirectTo('/admin/pasakuma-tipi');
    }

    public function testPievienotWithoutSuccess()
    {

        $this->request->setMethod('POST')
            ->setPost(array(
            'typeDescription' => '43215',
            'typeParent' => 0
        ));
        $this->dispatch('/admin/pasakuma-tipi/pievienot');
        $this->assertAction("pievienot");
        $this->assertController("pasakuma-tipi");
    }


    public function testRedigetWithSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'typeTitle' => 'user123',
            'typeDescription' => '43215',
            'typeParent' => 0
        ));
        $pasakumiModel = new Model_Pasakumi_Type();
        $all = $pasakumiModel->getAll();
        $this->dispatch('/admin/pasakuma-tipi/rediget/id/' . $all[0]['typeId']);

        $this->assertRedirectTo('/admin/pasakuma-tipi');
    }

    public function testRedigetWithoutSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'typeDescription' => '43215',
            'typeParent' => 0
        ));

        $pasakumiModel = new Model_Pasakumi_Type();
        $all = $pasakumiModel->getAll();
        $this->dispatch('/admin/pasakuma-tipi/rediget/id/' . $all[0]['typeId']);
        $this->assertAction("rediget");
        $this->assertController("pasakuma-tipi");
    }

    public function testRedigetWithoutSubmit()
    {
        $pasakumiModel = new Model_Pasakumi_Type();
        $all = $pasakumiModel->getAll();
        $this->dispatch('/admin/pasakuma-tipi/rediget/id/' . $all[0]['typeId']);
        $this->assertAction("rediget");
        $this->assertController("pasakuma-tipi");
    }

    public function testRedigetWithoutId()
    {
        $this->dispatch('/admin/pasakuma-tipi/rediget/id/');
        $this->assertRedirectTo('/admin/pasakuma-tipi');
    }

    public function testPointsWithoutSubmit()
    {
        $pasakumiModel = new Model_Pasakumi_Type();
        $all = $pasakumiModel->getAll();
        $this->dispatch('/admin/pasakuma-tipi/punkti/id/' . $all[0]['typeId']);
        $this->assertAction("punkti");
        $this->assertController("pasakuma-tipi");
    }
    public function testDzestWithoutId()
    {
        $this->dispatch('/admin/pasakuma-tipi/dzest/id/');
        $this->assertRedirectTo('/admin/pasakuma-tipi');
    }

    public function testDzestWithWrongId()
    {
        $this->dispatch('/admin/pasakuma-tipi/dzest/id/0');
        $this->assertRedirectTo('/admin/pasakuma-tipi');
    }

    public function testDzest()
    {
        $pasakumiModel = new Model_Pasakumi_Type();
        $all = $pasakumiModel->getAll();
        $this->dispatch('/admin/pasakuma-tipi/dzest/id/' . $all[0]['typeId']);
        $this->assertRedirectTo('/admin/pasakuma-tipi');
    }



}
