<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return service('response')->setJSON(['error' => 'Missing Bearer token'])->setStatusCode(401);
        }

        $token = $matches[1];
        $secret = getenv('JWT_SECRET') ?: 'change_me';

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            // attach user id to request (shared via global)
            $request->user = (object) ['id' => $decoded->sub ?? null, 'email' => $decoded->email ?? null];
        } catch (\Exception $e) {
            return service('response')->setJSON(['error' => 'Invalid token', 'detail' => $e->getMessage()])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
