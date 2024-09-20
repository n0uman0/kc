<?php

namespace KnowledgeCity\Repositories;

use KnowledgeCity\Entities\CategoryEntity;
use KnowledgeCity\Interfaces\ICategoryRepository;
use PDO;

class CategoryRepository implements ICategoryRepository{

    private PDO $db;
    protected $table = 'categories';
    protected $columns = "categories.id, categories.name, categories.description, categories.parent_id, categories.created_at, categories.updated_at";

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): ?array
    {
        try{

            $query = $this->db->query("CALL GetCategoriesWithCoursesCount();");
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            if( empty($result) ){
                throw new \Exception('No categories found', 404);
            }

            return array_map(function($category){
                return (new CategoryEntity($category))->toArray();
            }, $result);

        }catch( \PDOException $e ){
            throw new \Exception($e->getMessage(), 500);
        }   
    }

    public function find(string $id) : array
    {
        try{

            $query = $this->db->prepare("CALL GetSingleCategoryCourseCount(:id);");
            $query->execute(['id' => $id]);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if( empty($result) ){
                throw new \Exception('Category not found', 404);
            }

            return (new CategoryEntity($result))->toArray();

        }catch( \PDOException $e ){
            throw new \Exception($e->getMessage(), 500);
        }
    }

}