<?php

namespace KnowledgeCity\Interfaces;
interface IRepository
{
    public function findAll();
    public function find(string $id);
    // public function create(array $data): array;
    // public function update(string $id, array $data): array;
    // public function delete(string $id): void;
}
