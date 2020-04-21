<?php

namespace App\Twig;


use App\Twig\Cache\CacheableInterface;
use App\Twig\Cache\CacheTokenParser;
use Error;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Twig\Extension\AbstractExtension;
use Twig\TokenParser\TokenParserInterface;

class TwigCacheExtension extends AbstractExtension
{
    /**
     * @var AdapterInterface
     */
    private AdapterInterface $cache;

    public function __construct (AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return array<TokenParserInterface>
     */
    public function getTokenParsers (): array
    {
        return [
            new CacheTokenParser()
        ];
    }

    /**
     * @param CacheableInterface|string|null|array $item
     *
     * @return string
     * @throws Exception
     */
    private function getCacheKey ($item): string
    {
        if (empty($item)) {
            throw new Exception('Invalid cache key');
        }
        if (is_string($item)) {
            return $item;
        }
        if (is_array($item)) {
            return implode('-', array_map(function ($v) {
                    $this->getCacheKey($v);
                }, $item)
            );
        }
        if (!is_object($item)) {
            throw new Exception("TwigCache : Cannot serialize a variable that is not an object or a string");
        }
        try {
            $updatedAt = $item->getUpdatedAt();
            $id = $item->getId();
            $className = get_class($item);
            $className = substr($className, strrpos($className, '\\') + 1);
            return $id . $className . $updatedAt->getTimestamp();
        } catch (Error $e) {
            throw new Exception("TwigCache : Unable to serialize object for cache : \n" . $e->getMessage());
        }
    }

    /**
     * @param CacheableInterface|string $item
     *
     * @return string|null
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function getCacheValue ($item): ?string
    {
        $item = $this->cache->getItem($this->getCacheKey($item));
        return $item->get();
    }

    /**
     * @param CacheableInterface|string $item
     * @param string $value
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function setCacheValue($item, string $value): void
    {
        /** @var CacheItem $item */
        $item = $this->cache->getItem($this->getCacheKey($item));
        $item->set($value);
        $this->cache->save($item);
    }
}
