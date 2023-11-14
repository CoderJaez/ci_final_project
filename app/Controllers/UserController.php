<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Config\AuthGroups;
use CodeIgniter\Shield\Entities\UserIdentity;
use CodeIgniter\Shield\Models\UserModel;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

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
        $data = $builder->select('identities.user_id, users.username as name, identities.secret as email, groups.group as group ')
            ->join("auth_groups_users as groups", "groups.user_id = identities.user_id")->where("identities.user_id", $id)
            ->join("users", "users.id = identities.user_id")
            ->get()
            ->getFirstRow();
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
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

        $totalRecordswithFilter = $builder->select('identities.user_id,  identities.secret ')
            ->join("auth_groups_users as groups", "groups.user_id = identities.user_id")
            ->join("users", "users.id = identities.user_id")
            ->orLike('users.username', $searchValue)
            ->orLike('identities.secret', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $builder->select('identities.user_id, users.username as name, identities.secret,groups.group')
            ->join("auth_groups_users as groups", "groups.user_id = identities.user_id")
            ->join("users", "users.id = identities.user_id")
            ->orLike('users.username', $searchValue)
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
        $db = \Config\Database::connect();
        $builder = $db->table('auth_groups_users');
        $data = $this->request->getJSON();
        unset($data->id);
        unset($data->name);
        unset($data->email);


        try {
            $builder->where('user_id', $id)->update($data);
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'User role updated successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        } catch (Exception $e) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => "Error updating role."
            );
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        if ($builder->delete(['id' => $id])) {
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'User deleted successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        $response = array(
            'status' => 'error',
            'error' => true,
            'messages' => 'User not found'
        );

        return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
    }
}
