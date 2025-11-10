<?php

declare(strict_types=1);

namespace App\Core\View;

use InvalidArgumentException;

use function extract;
use function is_file;
use function ltrim;
use function ob_get_clean;
use function ob_start;
use function rtrim;
use function sprintf;

final class TemplateRenderer
{
    public function __construct(private readonly string $templatesPath)
    {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function render(string $template, array $context = []): string
    {
        $templateFile = rtrim($this->templatesPath, '/\\') . '/' . ltrim($template, '/\\') . '.php';

        if (!is_file($templateFile)) {
            throw new InvalidArgumentException(sprintf('Template "%s" not found.', $template));
        }

        return $this->renderFile($templateFile, $context);
    }

    /**
     * @param array<string, mixed> $variables
     */
    private function renderFile(string $file, array $variables = []): string
    {
        $render = static function (string $file, array $variables): string {
            extract($variables, EXTR_OVERWRITE);

            ob_start();
            require $file;

            return (string) ob_get_clean();
        };

        return $render($file, $variables);
    }
}
