<?php
/*
 * Lumos Framework
 * Copyright (c) 2022 Jack Polgar
 * https://gitlab.com/lumosphp/lumos
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Lumos\Templating\Helpers;

class AssetHelper implements HelperInterface
{
    public function __construct(
        protected string $basePath,
        protected ?string $hostname = null
    ) {}

    public function getName(): string
    {
        return 'assets';
    }

    public function path(string $name): string
    {
        $host = $this->hostname ?? '';
        $basePath = $this->basePath ? rtrim($this->basePath, '/') : '';
        return $host.$basePath.'/'.$name;
    }
}