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
 * Class ForeignKeyConstraintViolationExceptionNormalizer
 *
 * @package App\Normalizer\Exception
 */
class ForeignKeyConstraintViolationExceptionNormalizer extends AbstractNormalizer
{
    /**
     * @inheritDoc
     */
    public function normalize(\Exception $exception)
    {
        $result['code'] = Response::HTTP_INTERNAL_SERVER_ERROR;

        $result['body'] = [
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exception->getMessage()
        ];

        return $result;
    }
}
