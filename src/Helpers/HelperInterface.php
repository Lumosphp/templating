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

interface HelperInterface
{
    /**
     * Returns the name of the helper.
     */
    public function getName(): string;
}
