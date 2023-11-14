<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Config\AuthGroups;
use CodeIgniter\Shield\Entities\UserIdentity;
use CodeIgniter\Shield\Models\UserModel;

class UserController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
        $groups = new AuthGroups();
        $data = array(
            'groups' => $groups
        );
        return view("users", $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('auth_identities as identities');
        $builder->select('identities.user_id, identities.name, identities.secret ')
            ->join("auth_groups_users as groups", "groups.user_id = identities.user_id")->where("entities.user_id", $id)
            ->get();
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */

    public function list()
    {
        $postData = $this->request->getPost();

        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $searchValue = $postData['search']['value'];
        $sortby = $postData['order'][0]['column']; // Column index
        $sortdir = $postData['order'][0]['dir']; // asc or desc
        $sortcolumn = $postData['columns'][$sortby]['data']; // Column name 

        $db = \Config\Database::connect();
        $builder = $db->table('auth_identities as identities');

        $totalRecords = $builder->select('id')->countAllResults();

        $totalRecordswithFilter = $builder->select('identities.user_id, identities.name, identities.secret ')
            ->join("auth_groups_users as groups", "groups.user_id = identities.user_id")
            ->orLike('name', $searchValue)
            ->orLike('identities.secret', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $builder->select('identities.user_id, identities.name, identities.secret,groups.group')
            ->join("auth_groups_users as groups", "groups.user_id = identities.user_id")
            ->orLike('name', $searchValue)
            ->orLike('identities.secret', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->get($rowperpage, $start);

        $data = array();
        foreach ($records->getResultObject() as $record) {
            $data[] = array(
                "user_id" => $record->user_id,
                "name" => $record->name,
                "email" => $record->secret,
                "role" => $record->group
            );
        }

        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $data
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
