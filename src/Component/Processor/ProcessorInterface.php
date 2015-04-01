<?php

namespace Component\Processor;

interface ProcessorInterface
{
    /**
     * Get a list of mime types the processor module can handle
     * @return array List of mime types that the processor can handle
     */
    public function getSupportedTypes();
}
