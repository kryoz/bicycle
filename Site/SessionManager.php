<?php
/**
 * Created by PhpStorm.
 * User: kryoz
 * Date: 03.11.13
 * Time: 16:16
 */

namespace Site;


use Core\HttpRequest;

class SessionManager
{
    protected $user;

    private $demoAccount = [
        'user' => 'demo',
        'password' => 'demo'
    ];

    public function __construct()
    {
        session_set_cookie_params(3600);
        session_start();
    }

    public function login(HttpRequest $request)
    {
        if (!isset($request->getPost()['user']) || !isset($request->getPost()['password']))
        {
            if (isset($_SESSION['user'])) {
                $this->setUser($_SESSION['user']);
            }
            return;
        }

        if ($this->checkCredentials($request, $this->demoAccount)) {
            $this->setUser($this->demoAccount);
            $_SESSION['user'] = $this->demoAccount;
        }
    }

    public function logout()
    {
        $this->setUser(null);
        unset($_SESSION['user']);
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    private function checkCredentials(HttpRequest $request, $user)
    {
        return $request->getPost()['user'] === $user['user']
            && $request->getPost()['password'] === $user['password']
            && FormToken::create()->validateToken($request, true);
    }
} 