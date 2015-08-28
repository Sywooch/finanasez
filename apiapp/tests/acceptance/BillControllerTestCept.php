<?php

$I = new AcceptanceTester($scenario);

$username = 'victor';
$password = 'victor';
$userId   = 1;

$I->wantTo('check if only auth users can access controller actions');
$I->sendGET('/bill');
$I->seeResponseCodeIs(401);
$I->sendDELETE('/bill/3');
$I->seeResponseCodeIs(401);
$I->sendPOST('/bill', ['name'=>'test', 'money'=>333]);
$I->seeResponseCodeIs(401);
$I->sendPUT('/bill/3', ['name'=>'new test']);
$I->seeResponseCodeIs(401);

$I->wantTo('authorize');
$I->sendPOST('/user/login', ['username'=> $username, 'password'=>$password]);
$I->seeResponseCodeIs(200);
$json = json_decode($I->grabResponse());
$authKey = $json->data->response->access_token;
$I->amBearerAuthenticated($authKey);


$I->wantTo('create new bill without name field');
$I->sendPOST('/bill', ['user_id'=>$userId ]);
$I->seeResponseCodeIs(406);
$I->wantTo('create new bill without user_id field');
$I->sendPOST('/bill', []);
$I->seeResponseCodeIs(406);
$I->wantTo('create new bill with not existing user_id field');
$I->sendPOST('/bill', ['user_id'=>9999999]);
$I->seeResponseCodeIs(406);

$I->wantTo('create new bill where money is not number');
$I->sendPOST('/bill', ['user_id'=>$userId , 'name'=>'testtest', 'money'=>'THIS IS A STRING']);
$I->seeResponseCodeIs(406);

$I->wantTo('create new bill with valid credentials');
$I->sendPOST('/bill', ['user_id'=>$userId , 'name'=>'testtest']);
$I->seeResponseCodeIs(201);
$json = json_decode($I->grabResponse());
$newBillId = $json->data->response->id;
$I->assertNotEmpty($newBillId);
$I->wantTo('create new bill with the same name');
$I->sendPOST('/bill', ['user_id'=>$userId , 'name'=>'testtest']);
$I->seeResponseCodeIs(406);

$I->wantTo('update unknown bill');
$I->sendPUT('/bill/9999999', ['name'=>'new name', 'money'=>'9000']);
$I->seeResponseCodeIs(404);
$I->wantTo('ensure bill really updated');
$I->sendPUT('/bill/'.$newBillId, ['name'=>'new name', 'money'=>'9000']);
$I->seeResponseCodeIs(200);
$json = json_decode($I->grabResponse());
$I->assertTrue('new name' === $json->data->response->name);

$I->wantTo('ensure index bill not show unknown bill id');
$I->sendGET('/bill/999999999');
$I->seeResponseCodeIs(404);
$I->wantTo('ensure index page works for EXISTING bill id');
$I->sendGET('/bill/'.$newBillId);
$I->seeResponseCodeIs(200);
$I->assertTrue('new name' === $json->data->response->name);
$I->wantTo('ensure index page will show new bill id');
$I->sendGET('/bill');
$I->seeResponseCodeIs(200);
$json = json_decode($I->grabResponse());
$I->assertGreaterThan(0, count(array_filter($json->data->response, function($el) use ($newBillId) {
    return $el->id == $newBillId;
})));

$I->wantTo('try to delete unknown bill');
$I->sendDELETE('/bill/99999999');
$I->seeResponseCodeIs(404);
$I->wantTo('delete really existing bill');
$I->sendDELETE('/bill/'.$newBillId);
$I->seeResponseCodeIs(200);
