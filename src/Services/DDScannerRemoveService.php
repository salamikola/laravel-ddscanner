<?php

namespace Salamikola\LaravelDDScanner\Services;

class DDScannerRemoveService
{

    const PATTERN = '/\b(?:dd|ddd)[(].*?[)][;]/';

    /**
     * @throws \Exception
     */
    public function handle(string $filePath, bool $shouldComment): bool
    {
        $fileContent = file_get_contents($filePath);
        if (!$fileContent) {
            throw new \Exception("File cannot be found: $filePath");
        }
        $replaceText = $shouldComment ? '// ${0}' : '';
        $newFileContent = preg_replace(DDScannerRemoveService::PATTERN, $replaceText, $fileContent, -1, $count);
        if ($count == 0) {
            return false;
        }
        $numberOfByteWritten = file_put_contents($filePath,$newFileContent,LOCK_EX);
        if ($numberOfByteWritten === false){
            throw new \Exception("File cannot be updated: $filePath");
        }
        return true;
    }
}
