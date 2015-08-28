<?php
use \AcceptanceTester;

class OperationControllerTestCest
{
    protected $username = 'victor';
    protected $password = 'victor';
    protected $userId   = 1;
    protected $path     = '/operation';
    protected $authKey  = null;
    protected $newId    = null;

    public function _before(AcceptanceTester $I)
    {
        $I->wantTo('authorize');
        $I->sendPOST('/user/login', ['username'=> $this->username, 'password'=>$this->password]);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $this->authKey = $json->data->response->access_token;
        $I->amBearerAuthenticated($this->authKey);
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function _getDataArray()
    {


        // write valid params here!
        return [
            'amount'=>9000.99,
            'comment'=>'some comment',
            'bill_id'=>38,
            'category_id'=>25,
            'user_id' =>$this->userId
        ];
    }

    // tests
    public function testCreate(AcceptanceTester $I)
    {
        $I->wantTo('ensure that i can\'t create operation w.o. required fields');
        $I->sendPOST($this->path, []);
        $I->seeResponseCodeIs(406);

        $I->wantTo('ensure that foreign key constraints 2 bill_id work');
        $dataArray = $this->_getDataArray();
        $dataArray['bill_id'] = 999999;
        $I->sendPOST($this->path, $dataArray);
        $I->seeResponseCodeIs(406);

        $I->wantTo('ensure that foreign key constraints 2 category_id work');
        $dataArray = $this->_getDataArray();
        $dataArray['category_id'] = 999999;
        $I->sendPOST($this->path, $dataArray);
        $I->seeResponseCodeIs(406);

        $I->wantTo('take money from bill before new operation insert...');
        $I->sendGET('/bill/'.$dataArray['bill_id']);
        $json = json_decode($I->grabResponse());
        $I->assertNotEmpty($json->data->response);
        $billMoneyBefore = $json->data->response->money;
        $I->assertNotNull($billMoneyBefore);

        $I->wantTo('try to add operation with valid params');
        $dataArray = $this->_getDataArray();
        $I->sendPOST($this->path, $dataArray);
        print_r($I->grabResponse());
        $I->seeResponseCodeIs(201);
        $json = json_decode($I->grabResponse());
        $newId = $json->data->response->id;
        $I->assertNotEmpty($newId);
        $this->newId = $newId;

        $I->wantTo('take money from bill after new operation insert');
        $I->sendGET('/bill/'.$dataArray['bill_id']);
        $json = json_decode($I->grabResponse());
        $I->assertNotEmpty($json->data->response);
        $billMoneyAfter = $json->data->response->money;
        $I->assertNotNull($billMoneyAfter);
        $delta = abs($billMoneyAfter - $billMoneyBefore);
        $I->assertLessThan(0.01, abs($delta - $dataArray['amount']));



    }

    public function testIndex(AcceptanceTester $I)
    {
        $I->wantTo('ensure index page works');
        $I->sendGET($this->path);
        $I->seeResponseCodeIs(200);

        $I->wantTo('ensure index page shows our new operation');
        $json = json_decode($I->grabResponse());
        $newId = $this->newId;
        $I->assertEquals(1, count(array_filter($json->data->response, function ($el) use ($newId) {
            return $newId == $el->id;
        })));

        $I->wantTo('ensure /{id} page works');
        $I->sendGET($this->path.'/'.$this->newId);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $I->assertEquals($this->newId, $json->data->response->id);

        $I->wantTo('ensure that there is no operations in the future :D');
        $I->sendGET($this->path, ['time_from'=>time()+3600]);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $I->assertEquals(0, count($json->data->response));

        $I->wantTo('ensure that there is no operations at 1\'st jan 1970 :D');
        $I->sendGET($this->path, ['time_to'=>1]);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $I->assertEquals(0, count($json->data->response));

        $I->wantTo('ensure that timestamp filtering works');
        $I->sendGET($this->path, ['time_from' => 1, 'time_to'=>time()]);
        $jsonWithTs = json_decode($I->grabResponse(), true);
        $I->seeResponseCodeIs(200);
        $I->sendGET($this->path);
        $jsonWithoutTs = json_decode($I->grabResponse(), true);
        $I->seeResponseCodeIs(200);
        $I->wantTo('check if results with time_to=time() equals with simple get on /');
//        $I->assertEquals(count($jsonWithoutTs->data->response), count($jsonWithTs->data->response));
        $I->assertEquals(count($jsonWithoutTs), count($jsonWithTs));


        $I->wantTo('ensure relations works');
        $I->sendGET($this->path.'/'.$this->newId);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $bill = $json->data->response->bill;
        $category = $json->data->response->category;
        $I->assertNotEmpty($bill);
        $I->assertNotEmpty($category);
        $I->assertEquals($json->data->response->bill_id, $bill->id);
        $I->assertEquals($json->data->response->category_id, $category->id);


        $I->wantTo('ensure limit works');
        $I->sendGET($this->path, ['limit'=>1]);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $firstId = $json->data->response[0]->id;
        $I->assertEquals(1, count($json->data->response));
        $I->wantTo('ensure offset works');
        $I->sendGET($this->path, ['limit'=>1, 'offset'=>1]);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $secondId = $json->data->response[0]->id;
        $I->assertNotEmpty($firstId);
        $I->assertNotEmpty($secondId);
        // if offset works, then previous result row will not equals to new result row
        $I->assertNotEquals($firstId, $secondId);

    }

    public function testUpdate(AcceptanceTester $I)
    {
        $dataArray = $this->_getDataArray();
        $I->wantTo('update non-existing page');
        $I->sendPUT($this->path.'/999999', $dataArray);
        $I->seeResponseCodeIs(404);

        $I->wantTo('check if constraint violation 2 bill_id works');
        $dataArray['bill_id'] = 9999999;
        $I->sendPUT($this->path.'/'.$this->newId, $dataArray);
        $I->seeResponseCodeIs(406);
        $I->seeResponseContains('constraint');

        $I->wantTo('check if constraint violation 2 category_id works');
        $dataArray = $this->_getDataArray();
        $dataArray['category_id'] = 9999999;
        $I->sendPUT($this->path.'/'.$this->newId, $dataArray);
        $I->seeResponseCodeIs(406);
        $I->seeResponseContains('constraint');

        $I->wantTo('update operation with valid params');
        $dataArray = $this->_getDataArray();
        $dataArray['amount'] = '111';
        $dataArray['comment'] = 'blablabla';
        $I->sendPUT($this->path.'/'.$this->newId, $dataArray);
        $I->seeResponseCodeIs(200);
        $json = json_decode($I->grabResponse());
        $I->assertEquals(111, $json->data->response->amount);
        $I->assertEquals('blablabla', $json->data->response->comment);

    }

    public function testDelete(AcceptanceTester $I)
    {
        $I->wantTo('delete non-existing operation');
        $I->sendDELETE($this->path.'/999999');
        $I->seeResponseCodeIs(404);

        $I->wantTo('delete existing page');
        $I->sendDelete($this->path.'/'.$this->newId);
        $I->seeResponseCodeIs(200);

        $I->wantTo('ensure that deleted page will not shown in /{id}');
        $I->sendGET($this->path.'/'.$this->newId);
        $I->seeResponseCodeIs(404);

    }


}
