<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $author = new \App\Models\Author;
        $post = new \App\Models\Post;

        $data['totalauthors'] = $author->countAll();
        $data['totalposts'] = $post->countAll();

        $data['barchartdata'] = $author->select("CONCAT(authors.first_name, ' ', authors.last_name) as author_name,
        COUNT(posts.id) as post_count")
        ->join('posts', 'posts.author_id = authors.id')
        ->groupBy('authors.id')
        ->findAll();


        return view('dashboard',$data);
    }
}
