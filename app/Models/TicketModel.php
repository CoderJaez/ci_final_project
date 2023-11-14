<?php

namespace App\Models;

use CodeIgniter\Model;




class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'severity_id', 'office_id', 'status', 'remarks', 'description'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required',
        'severity_id' => 'required',
        'office_id' => 'required',
        'description' => 'required|min_length[5]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    //Relationships/Association
    protected $returnTypeRelations = 'array';
    protected $belongsTo = [
        'auth_identities' => [
            'model' => 'CodeIgniter\Shield\Models\UserIdentityModel',
            'foreign_key' => 'user_id',
            'local_key' => 'user_id'
        ],
        'offices' => [

            'model' => OfficeModel::class,
            'foreign_key' => 'office_id',
            'local_key' => 'id'
        ],

        'severities' => [
            'model' => SeverityModel::class,
            'foreign_key' => 'severity_id',
            'local_key' => 'id'

        ],
    ];
}
