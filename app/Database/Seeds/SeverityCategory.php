<?php

namespace App\Database\Seeds;

use App\Entities\Severity;
use App\Models\SeverityModel;
use CodeIgniter\Database\Seeder;

class SeverityCategory extends Seeder
{
    public function run()
    {
        $model = new SeverityModel();
        $data  = [
            [
                'name' => 'LOW',
                'description' => 'Low',
            ],
            [
                'name' => 'NORMAL',
                'description' => 'NORMAL',
            ],
            [
                'name' => 'HIGH',
                'description' => 'HIGH',
            ],
            [
                'name' => 'CRITICAL',
                'description' => 'CRITICAL',
            ]
        ];

        $model->insertBatch($data);
    }
}
