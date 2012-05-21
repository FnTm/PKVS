<?php
/**
 *
 * @group Maksajumi
 */
class Admin_MaksajumiControllerTest extends ControllerTestCase
{
    /**
     *
     */
    public function testIndex()
    {
        $this->dispatch('/admin/maksajumi');
        $this->assertAction("index");
        $this->assertController("maksajumi");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }


    public function testPievienot()
    {
        $this->dispatch('/admin/maksajumi/pievienot-maksajumu');
        $this->assertAction("pievienot-maksajumu");
        $this->assertController("maksajumi");
        $this->assertResponseCode(200,
            'The response code is ' . $this->getResponse()
                ->getHttpResponseCode());
    }

    public function testPievienotWithSuccess()
    {
        $userModel = new Model_Users();
        $users = $userModel->getDraugiemUser('72275');
        $this->request->setMethod('POST')
            ->setPost(array(
            'maksajumsTitle' => 'user123',
            'maksajumsValue' => '3',
            'maksajumsUserId' => $users->userId,
            'maksajumsCompleted' => '1'
        ));

        $this->dispatch('/admin/maksajumi/pievienot-maksajumu');

        $this->assertRedirectTo('/admin/maksajumi');
    }
    public function testPievienotVairakiemWithSuccess()
    {
        $userModel = new Model_Users();
        $users = $userModel->getDraugiemUser('72275');
        $postArray=array(
            'maksajumsTitle' => 'user123',
            'maksajumsValue' => '3',
            'user_'.$users->userId=>1,
            'maksajumsCompleted' => '1'
        );
        $this->request->setMethod('POST')
            ->setPost($postArray);
        $this->dispatch('/admin/maksajumi/pievienot-maksajumu-vairakiem');

        $this->assertRedirectTo('/admin/maksajumi');
    }
    public function testPievienotVairakiemWithoutSuccess()
    {
        $userModel = new Model_Users();
        $users = $userModel->getDraugiemUser('72275');
        $postArray=array(
            'maksajumsValue' => '3',
            'user_'.$users->userId=>1,
            'maksajumsCompleted' => '1'
        );
        $this->request->setMethod('POST')
            ->setPost($postArray);
        $this->dispatch('/admin/maksajumi/pievienot-maksajumu-vairakiem');

        $this->assertController('maksajumi');
        $this->assertAction('pievienot-maksajumu-vairakiem');
    }

    public function testPievienotWithoutSuccess()
    {
        $userModel = new Model_Users();
        $users = $userModel->getDraugiemUser('72275');
        $this->request->setMethod('POST')
            ->setPost(array(
            'maksajumsValue' => '3',
            'maksajumsUserId' => $users->userId,
            'maksajumsCompleted' => '1'
        ));

        $this->dispatch('/admin/maksajumi/pievienot-maksajumu');

        $this->assertAction("pievienot-maksajumu");
        $this->assertController("maksajumi");
    }

    public function testRedigetWithSuccess()
    {
        $maksajumiModel = new Model_Maksajumi();
        $maksajumi=$maksajumiModel->getMaksajumi($maksajumiModel->_primaryKey." desc");
        $this->request->setMethod('POST')
            ->setPost(array(
            'maksajumsTitle' => 'user123',
            'maksajumsCompleted' => '1'
        ));
        $this->dispatch('/admin/maksajumi/rediget-maksajumu/id/' . $maksajumi[0][$maksajumiModel->_primaryKey]);

        $this->assertRedirectTo('/admin/maksajumi');
    }

    public function testRedigetWithoutSuccess()
    {
        $maksajumiModel = new Model_Maksajumi();
        $maksajumi=$maksajumiModel->getMaksajumi($maksajumiModel->_primaryKey." desc");
        $this->request->setMethod('POST')
            ->setPost(array(
            'maksajumsCompleted' => '1'
        ));
        $this->dispatch('/admin/maksajumi/rediget-maksajumu/id/' . $maksajumi[0][$maksajumiModel->_primaryKey]);
        $this->assertAction("rediget-maksajumu");
        $this->assertController("maksajumi");
    }

    public function testRedigetWithoutSubmit()
    {
        $maksajumiModel = new Model_Maksajumi();
        $maksajumi=$maksajumiModel->getMaksajumi($maksajumiModel->_primaryKey." desc");
        $this->dispatch('/admin/maksajumi/rediget-maksajumu/id/' . $maksajumi[0][$maksajumiModel->_primaryKey]);
        $this->assertAction("rediget-maksajumu");
        $this->assertController("maksajumi");
    }

    public function testRedigetWithoutId()
    {
        $this->dispatch('/admin/maksajumi/rediget-maksajumu/id/');
        $this->assertRedirectTo('/admin/maksajumi');
    }
    public function testApstiprinatWithoutId()
    {
        $this->dispatch('/admin/maksajumi/apstiprinat/id/');
        $this->assertRedirectTo('/admin/maksajumi');
    }

    public function testApstiprinatWithWrongId()
    {

        $this->dispatch('/admin/maksajumi/apstiprinat/id/0');
        $this->assertRedirectTo('/admin/maksajumi');
    }

    public function testApstiprinat()
    {
        $maksajumiModel = new Model_Maksajumi();
        $maksajumi=$maksajumiModel->getMaksajumi($maksajumiModel->_primaryKey." desc");

        $this->dispatch('/admin/maksajumi/apstiprinat/id/' . $maksajumi[0][$maksajumiModel->_primaryKey]);

        $this->assertRedirectTo('/admin/maksajumi');
    }
    public function testNoraiditWithoutId()
    {
        $this->dispatch('/admin/maksajumi/noraidit/id/');
        $this->assertRedirectTo('/admin/maksajumi');
    }

    public function testNoraiditWithWrongId()
    {

        $this->dispatch('/admin/maksajumi/noraidit/id/0');
        $this->assertRedirectTo('/admin/maksajumi');
    }

    public function testNoraidit()
    {
        $maksajumiModel = new Model_Maksajumi();
        $maksajumi=$maksajumiModel->getMaksajumi($maksajumiModel->_primaryKey." desc");

        $this->dispatch('/admin/maksajumi/noraidit/id/' . $maksajumi[0][$maksajumiModel->_primaryKey]);

        $this->assertRedirectTo('/admin/maksajumi');
    }
    public function testDzestWithoutId()
    {
        $this->dispatch('/admin/maksajumi/dzest/id/');
        $this->assertRedirectTo('/admin/maksajumi');
    }

    public function testDzestWithWrongId()
    {

        $this->dispatch('/admin/maksajumi/dzest/id/0');
        $this->assertRedirectTo('/admin/maksajumi');
    }

    public function testDzest()
    {
        $maksajumiModel = new Model_Maksajumi();
        $maksajumi=$maksajumiModel->getMaksajumi($maksajumiModel->_primaryKey." desc");

        $this->dispatch('/admin/maksajumi/dzest/id/' . $maksajumi[0][$maksajumiModel->_primaryKey]);

        $this->assertRedirectTo('/admin/maksajumi');
    }

}
