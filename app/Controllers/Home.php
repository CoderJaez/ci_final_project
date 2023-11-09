<?php

namespace App\Controllers;

use CodeIgniter\Database\RawSql;

class Home extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();
        $builder = $db->table('authors');

        // $query = $builder->get();
        // SELECT * FROM authors

        // $query = $builder->get(10,20);

        // $sql = $builder->getCompiledSelect();
        // echo $sql;

        // $builder->select('id, first_name');
        // $query = $builder->getWhere(['id' => 2]);

        // $sql = "CONCAT(first_name, ' ', last_name) AS full_name";
        // $builder->select(new RawSql($sql));
        // $query = $builder->getWhere(['id' => 2]);

        // $query = $builder->selectMax('birthdate')->get();
        // $query = $builder->selectMin('birthdate')->get();
        // $query = $builder->selectAvg('birthdate')->get();
        // $query = $builder->selectSum('id')->get();
        // $query = $builder->selectCount('id')->get();

        // $builder->select('*');
        // $builder->join('posts', 'posts.author_id = authors.id');
        // $builder->where('authors.id', 2);

        // $query = $builder->get();

        // $builder->select('*');
        // $builder->where('first_name','Kaylie');
        // $builder->where('last_name', 'Kilback');

        // $builder->where('id !=',2);
        // $builder->where('id >', 2);
        // $builder->where('id >=', 2);

        // $builder->where('first_name', 'Kaylie');
        // $builder->orWhere('last_name', 'Kilback');

        // $builder->whereIn('id', [1,2,3]);

        // $builder->like('first_name', 'Kay');
        // $builder->orLike('last_name', 'Kil');

        // $sql = $builder->getCompiledSelect();
        // echo $sql;

        // $builder->groupBy('first_name');
        // $builder->orderBy('first_name', 'ASC');
        // $builder->orderBy('first_name', 'DESC');

        // $data = [
        //     'first_name' => 'RUFINO JOHN',
        //     'last_name' => 'AGUILAR',
        //     'email' => 'aguilar@gmail.com',
        //     'birthdate' => '1990-01-01',
        //     'added' => date('Y-m-d H:i:s'),
        // ];

        // $builder->insert($data);
        
        // $builder->select('*');
        // $builder->where('first_name', 'RUFINO JOHN');
        // $query = $builder->get();

        // $data = [
        //     'first_name' => 'RUFINO',
        //     'last_name' => 'AGUILAR',
        //     'email' => 'aguilar@gmail.com',
        //     'birthdate' => '1990-01-01',
        //     'added' => date('Y-m-d H:i:s'),
        // ];
        // $builder->where('id', 101);
        // $builder->update($data);
        
        // $builder->select('*');
        // $builder->where('first_name', 'RUFINO');
        // $query = $builder->get();

        // $builder->where('id', 101);
        // $builder->delete();

        // $builder->select('*');
        // $builder->where('first_name', 'RUFINO');
        // $query = $builder->get();

        // $json = new \stdClass();

        // $json->data = $query->getResult();

        // return json_encode($json);

        return view('welcome_message');
    }
}
