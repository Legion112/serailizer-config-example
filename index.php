<?php

require 'vendor/autoload.php';

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

$phpDocExtractor = new PhpDocExtractor();
$reflectionExtractor = new ReflectionExtractor();
$propertyInfoExtractor = new PropertyInfoExtractor(
    [$phpDocExtractor],
    [$phpDocExtractor, $reflectionExtractor],
    [$phpDocExtractor],
    [$reflectionExtractor],
    [$reflectionExtractor]
);

$normalizers = [
    new DateTimeNormalizer(),
    new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, $propertyInfoExtractor),
    new ArrayDenormalizer(),
];
$encoders = [new JsonEncoder()];

$serializer = new Serializer($normalizers, $encoders);

class A {
  public DateTimeImmutable $createAt;
};

$object = new A();
$object->createAt = new DateTimeImmutable();
$json = $serializer->serialize($object, 'json');
var_dump($json);

$deserializedObject = $serializer->deserialize($json, A::class, 'json');
var_dump($deserializedObject);