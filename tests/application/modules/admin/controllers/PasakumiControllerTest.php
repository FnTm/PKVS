<?php
/**
 *
 */
class Admin_PasakumiControllerTest extends ControllerTestCase
{
    /**
     *
     */
    public function testIndex()
    {
        $this->dispatch('/admin/pasakumi');
        $this->assertAction("index");
        $this->assertController("pasakumi");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }


    public function testPievienot()
    {
        $this->dispatch('/admin/pasakumi/pievienot');
        $this->assertAction("pievienot");
        $this->assertController("pasakumi");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }

    public function testPievienotWithSuccess()
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

        $this->dispatch('/admin/pasakumi/pievienot');

        $this->assertRedirectTo('/admin/pasakumi');
    }

    public function testPievienotWithoutSuccess()
    {
        $this->request->setMethod('POST')
            ->setPost(array(
            'pasakumsDescription' => '43215',
            'pasakumsTime' => date("Y-m-d H:i:s"),
            'pasakumsLocation' => 'sdf'
        ));

        $this->dispatch('/admin/pasakumi/pievienot');
        $this->assertAction("pievienot");
        $this->assertController("pasakumi");
    }

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

    public function testDzestWithoutId()
    {
        $this->dispatch('/admin/pasakumi/dzest/id/');
        $this->assertRedirectTo('/admin/pasakumi');
    }

    public function testDzestWithWrongId()
    {
        $this->dispatch('/admin/pasakumi/dzest/id/0');
        $this->assertRedirectTo('/admin/pasakumi');
    }

    public function testDzest()
    {
        $pasakumiModel = new Model_Pasakumi();
        $all = $pasakumiModel->getAllPasakumi();
        $this->dispatch('/admin/pasakumi/dzest/id/' . $all[0]['pasakumsId']);
        $this->assertRedirectTo('/admin/pasakumi');
    }

}
