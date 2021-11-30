<?php

namespace Lendable\GoCardlessEnterprise\Model;

class Model
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var array
     */
    protected $links = [];

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->created_at = $createdAt;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setLinks(array $links)
    {
        $this->links = $links;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function toArray(): array
    {
        return \array_filter(\get_object_vars($this));
    }

    public function fromArray(array $data): void
    {
        foreach ($data as $property => $value) {
            if (\property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}
