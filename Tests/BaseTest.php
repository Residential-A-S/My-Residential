<?php

namespace Tests;

use PDO;
use PHPUnit\Framework\TestCase;
use src\Controllers\Api\AuthController as AuthController;
use src\Controllers\Api\UserController;
use src\Core\NativeSession;
use src\Core\Request;
use src\Factories\UserFactory;
use src\Repositories\UserRepository;
use src\Services\AuthService;
use src\Services\UserService;

class BaseTest extends TestCase
{
    protected PDO $db;
    protected array $session = [];
    protected NativeSession $nativeSession;

    protected UserRepository $userRepo;
    protected AuthService $authService;
    protected UserService $userService;
    protected AuthController $authCtrl;
    protected UserController $userController;

    protected function setUp(): void
    {
        parent::setUp();
        require_once __DIR__.'/../vendor/autoload.php';
        $this->db = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $this->nativeSession = new NativeSession($this->session);

        $userFactory = new UserFactory();

        // Initialize repositories
        $this->userRepo = new UserRepository($this->db);

        // Initialize services
        $this->authService = new AuthService($this->nativeSession, $this->userRepo, $userFactory);
        $this->userService = new UserService($this->authService, $this->userRepo, $userFactory);

        $this->authCtrl = new AuthController($this->authService);
        $this->userController = new UserController($this->userService);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->exec("TRUNCATE TABLE users");
    }

    protected function registerUser(array $userData): void {
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

    protected function loginUser(array $userData): void {
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