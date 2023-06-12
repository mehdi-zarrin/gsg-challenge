<?php

namespace App\Controller;

use App\DataTransferObject\Request\Voucher\VoucherDto;
use App\DataTransferObject\Request\Voucher\VoucherFilterDto;
use App\DataTransferObject\Response\ErrorResponse;
use App\DataTransferObject\Response\ValidationErrorResponseDto;
use App\Service\Voucher\Exception\VoucherNotFoundException;
use App\Service\Voucher\VoucherReader;
use App\Service\Voucher\VoucherWriter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VouchersController extends BaseController
{
    #[Route(path: "/vouchers", name: "vouchers_create", methods: "POST")]
    public function create(Request $request, VoucherWriter $voucherWriter)
    {
        $voucherRequest = $this->httpRequestTransformer->transform($request, VoucherDto::class);
        $validationErrors = $this->validator->validate($voucherRequest);

        if ($validationErrors->count() > 0) {
            return $this->validationErrorTransformer->transform(
                (new ValidationErrorResponseDto())->setErrors($validationErrors)
            );
        }

        $responseDto = $voucherWriter->create($voucherRequest);

        return $this->httpResponseTransformer->transform($responseDto, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param VoucherReader $voucherReader
     */
    #[Route(path: "/vouchers", name: "vouchers_list", methods: "GET")]
    public function list(Request $request, VoucherReader $voucherReader)
    {
        $voucherRequest = $this->httpRequestTransformer->transform($request, VoucherFilterDto::class);
        $responseDto = $voucherReader->handle($voucherRequest);

        return $this->httpResponseTransformer->transform($responseDto);
    }


    #[Route(path: "/vouchers/{id}", name: "vouchers_update", methods: "PUT")]
    public function update(Request $request, int $id, VoucherWriter $voucherWriter)
    {
        $voucherRequest = $this->httpRequestTransformer->transform($request, VoucherDto::class);
        $validationErrors = $this->validator->validate($voucherRequest);

        if ($validationErrors->count() > 0) {
            return $this->validationErrorTransformer->transform(
                (new ValidationErrorResponseDto())->setErrors($validationErrors)
            );
        }

        try {
            $responseDto = $voucherWriter->update($voucherRequest, $id);
        } catch (VoucherNotFoundException $e) {
            return $this->httpResponseTransformer->transform(
                (new ErrorResponse())->setMessage($e->getMessage())
                    ->setCode(Response::HTTP_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->httpResponseTransformer->transform($responseDto);
    }

    #[Route(path: "/vouchers/{id}", name: "vouchers_delete", methods: "DELETE")]
    public function delete(int $id, VoucherWriter $voucherWriter)
    {
        try {
            $voucherWriter->delete($id);
        } catch (VoucherNotFoundException $e) {
            return $this->httpResponseTransformer->transform(
                (new ErrorResponse())->setMessage($e->getMessage())
                    ->setCode(Response::HTTP_NOT_FOUND),
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
