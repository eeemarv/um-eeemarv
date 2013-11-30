<?php

namespace Eeemarv\EeemarvBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


class CurrencyTransformer implements DataTransformerInterface
{
    /**
     * @var integer
     */
    private $currencyRate;

    /**
     * @param integer $currencyRate
     */
    public function __construct($currencyRate)
    {
        $this->currencyRate = $currencyRate;
    }

    /**
     * Transforms internal amount (lets-seconds) to local currency.
     *
     * @param  integer|null $internalAmount
     * @return integer
     */
    public function transform($internalAmount)
    {
        if (null === $internalAmount) {
            return 0;
        }

        return round($internalAmount / $this->currencyRate);
    }

    /**
     * Transforms amount to internal amount (lets-seconds).
     *
     * @param  integer $amount
     * @return integer|null
     */
    public function reverseTransform($amount)
    {
        if (!$amount) {
            return null;
        }

        return $amount * $this->currencyRate;
    }
}
