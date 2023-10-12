<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\SerieExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SerieExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('displayToBadge', [SerieExtensionRuntime::class, 'displayToBadge'], ['is_safe' => ['html']])
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('displayToBadgeFunction', [SerieExtensionRuntime::class, 'displayToBadgeFunction'], ['is_safe' => ['html']])
        ];
    }
}
