<?php

namespace KnowledgeCity\Controllers;
use KnowledgeCity\Interfaces\ICategoryRepository;

class CategoryController extends BaseController{

    private ICategoryRepository $categoryRepository;

    public function __construct(ICategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll(){

        try {

            $categories = $this->categoryRepository->findAll();
            return $this->jsonResponse( 200, $categories);

        } catch (\Exception $e) {
            return $this->jsonResponse( $e->getCode() ?: 500, ['error' => $e->getMessage()] );
        }
        
    }
    
    public function getById( array $request ){

        try {

            if( empty($request['id']) ){
                throw new \Exception('Category ID is required', 400);
            }
            
            $category = $this->categoryRepository->find( $request['id'] );
            return $this->jsonResponse( 200, $category);

        } catch (\Exception $e) {
            return $this->jsonResponse( $e->getCode() ?: 500, ['error' => $e->getMessage()] );
        }
    }
}