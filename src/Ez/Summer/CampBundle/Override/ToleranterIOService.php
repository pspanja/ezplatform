<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Summer\CampBundle\Override;

use eZ\Publish\Core\IO\TolerantIOService;
use eZ\Publish\Core\IO\Exception\BinaryFileNotFoundException;
use eZ\Publish\Core\IO\Values\MissingBinaryFile;

/**
 * A tolerant IO service that is even more tolerant
 */
class ToleranterIOService extends TolerantIOService
{
    /**
     * Loads the binary file with $binaryFileId.
     *
     * @param string $binaryFileId
     *
     * @return \eZ\Publish\Core\IO\Values\BinaryFile|\eZ\Publish\Core\IO\Values\MissingBinaryFile
     *
     * @throws \eZ\Publish\Core\IO\Exception\InvalidBinaryFileIdException
     */
    public function loadBinaryFile($binaryFileId)
    {
        $this->checkBinaryFileId($binaryFileId);

        if ($binaryFileId[0] === '/') {
            //throw new InvalidBinaryFileIdException($binaryFileId, 'Binary file ids can not begin with a /');
        }

        try {
            $spiBinaryFile = $this->metadataHandler->load($this->getPrefixedUri($binaryFileId));
        } catch (BinaryFileNotFoundException $e) {
            $this->logMissingFile($binaryFileId);

            return $this->createMissingBinaryFile($binaryFileId);
        }

        if (!isset($spiBinaryFile->uri)) {
            $spiBinaryFile->uri = $this->binarydataHandler->getUri($spiBinaryFile->id);
        }

        return $this->buildDomainBinaryFileObject($spiBinaryFile);
    }

    /**
     * @param $binaryFileId
     *
     * @return \eZ\Publish\Core\IO\Values\MissingBinaryFile
     */
    private function createMissingBinaryFile($binaryFileId)
    {
        return new MissingBinaryFile(
            array(
                'id' => $binaryFileId,
                'uri' => $this->binarydataHandler->getUri($this->getPrefixedUri($binaryFileId)),
            )
        );
    }

    private function logMissingFile($id)
    {
        if (!isset($this->logger)) {
            return;
        }
        $this->logger->info("BinaryFile with id $id not found");
    }
}
