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

namespace Lumos\Templating;

class Slots
{
    protected array $slots = [];

    public function set(string $name, string $content): void
    {
        $this->slots[$name] = $content;
    }

    public function get(string $name): string
    {
        return $this->slots[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->slots[$name]);
    }
}