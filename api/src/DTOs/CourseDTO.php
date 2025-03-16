<?php

namespace Assesment\Test\DTOs;

class CourseDTO
{
    public string $course_id;
    public string $title;
    public string $description;
    public string $image_preview;
    public string $category_id;

    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->course_id = $data['course_id'];
        $dto->title = $data['title'];
        $dto->description = $data['description'];
        $dto->image_preview = $data['image_preview'];
        $dto->category_id = $data['category_id'];
        return $dto;
    }

    public function toArray(): array
    {
        return [
            'course_id' => $this->course_id,
            'title' => $this->title,
            'description' => $this->description,
            'image_preview' => $this->image_preview,
            'category_id' => $this->category_id,
        ];
    }
}