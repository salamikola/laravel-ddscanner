<?php

namespace Salamikola\LaravelDDScanner\Commands;

use Illuminate\Console\Command;
use Salamikola\LaravelDDScanner\Services\DDScannerService;


class DDScannerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dd:scanner  {--path= : Specify the path the you want the scanner to start from. Note path can be a folder or a file}
                                        {--s : Show the list of affected files after scanning}
                                        {--comment : Comment out dd function instead of removing}
                                        {--rl= : Specify the recursive level of the scan}
                                        {--t : Should scan only the top directory, same as rl=1}
                                        {--ext=* : Specify the file extensions to be scan. More than one extension can be specified }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private DDScannerService $ddScannerService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DDScannerService $ddScannerService)
    {
        parent::__construct();
        $this->ddScannerService = $ddScannerService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->line('Starting DD Scanner');
            $affectedFiles = $this->ddScannerService->handle($this->options());
            if ($this->option('s')) {
                $commandTableArr = $this->convertFlatArrayToCommandTableArray($affectedFiles);
                $this->table(['FileNames'], $commandTableArr);
            }
            $this->line('Scanning Completed Successfully');
        } catch (\Exception $exception) {
            $this->error("An error Occurred " . $exception->getMessage());
        }
        return 0;
    }

    /**
     * @param array $arr
     * @return array|array[]
     */
    private function convertFlatArrayToCommandTableArray(array $arr): array
    {
        return array_map(function ($item) {
            return ['FileNames' => $item];
        }, $arr);
    }
}
