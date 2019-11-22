<?php

declare(strict_types=1);

namespace webignition\PathResolver;

class PathResolver
{
    private const CURRENT_DIRECTORY = '.';
    private const PREVIOUS_DIRECTORY = '..';

    public function resolve(string $basePath, string $path): string
    {
        $basePath = trim($basePath);
        $path = trim($path);

        if ('' === $basePath || $this->isAbsolutePath($path)) {
            return $path;
        }

        if (DIRECTORY_SEPARATOR !== substr($basePath, -1)) {
            $basePath = $basePath . DIRECTORY_SEPARATOR;
        }

        if ('' === $path) {
            return $basePath;
        }

        return $this->resolvePath($basePath . $path);
    }

    private function isAbsolutePath(string $path): bool
    {
        return strlen($path) > 1 && DIRECTORY_SEPARATOR === $path[0];
    }

    private function resolvePath(string $path): string
    {
        $resolvedPathParts = [];
        $parts = explode(DIRECTORY_SEPARATOR, $path);

        foreach ($parts as $part) {
            $part = trim($part);

            if ('' === $part || self::CURRENT_DIRECTORY === $part) {
                continue;
            }

            if (self::PREVIOUS_DIRECTORY !== $part) {
                array_push($resolvedPathParts, $part);
            } elseif (count($resolvedPathParts) > 0) {
                array_pop($resolvedPathParts);
            }
        }

        return DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $resolvedPathParts);
    }
}
