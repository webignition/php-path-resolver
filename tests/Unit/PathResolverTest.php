<?php

declare(strict_types=1);

namespace webignition\PathResolver\Tests\Unit;

use webignition\PathResolver\PathResolver;

class PathResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider emptyPathDataProvider
     * @dataProvider absolutePathDataProvider
     * @dataProvider relativePathDataProvider
     */
    public function testResolve(string $basePath, string $path, string $expectedPath): void
    {
        $pathResolver = new PathResolver();

        $this->assertSame($expectedPath, $pathResolver->resolve($basePath, $path));
    }

    /**
     * @return array[]
     */
    public function emptyPathDataProvider(): array
    {
        return [
            'empty basePath, empty path' => [
                'basePath' => '',
                'path' => '',
                'expectedPath' => '',
            ],
            'empty path' => [
                'basePath' => '/',
                'path' => '',
                'expectedPath' => '/',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function absolutePathDataProvider(): array
    {
        return [
            'absolute import path, no base path' => [
                'basePath' => '/base/Test/',
                'path' => '/absolute/file.txt',
                'expectedPath' => '/absolute/file.txt',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function relativePathDataProvider(): array
    {
        return [
            'relative path, no base path' => [
                'basePath' => '',
                'path' => '../relative/file.txt',
                'expectedPath' => '../relative/file.txt',
            ],
            'relative path, has base path; previous directory' => [
                'basePath' => '/base/Test',
                'path' => '../relative/file.txt',
                'expectedPath' => '/base/relative/file.txt',
            ],
            'relative path, has base path; previous directory, base path has trailing slash' => [
                'basePath' => '/base/Test/',
                'path' => '../relative/file.txt',
                'expectedPath' => '/base/relative/file.txt',
            ],
            'relative path, has base path; explicit current directory' => [
                'basePath' => '/base/Test',
                'path' => './relative/file.txt',
                'expectedPath' => '/base/Test/relative/file.txt',
            ],
            'relative path, has base path; implicit current directory' => [
                'basePath' => '/base/Test',
                'path' => 'relative/file.txt',
                'expectedPath' => '/base/Test/relative/file.txt',
            ],
            'relative path, has base path; previous previous directory' => [
                'basePath' => '/base/Test',
                'path' => '../../relative/file.txt',
                'expectedPath' => '/relative/file.txt',
            ],
            'relative path, has base path; double-dot segments leave base path directory' => [
                'basePath' => '/base/Test',
                'path' => '../../../relative/file.txt',
                'expectedPath' => '/relative/file.txt',
            ],
        ];
    }
}
