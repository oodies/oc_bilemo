<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/05
 */

namespace App\Normalizer\Exception;

use App\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NotFoundHttpExceptionNormalizer
 *
 * @package App\Normalizer\Exception
 */
class NotFoundHttpExceptionNormalizer extends AbstractNormalizer
{
    /**
     * @inheritDoc
     */
    public function normalize(\Exception $exception)
    {
        $result['code'] = Response::HTTP_NOT_FOUND;

        $result['body'] = [
            'code' => Response::HTTP_NOT_FOUND,
            'message' => $exception->getMessage()
        ];

        return $result;
    }
}
