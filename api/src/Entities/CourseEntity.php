<?php

namespace KnowledgeCity\Entities;

class CourseEntity extends AbstractEntity{

    protected string $name;
    protected string $description;
    protected string $preview;
    protected string $category_id;
    protected string $main_category_name;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPreview(): string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): void
    {
        $this->preview = $preview;
    }

    public function getCategoryId(): string
    {
        return $this->category_id;
    }

    public function setCategoryId(string $category_id): void
    {
        $this->category_id = $category_id;
    }

    public function getMainCategoryName(): string
    {
        return $this->main_category_name;
    }

    public function setMainCategoryName(string $main_category_name): void
    {
        $this->main_category_name = $main_category_name;
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