<?php

namespace App\DTO;

class BaseDTO
{
    /**
     * @param array<string, mixed> $source
     */
    final public function __construct(protected array  $source)
    {

    }

    /**
     * @param array<string, mixed> $source
     * @return static
     */
    public static function create(array $source): static
    {
        return new static($source);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        } else {
            return $this->source[$name] ?? null;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [

        ];
    }
}
