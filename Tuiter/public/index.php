<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

session_start();

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) return false;
}

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$mongoconn = new \MongoDB\Client("mongodb://localhost");
$userService = new \Tuiter\Services\UserService($mongoconn->tuiter->users);
$postService = new \Tuiter\Services\PostService($mongoconn->tuiter->posts);
$likeService = new \Tuiter\Services\LikeService($mongoconn->tuiter->likes);
$followService = new \Tuiter\Services\FollowService($mongoconn->tuiter->follows, $userService);
$loginService = new \Tuiter\Services\LoginService($userService);


$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) use ($twig) {
    
    $template = $twig->load('index.html');

    $response->getBody()->write(
        $template->render(['name' => 'Dario'])
    );
    return $response;
});

$app->get('/contacto', function (Request $request, Response $response, array $args) use ($twig) {
    
    $template = $twig->load('contacto.html');

    $response->getBody()->write(
        $template->render(['name' => 'Dario'])
    );
    return $response;
});

$app->run();