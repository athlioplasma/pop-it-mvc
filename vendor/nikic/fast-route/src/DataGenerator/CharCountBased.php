<?php
declare(strict_types=1);

namespace FastRoute\DataGenerator;

use function count;
use function implode;

/** @final */
class CharCountBased extends RegexBasedAbstract
{
    protected function getApproxChunkSize(): int
    {
        return 30;
    }

    /** @inheritDoc */
    protected function processChunk(array $regexToRoutesMap): array
    {
        $routeMap = [];
        $regexes = [];

        $suffixLen = 0;
        $suffix = '';
        $count = count($regexToRoutesMap);
        foreach ($regexToRoutesMap as $regex => $route) {
            $suffixLen++;
            $suffix .= "\t";

            $regexes[] = '(?:' . $regex . '/(\t{' . $suffixLen . '})\t{' . ($count - $suffixLen) . '})';
            $routeMap[$suffix] = [$route->handler, $route->variables, $route->extraParameters];
        }

        $regex = '~^(?|' . implode('|', $regexes) . ')$~';

        return ['regex' => $regex, 'suffix' => '/' . $suffix, 'routeMap' => $routeMap];
    }
}
