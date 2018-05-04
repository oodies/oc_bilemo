<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Normalizer;

/**
 * Class NormalizerInterface
 *
 * @package App\Normalizer
 */
interface NormalizerInterface
{
    /**
     * @param \Exception $exception
     */
    public function normalize(\Exception $exception);

    /**
     * @param \Exception $exception
     */
    public function supports(\Exception $exception);
}
