<?php

namespace App\Controllers;

use App\Models\OfficeModel;
use App\Models\SeverityModel;
use App\Models\TicketModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class TicketController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     * 


     */
    public function index()
    {
        //
        $severity = new SeverityModel();
        $office = new  OfficeModel();


        $data = array(
            "severities" => $severity->findAll(),
            "offices" => $office->findAll(),
        );
        return view("tickets", $data);
    }


    public function show($id = null)
    {
        $post = new TicketModel();
        $data = $post->find($id);
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
    }

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


        $post = new TicketModel();
        $totalRecords = $post->select('id')->countAllResults();

        $totalRecordswithFilter = $post->select('tickets.id')
            ->join('auth_identities', 'auth_identities.user_id = tickets.user_id')
            ->join('severities', 'severities.id = tickets.severity_id')
            ->join('offices', 'offices.id = tickets.office_id')
            ->orLike('auth_identities.name', $searchValue)
            ->orLike('tickets.status', $searchValue)
            ->orLike('tickets.description', $searchValue)
            ->orLike('offices.name', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $post->select('tickets.*, severities.name as severity, auth_identities.name, auth_identities.secret as email, offices.name as office')
            ->join('auth_identities', 'auth_identities.user_id = tickets.user_id')
            ->join('severities', 'severities.id = tickets.severity_id')
            ->join('offices', 'offices.id = tickets.office_id')
            ->orLike('auth_identities.name', $searchValue)
            ->orLike('tickets.status', $searchValue)
            ->orLike('tickets.description', $searchValue)
            ->orLike('offices.name', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);

        $data = array();
        foreach ($records as $record) {
            $data[] = array(
                "id" => $record['id'],
                "name" => $record['name'],
                "email" => $record['email'],
                "office" => $record['office'],
                "description" => $record['description'],
                "severity" => $record['severity'],
                "action" => ((auth()->user()->inGroup('user') && strtoupper($record["status"]) == "PROCESSING") || strtoupper($record["status"]) == "RESOLVED") ? '<td> NO ACTION REQUIRED</td>' : '<td>
            <button class="btn btn-warning btn-sm" id="editRow">Edit</button>
            <button class="btn btn-danger btn-sm" id="deleteRow">Delete</button>
            </td>',
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

        $post = new TicketModel();
        $data = $this->request->getJSON();


        if (!$post->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $post->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        try {
            $post->insert($data);
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Post added successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
        } catch (\Exception $e) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $e->getMessage()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $post = new TicketModel();
        $data = $this->request->getJSON();
        unset($data->id);

        if (!$post->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $post->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $post->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Post updated successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $post = new TicketModel();

        if ($post->delete($id)) {
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Post deleted successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        $response = array(
            'status' => 'error',
            'error' => true,
            'messages' => 'Post not found'
        );

        return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
    }
}
