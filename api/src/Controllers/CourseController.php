<?php

namespace KnowledgeCity\Controllers;
use KnowledgeCity\Interfaces\ICategoryRepository;
use KnowledgeCity\Interfaces\ICourseRepository;

class CourseController extends BaseController{

    private ICourseRepository $courseRepository;

    public function __construct(ICourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAll(){

        try {

            if( !empty( $_GET['category_id'] ) ){
                $courses = $this->courseRepository->findByCategory( $_GET['category_id'] );
            }else{
                $courses = $this->courseRepository->findAll();
            }

            return $this->jsonResponse( 200, $courses);

        } catch (\Exception $e) {
            return $this->jsonResponse( $e->getCode() ?: 500, ['error' => $e->getMessage()] );
        }
        
    }
    
    public function getById( array $request ){

        try {

            if( empty($request['id']) ){
                throw new \Exception('Category ID is required', 400);
            }
            
            $course = $this->courseRepository->find( $request['id'] );
            
            return $this->jsonResponse( 200, $course);

        } catch (\Exception $e) {
            
            return $this->jsonResponse( $e->getCode() ?: 500, ['error' => $e->getMessage()] );
        }
    }
}