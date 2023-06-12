<?php

namespace App\Controller;

use App\DataTransferObject\Request\Order\OrderDto;
use App\DataTransferObject\Request\Order\OrderListDto;
use App\DataTransferObject\Response\ErrorResponse;
use App\DataTransferObject\Response\ValidationErrorResponseDto;
use App\Service\Order\OrderReader;
use App\Service\Order\OrderWriter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrdersController extends BaseController
{
    #[Route(path: '/orders', name: 'orders_create', methods: ['POST'])]
    public function create(Request $request, OrderWriter $orderWriter)
    {
        $orderRequest = $this->httpRequestTransformer->transform($request, OrderDto::class);
        $validationErrors = $this->validator->validate($orderRequest);

        if ($validationErrors->count() > 0) {
            return $this->validationErrorTransformer->transform(
                (new ValidationErrorResponseDto())->setErrors($validationErrors)
            );
        }

        $statusCode = Response::HTTP_CREATED;
        try {
            $responseDto = $orderWriter->create($orderRequest);
        } catch (\Exception $e) {
            $responseDto = (new ErrorResponse())
                ->setMessage($e->getMessage())
                ->setCode(Response::HTTP_BAD_REQUEST);
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        return $this->httpResponseTransformer->transform($responseDto, $statusCode);
    }

    #[Route(path: '/orders', name: 'orders_list', methods: ['GET'])]
    public function list(Request $request, OrderReader $orderReader)
    {
        $orderRequest = $this->httpRequestTransformer->transform($request, OrderListDto::class);
        $responseDto = $orderReader->getList($orderRequest);

        return $this->httpResponseTransformer->transform($responseDto);
    }
}