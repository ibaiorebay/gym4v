<?php

namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;

class MonitorNewDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public int $id,
        #[Assert\NotBlank(message:"El nombre es obligatorio")]
        public string $name,
        #[Assert\NotBlank]
        public string $email,
        #[Assert\NotBlank]	
        public string $phone,
        #[Assert\NotBlank]
        public string $photo){}
}
