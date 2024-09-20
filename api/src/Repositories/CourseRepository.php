<?php

namespace KnowledgeCity\Repositories;

use KnowledgeCity\Entities\CourseEntity;
use KnowledgeCity\Interfaces\ICourseRepository;
use PDO;

class CourseRepository implements ICourseRepository{

    private PDO $db;
    protected $table = 'courses';
    protected $columns = "courses.id, courses.description, title as name, image as preview, courses.created_at, courses.updated_at";

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): ?array
    {
        try{

            $query = $this->db->query("
                SELECT {$this->columns}, categories.name as main_category_name, categories.id as category_id
                FROM {$this->table} 
                INNER JOIN categories ON {$this->table}.category_id = categories.id
            ");

            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if( empty($result) ){
                throw new \Exception('No courses found', 404);
            }

            return array_map(function($course){
                return (new CourseEntity($course))->toArray();
            }, $result);

        }catch( \PDOException $e ){
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function find(string $id): ?array
    {
        try{

            $query = $this->db->prepare("
                SELECT {$this->columns}, categories.name as main_category_name, categories.id as category_id
                FROM {$this->table} 
                INNER JOIN categories ON {$this->table}.category_id = categories.id
                WHERE {$this->table}.id = :id
            ");
            
            $query->execute(['id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if( empty($result) ){
                throw new \Exception('No course found against the id', 404);
            }

            return (new CourseEntity($result))->toArray();
            
        }catch( \PDOException $e ){
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function findByCategory(string $category_id): ?array
    {
        try{

            $query = $this->db->prepare("
                SELECT {$this->columns} 
                FROM {$this->table} 
                INNER JOIN categories ON courses.category_id = categories.id 
                WHERE courses.category_id = :category_id
            ");

            $query->execute(['category_id' => $category_id]);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if( empty($result) ){
                throw new \Exception('No courses found against the category', 404);
            }

            return array_map(function($course){
                return (new CourseEntity($course))->toArray();
            }, $result);

        }catch( \PDOException $e ){
            throw new \Exception($e->getMessage(), 500);
        }
    }
}