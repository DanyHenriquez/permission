<?php

namespace Prezto;

use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Prezto\Mode as Mode;

class PermissionMiddleware
{
    protected $patterns;
    protected $mode = null;
    protected $allowed = null;

    public function __construct($patterns = [], $mode = Mode::ALLOW)
    {
        $this->patterns = $patterns;
        $this->mode = $mode;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if ($this->mode == Mode::ALLOW)
            $this->allowed = $this->allow($request);

        if ($this->mode == Mode::DENY)
            $this->allowed = $this->deny($request);

        if (!$this->allowed)
            $response = $response->withStatus(401);

        $response = $next($request, $response);
        return $response;
    }

    /**
     * The default allow rule set allows all connections through unless otherwise stated
     * @param Request $request
     * @return bool
     */
    public function allow(Request $request)
    {
        foreach ($this->patterns as $regex)
            if (preg_match(sprintf("#^%s$#i", $regex), $request->getUri()->getPath()))
                return false;

        return true;
    }

    /**
     * A default deny rule set will deny all connections through  unless a url matches a specific rule.
     * @param Request $request
     * @return bool
     */
    public function deny(Request $request)
    {
        foreach ($this->patterns as $regex)
            if (preg_match(sprintf("#^%s$#i", $regex), $request->getUri()->getPath()))
                return true;

        return false;
    }
}
