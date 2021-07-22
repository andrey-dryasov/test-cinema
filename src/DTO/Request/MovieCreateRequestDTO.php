<?php

namespace App\DTO\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MovieCreateRequestDTO
{
    public string $title;

    public int $duration;

    public function __construct(Request $request)
    {
        $data = $request->toArray();

        $this->title = $data['title'];
        $this->duration = $data['duration'];

        if (empty($this->title) || empty($this->duration)) {
            throw new NotFoundHttpException('Check params');
        }
    }
}