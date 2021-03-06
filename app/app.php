<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Tamagotchi.php";

    session_start();
    if (empty($_SESSION['list_of_tamagotchis'])) {
        $_SESSION['list_of_tamagotchis'] = array();
    }

    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    //routes

    $app->get("/", function() use ($app) {
        return $app['twig']->render('home.html.twig', array(
            'tamagotchis' => Tamagotchi::getAll()
        ));
    });

    $app->post('/add_gotchi', function() use ($app) {
        $agotchi = new Tamagotchi($_POST['name']);
        $agotchi->save();

        return $app['twig']->render('home.html.twig', array(
            'tamagotchis' => Tamagotchi::getAll()
        ));
    });

    $app->get('/age', function() use ($app) {
        $tamagotchis = Tamagotchi::getAll();

        foreach ($tamagotchis as $key => $tamagotchi) {
            $tamagotchi->age();
        }

        return $app['twig']->render('home.html.twig', array(
            'tamagotchis' => Tamagotchi::getAll()
        ));
    });

    $app->post('/delete_all', function() use ($app) {
        Tamagotchi::deleteAll();

        return $app['twig']->render('home.html.twig', array(
            'tamagotchis' => Tamagotchi::getAll()
        ));
    });

    $app->post('/feed', function() use ($app) {
        foreach($_SESSION['list_of_tamagotchis'] as $key => $tamagotchi) {
            if ($tamagotchi->getName() == $_POST['name']) {
                $tamagotchi->feed();
            }
        }
        Tamagotchi::ageAll();

        return $app['twig']->render('home.html.twig', array(
            'tamagotchis' => Tamagotchi::getAll()
        ));
    });

    $app->post('/attend', function() use ($app) {
        foreach($_SESSION['list_of_tamagotchis'] as $key => $tamagotchi) {
            if ($tamagotchi->getName() == $_POST['name']) {
                $tamagotchi->attend();
            }
        }
        Tamagotchi::ageAll();

        return $app['twig']->render('home.html.twig', array(
            'tamagotchis' => Tamagotchi::getAll()
        ));
    });

    $app->post('/sleep', function() use ($app) {
        foreach($_SESSION['list_of_tamagotchis'] as $key => $tamagotchi) {
            if ($tamagotchi->getName() == $_POST['name']) {
                $tamagotchi->sleep();
            }
        }
        Tamagotchi::ageAll();

        return $app['twig']->render('home.html.twig', array(
            'tamagotchis' => Tamagotchi::getAll()
        ));
    });

    return $app;
?>
