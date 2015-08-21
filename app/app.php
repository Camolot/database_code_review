<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";

    $app = new Silex\Application();
    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=best_brands';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //Home page
    $app->get("/", function() use ($app){
      return $app['twig']->render('index.html.twig', array('stores' => Store::getAll()));
    });

    $app->post("/stores", function() use ($app) {
      $store - new Store($_POST['name']);
      $store->save();
      return $app['twig']->render('index.html.twig', array('stores' => Store::getAll()));
    });

    $app->delete("/stores/{id}", function(id) use $app) {
      $store = Store::find($id);
      $store->delete();
      return $app['twig']->render('index.html.twig', array('stores' => Store::getAll()));
    });

    $app->post("/delete_brands", function() use ($app) {
      Brand::deleteAll();
      return $app['twig']->render('index.html.twig', array('stores' => Store::getAll()));
    });

    $app->post("/delete_store", function() use ($app) {
      Store::deleteAll();
      return $app['twig']->render('index.html.twig', array('stores' => Store::getAll()));
    });

    //brands page
    $app->patch("/stores/{id}", function(id) use ($app) {
      $name = $_POST['name'];
      $store = Store::find($id);
      $store->update($name);
      return $app['twig']->render('store.html.twig'. array('store' => $store, 'brands'=> $store->getBrands()));
    });

    $app->post("/brands", function() use ($app) {
      $name - $_POST['name'];
      $description = $_POST['description'];
      $address = $_POST['address'];
      $brand = new Brand($name, $id = null, $store_id, $description, $address);
      $brand->save();
      $store = Store::find($store_id);
      return $app['twig']->render('store.html.twig', array('store' => $store, 'brands' => $store->getBrands()));
    });

    //Store_edit page
    $app->get("/stores/{id}/edit", function($id) use ($app) {
      $store = Store::find($id);
      return $app['twig']->render('search.html.twig', array('search' => $search, 'search_term' => $_GET['search']));
    });

    //Search result page
    $app->get("/search", function() use ($app) {
      $search = Brand::search($_GET['search']);
      return $app['twig']->render('search.html.twig', array('search' => $search, 'search_term' => $_GET['search']));
    });

  return $app;
  
?>
