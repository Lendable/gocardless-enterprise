<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 11:54
 */

namespace GoCardless\Enterprise\Model;


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
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_filter(get_object_vars($this));
    }

    /**
     * @param $data
     */
    public function fromArray($data)
    {
        foreach($data as $property => $value)
        {
            if(property_exists($this, $property)){
                $this->{$property} = $value;
            }
        }
    }
} 