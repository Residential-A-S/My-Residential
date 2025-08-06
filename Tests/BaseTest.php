<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use src\Controllers\Api\AuthController as AuthController;
use src\Controllers\Api\UserController;
use src\Core\NativeSession;
use src\Core\Request;
use src\Exceptions\AuthenticationException;
use src\Exceptions\ResponseException;
use src\Exceptions\ServerException;
use src\Exceptions\UserException;
use src\Exceptions\ValidationException;
use src\Factories\UserFactory;
use src\Repositories\UserOrganizationRepository;
use src\Repositories\UserRepository;
use src\Services\AuthService;
use src\Services\MailService;
use src\Services\UserService;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseTest extends TestCase
{
    protected PDO $db;
    protected array $session = [];
    protected NativeSession $nativeSession;
    protected Environment $twig;

    protected UserRepository $userRepo;
    protected UserOrganizationRepository $userOrgR;
    protected AuthService $authService;
    protected UserService $userService;
    protected MailService $mailService;
    protected AuthController $authCtrl;
    protected UserController $userController;

    protected function setUp(): void
    {
        parent::setUp();
        require_once __DIR__ . '/../vendor/autoload.php';
        $this->db = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // Clear tables
        $this->db->exec("DELETE FROM users WHERE true");

        $loader = new FilesystemLoader(__DIR__ . '/../src/Views');
        $twig = new Environment($loader, [
            false,//'cache' => __DIR__ . '/../../storage/cache/twig',
            'debug' => true,
        ]);

        $this->nativeSession = new NativeSession($this->session);

        $userFactory = new UserFactory();

        // Initialize repositories
        $this->userRepo = new UserRepository($this->db, $userFactory);
        $this->userOrgR = new UserOrganizationRepository($this->db);

        // Initialize services
        $this->authService = new AuthService($this->nativeSession, $this->userRepo, $this->userOrgR);
        $this->userService = new UserService($this->authService, $this->userRepo, $userFactory);
        $this->mailService = new MailService($twig);

        $this->authCtrl = new AuthController($this->authService);
        $this->userController = new UserController($this->userService);
    }

    /**
     * @param array $userData
     * @throws ResponseException
     * @throws ValidationException
     * @throws ServerException
     * @throws UserException
     */
    protected function registerUser(array $userData): void
    {
        $request = new Request(
            "POST",
            "/register",
            [],
            $userData,
            [],
            [],
            [],
            $this->nativeSession
        );
        $this->authCtrl->register($request);
    }

    /**
     * @param array $userData
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws ResponseException
     */
    protected function loginUser(array $userData): void
    {
        $request = new Request(
            "POST",
            "/login",
            [],
            $userData,
            [],
            [],
            [],
            $this->nativeSession
        );
        $this->authCtrl->login($request);
    }
}
