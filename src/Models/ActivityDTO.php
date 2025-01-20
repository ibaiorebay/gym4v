<?php

namespace App\Models;
use App\Models\ActivityTypeDTO;

class ActivityDTO
{
    public int $id;
    public string $name;
    public ?\DateTimeInterface $dateStart;
    public ?\DateTimeInterface $dateEnd;
    public ActivityTypeDTO $activityType;
    public array $monitors;

    public function __construct($id, $name, $dateStart, $dateEnd, ActivityTypeDTO $activityType, array $monitors)
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->activityType = $activityType;
        $this->monitors = $monitors;
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

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    public function getActivityType(): ActivityTypeDTO
    {
        return $this->activityType;
    }

    public function setActivityType(ActivityTypeDTO $activityType): void
    {
        $this->activityType = $activityType;
    }

    public function getMonitors(): array
    {
        return $this->monitors;
    }

    public function setMonitors(array $monitors): void
    {
        $this->monitors = $monitors;
    }

}

