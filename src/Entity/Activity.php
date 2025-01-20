<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?ActivityType $activityType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    /**
     * @var Collection<int, ActivityMonitor>
     */
    #[ORM\OneToMany(targetEntity: ActivityMonitor::class, mappedBy: 'activity', cascade: ['remove'])]
    private Collection $activityid;


    public function __construct()
    {
        $this->activityid = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivityType(): ?ActivityType
    {
        return $this->activityType;
    }

    public function setActivityType(?ActivityType $activityType): static
    {
        $this->activityType = $activityType;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * @return Collection<int, ActivityMonitor>
     */
    public function getActivityid(): Collection
    {
        return $this->activityid;
    }

    public function addActivityid(ActivityMonitor $activityid): static
    {
        if (!$this->activityid->contains($activityid)) {
            $this->activityid->add($activityid);
            $activityid->setActivity($this);
        }

        return $this;
    }

    public function removeActivityid(ActivityMonitor $activityid): static
    {
        if ($this->activityid->removeElement($activityid)) {
            if ($activityid->getActivity() === $this) {
                $activityid->setActivity(null);
            }
        }

        return $this;
    }

    public function createMonitor(Monitor $monitor): ActivityMonitor
    {
        $activityMonitor = new ActivityMonitor();
        $activityMonitor->setMonitor($monitor);
        $activityMonitor->setActivity($this);
        return $activityMonitor;
    }
}
