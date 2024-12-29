<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;

class ActivityDTO
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $activityTypeId;

    #[Assert\NotBlank]
    #[Assert\Count(min: 1)]
    private array $monitorIds;

    #[Assert\NotBlank]
    #[Assert\DateTime(format: 'Y-m-d\TH:i:s')]
    private string $dateStart;

    public function __construct(int $activityTypeId, array $monitorIds, string $dateStart)
    {
        $this->activityTypeId = $activityTypeId;
        $this->monitorIds = $monitorIds;
        $this->dateStart = $dateStart;
    }

    public function getActivityTypeId(): int
    {
        return $this->activityTypeId;
    }

    public function setActivityTypeId(int $activityTypeId): void
    {
        $this->activityTypeId = $activityTypeId;
    }

    public function getMonitorIds(): array
    {
        return $this->monitorIds;
    }

    public function setMonitorIds(array $monitorIds): void
    {
        $this->monitorIds = $monitorIds;
    }

    public function getDateStart(): string
    {
        return $this->dateStart;
    }

    public function setDateStart(string $dateStart): void
    {
        $this->dateStart = $dateStart;
    }
}

