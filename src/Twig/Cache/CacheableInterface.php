<?php

namespace App\Twig\Cache;


interface CacheableInterface
{
//    public function getId(): int; /* If the id of the class is a type of integer */

    public function getId(): string;

    public function getUpdatedAt(): \DateTimeInterface;
}