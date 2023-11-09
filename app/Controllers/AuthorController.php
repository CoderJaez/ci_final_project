<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;
class AuthorController extends ResourceController
{
     /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $author = new \App\Models\Author();
        $data = $author->find($id);
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $author = new \App\Models\Author();
        $data = $this->request->getPost();

        if (!$author->validate($data)){
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $author->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $author->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Author added successfully'
        );

        return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $author = new \App\Models\Author();
        $data = $this->request->getJSON();
        unset($data->id);

        if (!$author->validate($data)){
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $author->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_NOT_MODIFIED)->setJSON($response);
        }

        $author->update($id, $data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Author updated successfully'
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
        $author = new \App\Models\Author();

        if ($author->delete($id)){
            $response = array(
                'status' => 'success',
                'error' => false,
                'messages' => 'Author deleted successfully'
            );

            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }

        $response = array(
            'status' => 'error',
            'error' => true,
            'messages' => 'Author not found'
        );

        return $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->setJSON($response);
        
    }
}
