<?php

namespace App\PathGenerators;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomPathGenerator implements PathGenerator
{
    protected DefaultPathGenerator $defaultGenerator;

    public function __construct()
    {
        $this->defaultGenerator = new DefaultPathGenerator();
    }

    public function getPath(Media $media): string
    {
        if ($media->collection_name === 'Additional_InformationFiles' && $media->disk === 'spaces') {
            $date = now()->format('Y-m-d');
            $clientName =  $media->getCustomProperty('client_name', 'default-client');
            $address = $media->getCustomProperty('address', 'default-address');
            $location = $media->getCustomProperty('location', 'default-location');

            return "{$clientName}/{$address}/{$date}/{$location}/";
        }

        return $this->defaultGenerator->getPath($media);
    }

    public function getPathForConversions(Media $media): string
    {
        if ($media->collection_name === 'Additional_InformationFiles' && $media->disk === 'spaces') {
            return $this->getPath($media) . 'conversions/';
        }

        return $this->defaultGenerator->getPathForConversions($media);
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        if ($media->collection_name === 'Additional_InformationFiles' && $media->disk === 'spaces') {
            return $this->getPath($media) . 'responsive-images/';
        }

        return $this->defaultGenerator->getPathForResponsiveImages($media);
    }
}
