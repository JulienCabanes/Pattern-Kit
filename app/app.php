<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

// Conf
$app['root_dir'] = dirname(__DIR__);
$app['tmp_dir'] = $app['root_dir'].'/tmp';
$app['cache_dir'] = $app['tmp_dir'].'/cache';
$app['themes_dir'] = $app['root_dir'].'/themes';
$app['env'] = isset($_GET['env']) ? $_GET['env'] : 'dev';
$app['debug'] = $app['env'] === 'dev';

// Twig registration
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.options'  => array(
        'strict_variables' => false,
        'debug' => $app['debug'],
        'cache' => false
    )
));

// Twig extension
// TODO : better link function
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addGlobal('assets_dir', '../assets');
    $twig->addGlobal('img_dir', '../assets/img');
    $twig->addExtension(new Pattern\Twig_Extension_Helper());
    $twig->addFunction(new Twig_SimpleFunction('link', function($path) use ($app) {
        return '../../'.$app['theme']->getName().'/'.$path.'.html';
    }));
    return $twig;
}));


/**
 * Routing start
 * @todo http://silex.sensiolabs.org/doc/organizing_controllers.html
 */

/**
 * Index
 * @todo Something useful
 */
$app->match('/', function(Application $app) {
    return '<h1>Pattern Kit</h1>';
});

/**
 * Assets proxy
 * @todo Find a better way for doing this...
 */
$app->match('/{theme_name}/{asset_folder}/{asset_uri}', function($theme_name, $asset_folder, $asset_uri, Application $app) {
    $basepath = realpath($app['themes_dir'].'/'.$theme_name.'/'.$asset_folder.'/');
    $asset_path = realpath($basepath.'/'.$asset_uri);
    
    if(strpos($asset_path, $basepath) !== false && is_readable($asset_path)) {
        $mime = '';
        $exp = explode('.', $asset_path);
        $ext = array_pop($exp);
        // Light API
        if($ext === 'php') {
            // default http header
            header('Content-type: application/json');
            require($asset_path);
            die();
        }

        $mimes = array(
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/png',
            'svg' => 'image/svg+xml'
        );
        if(isset($mimes[$ext])) {
            $mime = $mimes[$ext];
        } else {
            $finfo = new finfo(FILEINFO_MIME);
            $mime = $finfo->file($asset_path);
        }
        
        return new Response(file_get_contents($asset_path), 200, array(
            'Content-type' => $mime
        ));
    }
   
    return new Response('Assert 404', 404);
})
->assert('asset_folder', 'assets|data')
->assert('asset_uri', '.*');


/**
 * Pattern routing
 */
$app->match('/{theme_name}/{pattern_type}/{pattern_name}.html', function($theme_name, $pattern_type, $pattern_name, Application $app) {
    // Create the theme
    $app['theme'] = new Pattern\Theme($app['themes_dir'].'/'.$theme_name);

    // Fallback theme folders
    $app['twig.loader']->addLoader(new Pattern\Twig_Loader($app['theme']->getPaths()));

    // Globalize datas
    foreach($app['theme']->getData() as $name => $value) {
        $app['twig']->addGlobal($name, $value);
    }
    
    // Template rendering
    $pattern_content = $app['twig']->render($pattern_type.'/'.$pattern_name);

    // If the route is not for a page, put the content inside an HTML page
    if($pattern_type === 'pages' || $pattern_type === 'pages') {
        return $pattern_content;
    } else {
        return $app['twig']->render('templates/default', array(
            'body' => $pattern_content
        ));
    }
})
->assert('pattern_name', '.*');
