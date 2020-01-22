<?php

namespace App\Normalizer;

use Symfony\Component\HttpFoundation\Response;

class NotAcceptableNormalizer extends AbstractNormalizer
{
    public function normalize(\Exception $exception)
    {
        $result['code'] = Response::HTTP_NOT_ACCEPTABLE;

        $result['body'] = [
            'code' => Response::HTTP_NOT_ACCEPTABLE,
            'message' => $exception->getMessage()
        ];

        return $result;
    }
}