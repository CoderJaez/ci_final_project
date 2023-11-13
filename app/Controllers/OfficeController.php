<?php

namespace App\Controllers;

use App\Models\OfficeModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class OfficeController extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
        return view('offices');
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $office = new OfficeModel();
        $data = $this->request->getJSON();

        if (!$office->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $office->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Office added successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $office = new OfficeModel();
        $data = $office->find($id);
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

        $office = new OfficeModel();
        $totalRecords = $office->select('id')->countAllResults();

        $totalRecordswithFilter = $office->select('id')
            ->orLike('name', $searchValue)
            ->orLike('code', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->countAllResults();

        $records = $office->select('*')
            ->orLike('name', $searchValue)
            ->orLike('code', $searchValue)
            ->orderBy($sortcolumn, $sortdir)
            ->findAll($rowperpage, $start);

        $data = array();
        foreach ($records as $record) {
            $data[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "code" => $record->code,
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
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $office = new OfficeModel();
        $data = $this->request->getJSON();
        unset($data->id);
        unset($data->email);

        if (!$office->validate($data)) {
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $office->errors()
            );
            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $office->update($id, $data);

        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Office updated successfully'
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
        $office = new OfficeModel();
        if ($office->delete($id)) {
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Office deleted successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        $response = array(
            'status' => 'error',
            'error' => true,
            'messages' => 'Office not found'
        );

        return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
    }
}
