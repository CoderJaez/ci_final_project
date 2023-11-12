<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Office extends Migration
{
    public function up()
    {
        $fields = [
            "id" => [
                "type" => "INT",
                "null" => false,
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => false,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'datetime',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ]
        ];
        $this->forge->addField($fields);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('offices');
        //
    }

    public function down()
    {
        $this->forge->dropTable("offices");
    }
}
