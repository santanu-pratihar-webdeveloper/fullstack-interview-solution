<?php

namespace App\Controllers;

use App\Models\AuthUserModel;
use App\Models\TeacherModel;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

class TeacherController extends BaseController
{
    public function index()
    {
        $teacherModel = new TeacherModel();
        $rows = $teacherModel
            ->select('teachers.id, auth_user.email, auth_user.first_name, auth_user.last_name, teachers.university_name, teachers.gender, teachers.year_joined')
            ->join('auth_user', 'auth_user.id = teachers.user_id')
            ->findAll();
        return $this->response->setJSON(['data' => $rows]);
    }

    // Single POST creates user + teacher in one transaction
    public function createUserWithTeacher()
    {
        $data = $this->request->getJSON(true) ?: $this->request->getPost();

        $rules = [
            'email'           => 'required|valid_email|is_unique[auth_user.email]',
            'first_name'      => 'required',
            'last_name'       => 'required',
            'password'        => 'required|min_length[6]',
            'university_name' => 'required',
            'gender'          => 'required',
            'year_joined'     => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['errors' => $this->validator->getErrors()])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $userModel = new AuthUserModel();
        $teacherModel = new TeacherModel();

        $userId = $userModel->insert([
            'email'      => $data['email'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
        ], true);

        $teacherModel->insert([
            'user_id'         => $userId,
            'university_name' => $data['university_name'],
            'gender'          => $data['gender'],
            'year_joined'     => $data['year_joined'],
        ], true);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['error' => 'Transaction failed'])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->response->setJSON(['message' => 'User and Teacher created', 'user_id' => $userId]);
    }
}
