<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class SerieExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function displayToBadge(string $string)
    {
        $tableau = explode(' / ', $string);
        foreach($tableau as &$item) {
            $item = '<span class="badge text-bg-primary rounded-pill">' . $item . '</span>';
        }
        return implode(' ', $tableau);
    }

    public function displayToBadgeFunction(string $string, string $color = 'primary')
    {
        $tableau = explode(' / ', $string);
        foreach($tableau as &$item) {
            $item = '<span class="badge text-bg-'.$color.' rounded-pill">' . $item . '</span>';
        }
        return implode(' ', $tableau);
    }


}
