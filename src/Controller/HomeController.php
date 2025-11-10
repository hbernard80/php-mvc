<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View\TemplateRenderer;
use App\Service\GreetingService;

final class HomeController
{
    public function __construct(
        private readonly GreetingService $greetingService,
        private readonly TemplateRenderer $templates,
    ) {
    }

    public function __invoke(): void
    {
        $vite = $this->resolveViteAssets();

        echo $this->templates->render('home/home', [
            'greeting' => $this->greetingService->greet('World'),
            'title' => 'Home',
            'vite' => $vite,
        ]);
    }

    /**
     * @return array{client: string|null, script: string, styles: array<int, string>}
     */
    private function resolveViteAssets(): array
    {
        $publicDir = dirname(__DIR__, 2) . '/public';
        $buildScript = '/build/main.js';
        $buildPath = $publicDir . $buildScript;

        if (is_file($buildPath)) {
            $styles = glob($publicDir . '/build/*.css') ?: [];
            $styles = array_map(
                static fn (string $path): string => str_replace($publicDir, '', $path),
                $styles,
            );

            return [
                'client' => null,
                'script' => $buildScript,
                'styles' => array_values($styles),
            ];
        }

        $devServerUrl = 'http://localhost:5173';

        return [
            'client' => $devServerUrl . '/@vite/client',
            'script' => $devServerUrl . '/main.js',
            'styles' => [],
        ];
    }
}
