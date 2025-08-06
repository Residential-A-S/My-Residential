<?php

namespace src\Core;

use src\Controllers\Api\UserController;
use src\Controllers\Web\HomeController;
use src\Enums\RouteName;
use src\Exceptions\ResponseException;
use src\Factories\IssueFactory;
use src\Factories\OrganizationFactory;
use src\Factories\PaymentFactory;
use src\Factories\PropertyFactory;
use src\Factories\RentalAgreementDocumentFactory;
use src\Factories\RentalAgreementFactory;
use src\Factories\RentalAgreementPaymentFactory;
use src\Factories\RentalUnitFactory;
use src\Factories\TenantFactory;
use src\Factories\UserFactory;
use src\Repositories\IssueRepository;
use src\Repositories\OrganizationRepository;
use src\Repositories\PaymentRepository;
use src\Repositories\PropertyRepository;
use src\Repositories\RentalAgreementDocumentRepository;
use src\Repositories\RentalAgreementPaymentRepository;
use src\Repositories\RentalAgreementRepository;
use src\Repositories\RentalUnitRepository;
use src\Repositories\TenantRepository;
use src\Repositories\UserOrganizationRepository;
use src\Repositories\UserRepository;
use src\Services\AuthService;
use src\Controllers\Web\LoginController as WebLoginController;
use src\Controllers\Api\AuthController as AuthController;
use PDO;
use src\Services\UserService;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final readonly class Application
{
    private function __construct(
        private Router $router
    ) {
    }

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

        // Initialize factories
        $issueF = new IssueFactory();
        $orgF = new OrganizationFactory();
        $payF = new PaymentFactory();
        $propF = new PropertyFactory();
        $raF = new RentalAgreementFactory();
        $raDocF = new RentalAgreementDocumentFactory();
        $raPayF = new RentalAgreementPaymentFactory();
        $ruF = new RentalUnitFactory();
        $tenantF = new TenantFactory();
        $userF = new UserFactory();

        // Initialize repositories
        $issueR = new IssueRepository($db, $issueF);
        $orgR = new OrganizationRepository($db, $orgF);
        $payR = new PaymentRepository($db, $payF);
        $propR = new PropertyRepository($db, $propF);
        $raR = new RentalAgreementRepository($db, $raF);
        $raDocR = new RentalAgreementDocumentRepository($db, $raDocF);
        $raPayR = new RentalAgreementPaymentRepository($db);
        $ruR = new RentalUnitRepository($db, $ruF);
        $tenantR = new TenantRepository($db, $tenantF);
        $userOrgR = new UserOrganizationRepository($db);
        $userR = new UserRepository($db, $userF);

        // Initialize services
        $authS = new AuthService($request->session, $userR, $userOrgR);
        $userS = new UserService($authS, $userR, $userF);
        //$orgS = new OrganizationService($orgR);

        // Initialize the Controllers
        $loginWebCtrl = new WebLoginController($twig);
        $homeWebCtrl = new HomeController($twig, $authS);

        $authCtrl = new AuthController($authS);
        $userCtrl = new UserController($userS);
        //$orgCtrl = new OrganizationController($orgS);

        // Initialize the router
        $router = new Router();
        $router->map(RouteName::Home, [$homeWebCtrl, 'show']);
        $router->map(RouteName::Login_GET, [$loginWebCtrl, 'show']);
        //$router->map(RouteNames::Properties, [$propertyCtrl, 'list']);

        $router->map(RouteName::Login_POST, [$authCtrl, 'login']);
        $router->map(RouteName::Logout, [$authCtrl, 'logout']);
        $router->map(RouteName::Register, [$authCtrl, 'register']);
        $router->map(RouteName::Forgot_Password, [$authCtrl, 'resetPassword']);
        $router->map(RouteName::User_Update, [$userCtrl, 'update']);
        $router->map(RouteName::User_Delete, [$userCtrl, 'delete']);

        //$router->map(RouteNames::Property_Create, [$propertyCtrl, 'create']);
        //$router->map(RouteNames::Property_Update, [$propertyCtrl, 'update']);
        //$router->map(RouteNames::Property_Delete, [$propertyCtrl, 'delete']);

        //$router->map(RouteNames::Organization_Create, [$organizationCtrl, 'create']);
        //$router->map(RouteNames::Organization_Update, [$organizationCtrl, 'update']);
        //$router->map(RouteNames::Organization_Delete, [$organizationCtrl, 'delete']);


        return new self($router);
    }

    /**
     * @throws ResponseException
     */
    public function handle(Request $request): Response
    {
        return $this->router->dispatch($request);
    }
}
