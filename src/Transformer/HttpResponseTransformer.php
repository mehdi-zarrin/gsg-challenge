<?php

namespace App\Transformer;

use App\Contracts\ServiceResponseInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class HttpResponseTransformer
{
    private const RESPONSE_WRAPPER_STRING = 'data';

    /**
     * @param ServiceResponseInterface $responseDto
     * @param string $statusCode
     * @return Response
     */
    public function transform(ServiceResponseInterface $responseDto, string $statusCode = Response::HTTP_OK): Response
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $normalizer = new ObjectNormalizer(
            nameConverter: $metadataAwareNameConverter,
            propertyTypeExtractor: new ReflectionExtractor()
        );
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);
        $normalizedData = $serializer->normalize(
            $responseDto,
        );

        $response = [
            static::RESPONSE_WRAPPER_STRING => $normalizedData
        ];

        return new JsonResponse($response, $statusCode);
    }
}
