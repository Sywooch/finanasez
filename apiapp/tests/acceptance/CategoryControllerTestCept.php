<?php

$I = new AcceptanceTester($scenario);

$username = 'victor';
$password = 'victor';
$userId   = 1;

$I->wantTo('check if only auth users can access controller actions');
$I->sendGET('/category');
$I->seeResponseCodeIs(401);
$I->sendDELETE('/category/3');
$I->seeResponseCodeIs(401);
$I->sendPOST('/category', ['name'=>'test', 'type'=>333]);
$I->seeResponseCodeIs(401);
$I->sendPUT('/category/3', ['name'=>'new test']);
$I->seeResponseCodeIs(401);

$I->wantTo('authorize');
$I->sendPOST('/user/login', ['username'=> $username, 'password'=>$password]);
$I->seeResponseCodeIs(200);
$json = json_decode($I->grabResponse());
$authKey = $json->data->response->access_token;
$I->amBearerAuthenticated($authKey);


$I->wantTo('create new category without name field');
$I->sendPOST('/category', ['user_id'=>$userId ]);
$I->seeResponseCodeIs(406);
$I->wantTo('create new category without user_id field');
$I->sendPOST('/category', []);
$I->seeResponseCodeIs(406);
$I->wantTo('create new category with invalid type param (not in [in, out]) ');
$I->sendPOST('/category', ['user_id'=>$userId , 'name'=>'testtest', 'type'=>'SOME STUFF']);
$I->seeResponseCodeIs(406);
$I->wantTo('create new category with valid credentials');
$I->sendPOST('/category', ['user_id'=>$userId , 'name'=>'testtest', 'type'=>'in']);
$I->seeResponseCodeIs(201);
$json = json_decode($I->grabResponse());
$newCategoryId = $json->data->response->id;
$I->assertNotEmpty($newCategoryId);
$I->wantTo('create new category with the same name');
$I->sendPOST('/category', ['user_id'=>$userId , 'name'=>'testtest']);
$I->seeResponseCodeIs(406);

$I->wantTo('update unknown category');
$I->sendPUT('/category/9999999', ['name'=>'new name', 'type'=>'out']);
$I->seeResponseCodeIs(404);
$I->wantTo('ensure category really updated');
$I->sendPUT('/category/'.$newCategoryId, ['name'=>'new name', 'type'=>'out']);
$I->seeResponseCodeIs(200);
$json = json_decode($I->grabResponse());
$I->assertTrue('new name' === $json->data->response->name);

$I->wantTo('ensure index category not show unknown category id');
$I->sendGET('/category/999999999');
$I->seeResponseCodeIs(404);
$I->wantTo('ensure index page works for EXISTING category id');
$I->sendGET('/category/'.$newCategoryId);
$I->seeResponseCodeIs(200);
$I->assertTrue('new name' === $json->data->response->name);
$I->wantTo('ensure index page will show new category id');
$I->sendGET('/category');
$I->seeResponseCodeIs(200);
$json = json_decode($I->grabResponse());
$I->assertGreaterThan(0, count(array_filter($json->data->response, function($el) use ($newCategoryId) {
    return $el->id == $newCategoryId;
})));

$I->wantTo('try to delete unknown category');
$I->sendDELETE('/category/99999999');
$I->seeResponseCodeIs(404);
$I->wantTo('delete really existing category');
$I->sendDELETE('/category/'.$newCategoryId);
$I->seeResponseCodeIs(200);
$I->wantTo('ensure index not shown deleted category');
$I->sendGET('/category');
$json = json_decode($I->grabResponse());
$I->assertEquals(0, count(array_filter($json->data->response, function($el) use ($newCategoryId) {
    return $el->id == $newCategoryId;
})));