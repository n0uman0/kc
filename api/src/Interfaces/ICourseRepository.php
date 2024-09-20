<?php

namespace KnowledgeCity\Interfaces;
interface ICourseRepository extends IRepository
{
    public function findByCategory( string $category_id ): ?array;
}
