<?php 
	session_start();
	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	use \Hcode\Model\User;

	$app = new Slim();

	$app->config('debug', true);

	$app->get('/', function() {
		
		$page = new Page();
		$page->setTpl("index");

	});

	$app->get('/Admin', function() {

		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("index");

	});

	$app->get('/Admin/login', function() {
		
		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);
		$page->setTpl("login");

	});

	$app->post('/Admin/login', function() {
		
		User::login($_POST["login"], $_POST["password"]);
		
		header("Location: /Admin");
		exit;
	});

	$app->get('/Admin/logout', function() {

		User::logout();

		header("Location: /Admin/login");
		exit;
	});

	$app->get("/Admin/users", function(){

		User::verifyLogin();

		$users = User::listAll();

		$page = new PageAdmin();
		
		$page->setTpl("users", array("users"=>$users));

	});

	$app->get("/Admin/users/create", function(){

		/*User::verifyLogin();

		$page = new PageAdmin();
		
		$page->setTpl("users-create");*/
		User::verifyLogin();

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

		$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

		"cost"=>12

		]);

		$user->setData($_POST);

		$user->save();

		header("Location: /Admin/users");
		exit;

	});

	$app->get("/Admin/users/:iduser/delete", function($iduser){

		User::verifyLogin();		
	});

	$app->get("/Admin/users/:iduser", function($iduser){

		User::verifyLogin();

		$page = new PageAdmin();
		
		$page->setTpl("users-update");

	});

	$app->post("/Admin/users/create", function(){

		User::verifyLogin();		

	});

	$app->post("/Admin/users/:iduser", function($iduser){

		User::verifyLogin();		

	});	

	$app->run();

 ?>