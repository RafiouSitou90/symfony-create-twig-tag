<?php


namespace App\Tests\Twig;


use App\Twig\TwigCacheExtension;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;

class TwigCacheExtensionTest extends TestCase
{

    /**
     * @var MockObject|AdapterInterface cache
     */
    private $cache;

    /**
     * @var TwigCacheExtension
     */
    private TwigCacheExtension $extension;

    public function setUp(): void
    {
        /** @var MockObject|AdapterInterface cache */
        $this->cache = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $this->extension = new TwigCacheExtension($this->cache);
    }

    /**
     * @return iterable
     */
    public function cacheKeys(): iterable
    {
        yield ['hello', 'hello'];
        yield ['hello-bye', ['hello', 'bye']];

        $fake = new FakeClass();
        yield [
            $fake->getId() . 'FakeClass' . $fake->getUpdatedAt()->getTimestamp(),
            $fake
        ];
        yield [
            'card-' . $fake->getId() . 'FakeClass' . $fake->getUpdatedAt()->getTimestamp(),
            ['card', $fake]
        ];
    }

    /**
     * @dataProvider cacheKeys
     *
     * @param $expected
     * @param $value
     *
     * @throws Exception
     */
    public function testCacheKeyGeneration($expected, $value): void
    {
        $this->assertEquals($expected, $this->extension->getCacheKey($value));
    }

    /**
     * @throws Exception
     */
    public function testCacheKeyWithBadValues(): void
    {
        $this->expectException(Exception::class);
        $this->extension->getCacheKey([]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetCacheValue(): void
    {
        $item = new CacheItem();
        $this->cache->expects($this->any())->method('getItem')
            ->with('demo')
            ->willReturn($item);
        $this->extension->setCacheValue('demo', 'Hello');
        $this->assertEquals('Hello', $item->get());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetCacheValue(): void
    {
        $item = new CacheItem();
        $item->set('hello');
        $this->cache->expects($this->any())->method('getItem')
            ->with('demo')
            ->willReturn($item);
        $value = $this->extension->getCacheValue('demo');
        $this->assertEquals($item->get(), $value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetCacheValueWithoutValue(): void
    {
        $item = new CacheItem();
        $this->cache->expects($this->any())->method('getItem')
            ->with('demo')
            ->willReturn($item);
        $value = $this->extension->getCacheValue('demo');
        $this->assertEquals(null, $value);
    }

}