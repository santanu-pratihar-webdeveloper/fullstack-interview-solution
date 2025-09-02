<?php

namespace App\Controllers;

use App\Models\AuthUserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    public function register()
    {
        $rules = [
            'email'      => 'required|valid_email|is_unique[auth_user.email]',
            'first_name' => 'required',
            'last_name'  => 'required',
            'password'   => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['errors' => $this->validator->getErrors()])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $userModel = new AuthUserModel();
        $id = $userModel->insert([
            'email'      => $data['email'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
        ], true);

        return $this->response->setJSON(['id' => $id, 'message' => 'User registered']);
    }

    public function login()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $userModel = new AuthUserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON(['error' => 'Invalid credentials'])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $payload = [
            'iss'   => base_url(),
            'sub'   => $user['id'],
            'email' => $user['email'],
            'iat'   => time(),
            'exp'   => time() + 60 * 60 * 24, // 24h
        ];
        $token = JWT::encode($payload, getenv('JWT_SECRET') ?: 'change_me', 'HS256');

        return $this->response->setJSON(['token' => $token]);
    }

    public function me()
    {
        $uid = $this->request->user->id ?? null;
        if (!$uid) {
            return $this->response->setJSON(['error' => 'No user'])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
        $userModel = new AuthUserModel();
        $user = $userModel->find($uid);
        unset($user['password']);
        return $this->response->setJSON(['user' => $user]);
    }

    public function listUsers()
    {
        $userModel = new AuthUserModel();
        $users = $userModel->select('id, email, first_name, last_name, created_at')->findAll();
        return $this->response->setJSON(['data' => $users]);
    }
}
