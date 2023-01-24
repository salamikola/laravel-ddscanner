<?php

namespace Salamikola\LaravelDDScanner\Services;

class DDScannerService
{


    private int $depth = 1;
    private int $recursiveLevel;
    private string $startPath;
    private array $extensions;
    private bool $shouldCommentNotRemove;
    private bool $shouldKeepAffectedFiles;
    private array $affectedFiles = [];
    private DDScannerRemoveService $ddScannerRemoveService;


    public function __construct(DDScannerRemoveService $ddScannerRemoveService)
    {
        $this->ddScannerRemoveService = $ddScannerRemoveService;
    }

    /**
     * @param string $path
     * @return array
     * @throws \Exception
     */
    private function getFolderItems(string $path): array
    {
        $folderItems = array_values(array_diff(scandir($path), array('.', '..')));
        if (!$folderItems) {
            throw new \Exception("Folder cannot be found : $path");
        }
        return $folderItems;

    }

    /**
     * @param string $file
     * @return string
     */
    private function getFileFullExtension(string $file): string
    {
        $fileNameParts = explode('.', $file);
        return implode(', ', array_slice($fileNameParts, 1));
    }

    /**
     * @param array $items
     * @return array
     */
    private function filterFolderItems(array $items,string $path): array
    {
        if (count($this->extensions) == 0) {
            return $items;
        }
        $newItems = [];
        foreach ($items as $item) {
            if (is_file($path .'\\'.$item)) {
                $fullExt = $this->getFileFullExtension($item);
                if (in_array($fullExt, $this->extensions)) {
                    $newItems[] = $item;
                }
                continue;
            }
            $newItems[] = $item;
        }
        return $newItems;
    }

    /**
     * @param array $props
     * @return void
     */
    private function setProps(array $props): void
    {
        $this->setPath($props['path'] ?? null);
        $this->setExtensions($props['ext'] ?? null);
        $this->setRecursiveLevel($props['rl'] ?? null, $props['t'] ?? null);
        $this->setShouldCommentNotRemove($props['comment']);
        $this->setShouldKeepAffectedFiles($props['s']);
    }

    /**
     * @param string|null $path
     * @return void
     */
    private function setPath(string|null $path): void
    {
        if ($path) {
            $this->startPath = $path;
        } else {
            $this->startPath = base_path() . '\app';
        }

    }

    /**
     * @param array|null $extensions
     * @return void
     */
    private function setExtensions(array|null $extensions): void
    {
        $this->extensions = $extensions;
    }

    /**
     * @param int|null $recursiveLevel
     * @param bool|null $recurseOnlyTopLevel
     * @return void
     */
    private function setRecursiveLevel(int|null $recursiveLevel, bool|null $recurseOnlyTopLevel): void
    {
        $this->recursiveLevel = $recurseOnlyTopLevel ? 1 : $recursiveLevel ?? PHP_INT_MAX;
    }

    /**
     * @param bool $shouldCommentNotRemove
     * @return void
     */
    private function setShouldCommentNotRemove(bool $shouldCommentNotRemove): void
    {
        $this->shouldCommentNotRemove = $shouldCommentNotRemove;
    }

    /**
     * @param bool $shouldKeepAffectedFile
     * @return void
     */
    private function setShouldKeepAffectedFiles(bool $shouldKeepAffectedFile): void
    {
        $this->shouldKeepAffectedFiles = $shouldKeepAffectedFile;
    }

    /**
     * @param $filePath
     * @return void
     * @throws \Exception
     */
    private function scanFile($filePath): void
    {
        $containPattern = $this->ddScannerRemoveService->handle($filePath, $this->shouldCommentNotRemove);
        if ($this->shouldKeepAffectedFiles) {
            if ($containPattern) $this->affectedFiles [] = $filePath;
        }
    }

    /**
     * @throws \Exception
     */
    private function startScan(): void
    {
        if (is_file($this->startPath)) {
            $this->scanFile($this->startPath);
            return;
        }
        $this->scanFolder($this->startPath);
    }

    /**
     * @throws \Exception
     */
    private function scanFolder($path): void
    {
        $items = $this->getFolderItems($path);
        $filteredItems = $this->filterFolderItems($items,$path);
        foreach ($filteredItems as $filteredItem) {
            $newPath = '' . $path . '\\' . $filteredItem;
            if (is_dir($newPath)) {
                if ($this->depth < $this->recursiveLevel) {
                    $this->depth++;
                    $this->scanFolder($newPath);
                }
            } else {
                $this->scanFile($newPath);
            }
        }
        $this->depth--;
    }

    /**
     * @param array $props
     * @return array
     * @throws \Exception
     */
    public function handle(array $props): array
    {
        $this->setProps($props);
        $this->startScan();
        return $this->affectedFiles;
    }

}
