<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Normalizer;

/**
 * Class AbstractNormalizer
 *
 * @package App\Normalizer
 */
abstract class AbstractNormalizer implements NormalizerInterface
{
    /**
     * @var array
     */
    protected $exceptionTypes = array();

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function supports(\Exception $exception): bool
    {
        return in_array(get_class($exception), $this->exceptionTypes);
    }

    /**
     * @param string $exceptionType
     */
    public function setException(string $exceptionType)
    {
        $this->exceptionTypes[] = $exceptionType;
    }
}
