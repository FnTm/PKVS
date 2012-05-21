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

    /*
    public function testRedigetWithSuccess()
    {
        $categoryModel = new Model_Pasakumi_Type();
        $types = $categoryModel->getAll();
        $this->request->setMethod('POST')
            ->setPost(array(
            'pasakumsTitle' => 'user123',
            'pasakumsDescription' => '43215',
            'pasakumsTime' => date("Y-m-d H:i:s"),
            'pasakumsLocation' => 'sdf',
            'pasakumsCategory' => $types[0]['typeId']
        ));
        $pasakumiModel = new Model_Pasakumi();
        $all = $pasakumiModel->getAllPasakumi();
        $this->dispatch('/admin/pasakumi/rediget/id/' . $all[0]['pasakumsId']);

        $this->assertRedirectTo('/admin/pasakumi');
    }

    public function testRedigetWithoutSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'pasakumsDescription' => '43215'
        ));

        $pasakumiModel = new Model_Pasakumi();
        $all = $pasakumiModel->getAllPasakumi();
        $this->dispatch('/admin/pasakumi/rediget/id/' . $all[0]['pasakumsId']);
        $this->assertAction("rediget");
        $this->assertController("pasakumi");
    }

    public function testRedigetWithoutSubmit()
    {


        $pasakumiModel = new Model_Pasakumi();
        $all = $pasakumiModel->getAllPasakumi();
        $this->dispatch('/admin/pasakumi/rediget/id/' . $all[0]['pasakumsId']);
        $this->assertAction("rediget");
        $this->assertController("pasakumi");
    }

    public function testRedigetWithoutId()
    {
        $this->dispatch('/admin/pasakumi/rediget/id/');
        $this->assertRedirectTo('/admin/pasakumi');
    }
*/
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
