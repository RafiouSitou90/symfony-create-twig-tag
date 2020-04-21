<?php

namespace App\Twig\Cache;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class CacheTokenParser extends AbstractTokenParser
{
    /**
     * @param Token $token
     *
     * @return CacheNode|Node
     * @throws SyntaxError
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $key = $this->parser->getExpressionParser()->parseExpression();
        $key->setAttribute('always_defined', true);
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse([$this, 'decideCacheEnd'], true);
        $stream->expect(Token::BLOCK_END_TYPE);

        return new CacheNode($key, $body, $lineno, $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'cache';
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function decideCacheEnd(Token $token): bool {
        return $token->test('endcache');
    }
}