<?php



namespace src\Core;

use src\Controllers\Api\OrganizationController;
use src\Controllers\Api\PropertyController;
use src\Controllers\Web\HomeController;
use src\Enums\RouteNames;
use src\Repositories\OrganizationRepository;
use src\Repositories\PropertyRepository;
use src\Repositories\UserRepository;
use src\Services\AuthService;

use src\Controllers\Web\LoginController as WebLoginController;
use src\Controllers\Api\AuthController as AuthController;
use PDO;
use src\Services\OrganizationService;
use src\Services\PropertyService;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final readonly class Application
{
    private function __construct(
        private Router $router
    ) {}

    public static function bootstrap(Request $request): self
    {
        // Low-level initialization
        $db = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $twig = new Environment($loader, [
            false,//'cache' => __DIR__ . '/../../storage/cache/twig',
            'debug' => true,
        ]);

        // Initialize repositories
        $userRepo = new UserRepository($db);
        $propertyRepo = new PropertyRepository($db);
        $organizationRepo = new OrganizationRepository($db);

        // Initialize services
        $authService = new AuthService($request->session, $userRepo);
        $propertyService = new PropertyService($propertyRepo);
        $organizationService = new OrganizationService($organizationRepo);

        // Initialize the Controllers
        $loginWebCtrl = new WebLoginController($twig);
        $homeWebCtrl = new HomeController($twig, $authService);

        $authCtrl = new AuthController($authService);
        $propertyCtrl = new PropertyController($propertyService);
        $organizationCtrl = new OrganizationController($organizationService);

        // Initialize the router
        $router = new Router();
        $router->map(RouteNames::Home, [$homeWebCtrl, 'show']);
        $router->map(RouteNames::Login_GET, [$loginWebCtrl, 'show']);
        $router->map(RouteNames::Properties, [$propertyCtrl, 'list']);

        $router->map(RouteNames::Login_POST, [$authCtrl, 'login']);
        $router->map(RouteNames::Logout, [$authCtrl, 'logout']);
        $router->map(RouteNames::Register, [$authCtrl, 'register']);

        $router->map(RouteNames::Property_Create, [$propertyCtrl, 'create']);
        $router->map(RouteNames::Property_Update, [$propertyCtrl, 'update']);
        $router->map(RouteNames::Property_Delete, [$propertyCtrl, 'delete']);

        $router->map(RouteNames::Organization_Create, [$organizationCtrl, 'create']);
        $router->map(RouteNames::Organization_Update, [$organizationCtrl, 'update']);
        $router->map(RouteNames::Organization_Delete, [$organizationCtrl, 'delete']);


        return new self($router);
    }

    public function handle(Request $request): Response {
        return $this->router->dispatch($request);
    }
}
