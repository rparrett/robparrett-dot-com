<?php

namespace RobParrett\Middleware;

class RequireSuperuser
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * RestrictRoute middleware invokable class.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->get('auth')->isSuperUser()) {
            return $this->container->get('renderer')->render(
                $response->withStatus(401), '401.html', []
            );
        }

        return $next($request, $response);
    }
}
