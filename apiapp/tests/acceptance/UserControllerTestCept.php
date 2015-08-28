<?php

$I = new AcceptanceTester($scenario);

$username = 'victor';
$password = 'victor';

// check /login route

$I->wantTo('ensure that dashboard with invalid credintials will not shown');
$I->sendGET('/user/dashboard');
$I->seeResponseCodeIs(401);

$I->wantTo('auth with empty credentials');
$I->sendPOST('/user/login', [ ]);
$I->seeResponseCodeIs(422);
$I->seeResponseContains('cannot be blank');

$I->wantTo('auth with invalid credentials');
$I->sendPOST('/user/login', ['username'=> 'test', 'password'=>'test']);
$I->seeResponseCodeIs(422);
$I->seeResponseContains('Incorrect');

$I->wantTo('auth with valid credentials');
$I->sendPOST('/user/login', ['username'=> $username, 'password'=>$password]);
$I->seeResponseCodeIs(200);
$json = json_decode($I->grabResponse());
$authKey = $json->data->response->access_token;
$lifeTime = $json->data->response->life_time;


$I->wantTo('ensure that login page returns access_token and life_time params');
$I->assertNotEmpty($authKey);
$I->assertNotEmpty($lifeTime);

// check /register route

$I->wantTo('register w.o. credentials');
$I->sendPOST('/user/signup', []);
$I->seeResponseCodeIs(406);

$I->wantTo('register with existing username');
$I->sendPOST('/user/signup', ['username'=>$username, 'password'=>'123456']);
$I->seeResponseCodeIs(406);

$I->wantTo('register with valid credentials');
$I->sendPOST('/user/signup', ['username'=>$username.(mt_rand(100,1000)), 'password'=>$password]);
$I->seeResponseCodeIs(201);
$json=json_decode($I->grabResponse());

$I->wantTo('ensure that new user can authorize');
$I->sendPOST('/user/login', ['username'=>$json->data->response->username,
                             'password'=>$json->data->response->password]);
$I->seeResponseContains('access_token');
$I->seeResponseCodeIs(200);

//check auth_key params

$I->wantTo('check if queryParamAuth work');
$I->sendGET('/user/dashboard', ['token'=>$authKey]);
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson(['access_token'=>$authKey, 'username'=>$username]);

$I->wantTo('check if HttpBearerAuth work');
$I->amBearerAuthenticated($authKey);
$I->sendGET('/user/dashboard');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson(['access_token'=>$authKey, 'username'=>$username]);

