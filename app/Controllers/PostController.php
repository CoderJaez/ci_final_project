<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;
class PostController extends ResourceController
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
        $post = new \App\Models\Post();
        $data = $post->find($id);
        return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $post = new \App\Models\Post();
        $data = $this->request->getPost();

        if (!$post->validate($data)){
            $response = array(
                'status' => 'error',
                'error' => true,
                'messages' => $post->errors()
            );

            return $this->response->setStatusCode(Response::HTTP_BAD_REQUEST)->setJSON($response);
        }

        $post->insert($data);
        $response = array(
            'status' => 'success',
            'error' => false,
            'messages' => 'Post added successfully'
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
        $post = new \App\Models\Post();
        $data = $this->request->getJSON();
        unset($data->id);

        if (!$post->validate($data)){
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
        $post = new \App\Models\Post();

        if ($post->delete($id)){
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