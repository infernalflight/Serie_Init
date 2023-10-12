<?php

namespace App\Validator;

use App\Entity\Serie;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SerieValidator
{
    public static function validate(Serie $serie, ExecutionContextInterface $context)
    {
        if (\in_array($serie->getStatus(), ['ended', 'canceled']) && !$serie->getLastAirDate()) {
            $context->buildViolation("Si la série est canceled ou abandonned alors il FAUT une date de fin")
                ->atPath('lastAirDate')
                ->addViolation();
        }

        if (!\in_array($serie->getStatus(), ['ended', 'canceled']) && $serie->getLastAirDate()) {
            $context->buildViolation("Si la série n'est ni canceled ni abandonned alors POURQUOI une date de fin ???")
                ->atPath('lastAirDate')
                ->addViolation();
        }
    }
}
