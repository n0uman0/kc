<?php

namespace KnowledgeCity\Entities;

class CategoryEntity extends AbstractEntity{

    protected string $name;
    protected ?string $description;
    protected ?string $parent_id;
    protected int $count_of_courses;
    protected string $created_at;
    protected string $updated_at;

    public function __construct( array $data ) {
        parent::__construct($data['id'], $data);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getParentId(): ?string
    {
        return $this->parent_id;
    }

    public function setParentId(?string $parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    public function getCountOfCourses(): int
    {
        return $this->count_of_courses;
    }

    public function setCountOfCourses(int $count_of_courses): void
    {
        $this->count_of_courses = $count_of_courses;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    
}