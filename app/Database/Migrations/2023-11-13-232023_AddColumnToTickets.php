<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnToTickets extends Migration
{
    public function up()
    {
        //
        $field = [
            'office_id' => ['type' => 'INT', 'constraint' => 11, 'null' => false],
        ];
        $this->forge->addColumn("tickets", $field);
        $this->forge->addForeignKey('office_id', 'offices', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        //

        $this->forge->dropColumn("tickets", "office_id");
    }
}
