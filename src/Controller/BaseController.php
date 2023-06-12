<?php

namespace App\Controller;

use App\Transformer\HttpRequestTransformer;
use App\Transformer\HttpResponseTransformer;
use App\Transformer\ValidationErrorTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    protected HttpRequestTransformer $httpRequestTransformer;
    protected ValidatorInterface $validator;
    protected ValidationErrorTransformer $validationErrorTransformer;
    protected HttpResponseTransformer $httpResponseTransformer;

    public function __construct(
        HttpRequestTransformer $httpRequestTransformer,
        ValidatorInterface $validator,
        ValidationErrorTransformer $validationErrorTransformer,
        HttpResponseTransformer $httpResponseTransformer,
    ) {

        $this->httpRequestTransformer = $httpRequestTransformer;
        $this->validator = $validator;
        $this->validationErrorTransformer = $validationErrorTransformer;
        $this->httpResponseTransformer = $httpResponseTransformer;
    }
}
