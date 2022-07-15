<?php
/*
 * Lumos Framework
 * Copyright (c) 2022 Jack Polgar
 * https://gitlab.com/nirix/lumos
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Lumos\Templating;

use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine as SymfonyPhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

class PhpEngine extends SymfonyPhpEngine
{
    public function slot(string $name = null)
    {
        if ($name) {
            return $this->get('slots')->get($name);
        }

        return $this->get('slots');
    }

    /**
     * Configure and return a PhpEngine instance.
     */
    public static function create(string $viewsPath): static
    {
        $filesystemLoader = new FilesystemLoader($viewsPath);
        $templating = new static(new TemplateNameParser(), $filesystemLoader);
        $templating->set(new SlotsHelper());

        return $templating;
    }
}
