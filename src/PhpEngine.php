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

use BadMethodCallException;
use Exception;
use Lumos\Templating\Helpers\HelperInterface;

class PhpEngine
{
    protected array $globals = [];
    protected array $helpers = [];
    protected array $parents = [];
    protected array $contentStack = [];
    protected string $current;
    protected Slots $slots;

    /**
     *
     */
    public function __construct(
        protected array $directories = []
    ) {
        $this->slots = new Slots();
    }

    /**
     *  Add a global variable available to all views.
     */
    public function addGlobal(string $name, mixed $value): void
    {
        $this->globals[$name] = $value;
    }

    /**
     * Add a helper for use in views.
     */
    public function addHelper(HelperInterface $helper)
    {
        $this->helpers[$helper->getName()] = $helper;
    }

    /**
     * Shortcut to call a helper.
     */
    public function __call(string $name, array $arguments = [])
    {
        if (isset($this->helpers[$name])) {
            return $this->helpers[$name];
        }

        throw new BadMethodCallException(sprintf('The method or helper "%s" does not exist', $name));
    }

    /**
     * Find a view file in the search directories.
     */
    public function find(string $file): string|false
    {
        foreach ($this->directories as $directory) {
            $path = $directory . '/' . $file;

            if (\file_exists($path)) {
                return $path;
            }
        }

        return false;
    }

    /**
     * Render a view with the supplied parameters.
     */
    public function render(string $viewFile, array $parameters = []): string
    {
        $this->current = $viewFile;
        $this->parents[$viewFile] = null;
        $viewPath = $this->find($viewFile);

        if (!$viewPath) {
            throw new Exception(sprintf('Unable to find view "%s"', $viewFile));
        }

        $parameters = array_replace($this->globals, $parameters);

        $view = $this;
        extract($parameters, \EXTR_SKIP);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($this->parents[$viewFile]) {
            if ($this->slots->has('content')) {
                $this->contentStack[] = $this->slots->get('content');
            }

            $this->slots->set('content', $content);
            $content = $this->render($this->parents[$viewFile], $parameters);

            if (count($this->contentStack)) {
                $this->slots->set('content', array_pop($this->contentStack));
            }
        }

        return $content;
    }

    /**
     * Extend a view, allowing inheritance.
     */
    public function extends(string $viewFile): void
    {
        $this->parents[$this->current] = $viewFile;
    }
}
