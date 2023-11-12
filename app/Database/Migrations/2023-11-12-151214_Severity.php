<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Severity extends Migration
{
    public function up()
    {
        //
        $fields = [
            "id" => [
                "type" => "INT",
                'constraint' => 11,
                'null' => false,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('severities');
    }

    public function down()
    {
        //
        $this->forge->dropTable("severities");
    }
}
