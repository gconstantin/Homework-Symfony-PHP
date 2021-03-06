<?php

namespace App\Controller;

use App\Services\API\GitInterface;
use App\Services\AuthService;

class LoginController extends AbstractController
{
    /**
     * @var AuthService
     */
    private $auth;
    /**
     * @var GitInterface
     */
    private $gitService;

    /**
     * @param AuthService $auth
     * @param GitInterface $gitService
     */
    public function __construct(AuthService $auth, GitInterface $gitService)
    {
        $this->auth = $auth;
        $this->gitService = $gitService;
    }

    public function index(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            $_SESSION['userGitLink'] =
                $_SESSION['userGitLink'] ?? $this->gitService->getProfileLink($_SESSION['username']);

            include $this->templatePath . 'main/index.php';

            return;
        }

        include $this->templatePath . 'login/index.php';
    }

    public function login(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            $this->redirect($_SERVER['HTTP_HOST']);
        }

        if (! $this->auth->authenticate($request)) {
            $_SESSION['error'] = 'Username and/or password is invalid';

            $this->redirect($_SERVER['HTTP_HOST']);
        }

        $this->redirect($_SERVER['HTTP_HOST']);
    }

    public function logout(array $request = [])
    {
        $this->auth->logout();

        $this->redirect($_SERVER['HTTP_HOST']);
    }
}

