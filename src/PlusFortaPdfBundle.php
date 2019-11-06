<?php

namespace PlusForta\PdfBundle;

use PlusForta\PdfBundle\DependencyInjection\PlusFortaPdfExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PlusFortaPdfBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new PlusFortaPdfExtension();
        }
        return $this->extension;
    }


}