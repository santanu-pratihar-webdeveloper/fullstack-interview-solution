<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeachers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'university_name'  => ['type' => 'VARCHAR', 'constraint' => 191],
            'gender'           => ['type' => 'VARCHAR', 'constraint' => 20],
            'year_joined'      => ['type' => 'INT', 'constraint' => 4],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'auth_user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('teachers', true);
    }

    public function down()
    {
        $this->forge->dropTable('teachers');
    }
}
