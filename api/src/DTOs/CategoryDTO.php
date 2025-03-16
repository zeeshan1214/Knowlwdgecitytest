<?php
namespace Assesment\Test\DTOs;

class CategoryDTO
{
    public string $id;
    public string $name;
    public ?string $parent_id;
    public int $course_count;

    public ?array $children = null;

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->id = $data['id'];
        $dto->name = $data['name'];
        $dto->parent_id = $data['parent_id'] ?? null;
        $dto->course_count = $data['course_count'];
        if (!empty($data['children'])) {
            $dto->children = array_map(function ($child) {
                return CategoryDTO::fromArray($child);
            }, $data['children']);
        }
        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'course_count' => $this->course_count,
            'children' => $this->children
        ];
    }

}