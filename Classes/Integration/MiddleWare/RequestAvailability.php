<?php
namespace FluidTYPO3\Flux\Integration\MiddleWare;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @codeCoverageIgnore
 */
class RequestAvailability implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $GLOBALS['TYPO3_REQUEST'] = $GLOBALS['TYPO3_REQUEST'] ?? $request;
        return $handler->handle($request);
    }
}
