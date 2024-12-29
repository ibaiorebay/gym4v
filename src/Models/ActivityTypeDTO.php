<?php

namespace app\Model;

class ActivityTypeDTO
{
    public int $id;
    public string $name;
    public int $numberOfMonitors;

    public function __construct(int $id, string $name, int $numberOfMonitors)
    {
        $this->id = $id;
        $this->name = $name;
        $this->numberOfMonitors = $numberOfMonitors;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNumberOfMonitors(): int
    {
        return $this->numberOfMonitors;
    }

    public function setNumberOfMonitors(int $numberOfMonitors): void
    {
        $this->numberOfMonitors = $numberOfMonitors;
    }
}

