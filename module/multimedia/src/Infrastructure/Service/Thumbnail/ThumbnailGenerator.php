<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Thumbnail;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Storage\MultimediaStorageInterface;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\FileExistsException;

/**
 */
class ThumbnailGenerator
{
    /**
     * @var MultimediaStorageInterface
     */
    private MultimediaStorageInterface $storage;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $thumbnailStorage;

    /**
     * @var TempFileStorage
     */
    private TempFileStorage $temp;

    /**
     * @var ThumbnailGenerationStrategyProvider
     */
    private ThumbnailGenerationStrategyProvider $provider;

    /**
     * @param MultimediaStorageInterface          $storage
     * @param FilesystemInterface                 $thumbnailStorage
     * @param TempFileStorage                     $temp
     * @param ThumbnailGenerationStrategyProvider $provider
     */
    public function __construct(
        MultimediaStorageInterface $storage,
        FilesystemInterface $thumbnailStorage,
        TempFileStorage $temp,
        ThumbnailGenerationStrategyProvider $provider
    ) {
        $this->storage = $storage;
        $this->thumbnailStorage = $thumbnailStorage;
        $this->temp = $temp;
        $this->provider = $provider;
    }

    /**
     * @param Multimedia $multimedia
     * @param string     $type
     *
     * @throws FileExistsException
     * @throws \ImagickException
     */
    public function generate(Multimedia $multimedia, string $type): void
    {
        $strategy = $this->provider->provide($type);
        $content = $this->storage->read($multimedia->getFileName());
        $this->temp->create($multimedia->getFileName());
        $this->temp->append([$content]);
        $this->temp->close();

        $filename = $this->temp->getDirectory().'/'.$multimedia->getFileName();
        $imagick = new \Imagick(realpath($filename));
        $imagick = $strategy->generate($imagick);
        $this->temp->clean($filename);

        $newFilename = sprintf('%s/%s.png', $this->temp->getDirectory(), $multimedia->getId()->getValue());
        $imagick->writeImage($newFilename);
        $imagick->destroy();

        $handler = fopen($newFilename, 'rb');
        $this->thumbnailStorage->writeStream(sprintf('%s/%s', $type, basename($newFilename)), $handler);
        $this->temp->clean($newFilename);
    }
}