<?php

namespace KnowledgeCity\Interfaces;

interface ICategoryRepository extends IRepository
{
    public function find(string $id): array;
}
