<?php


namespace App\Tests\Twig;


use App\Twig\Cache\CacheableInterface;
use DateTime;
use DateTimeInterface;

class FakeClass implements CacheableInterface
{
    public function getId (): string
    {
        return 'blablabla';
    }

    public function getUpdatedAt (): DateTimeInterface
    {
        return new DateTime('@12312312');
    }
}