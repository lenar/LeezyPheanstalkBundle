<?php
declare(strict_types=1);

namespace Leezy\PheanstalkBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait DataCollectorBCTrait
{
    public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {
        $this->doCollect($request, $response, $exception);
    }

    abstract protected function doCollect(Request $request, Response $response, ?\Throwable $exception = null): void;
}
