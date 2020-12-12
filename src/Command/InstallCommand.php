<?php

declare(strict_types=1);

namespace Stock\Command;

use Carbon\Carbon;
use Hyperf\Command\Command;
use League\Csv\Reader;
use League\Csv\Statement;
use Stock\Service\ProductService;
use Stock\Service\ProductStockService;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command
{
    private ProductService $products;

    private ProductStockService $stocks;

    private string $basePath = BASE_PATH . '/storage';

    public function __construct(ProductService $products, ProductStockService $stocks)
    {
        parent::__construct('app:install');

        $this->products = $products;
        $this->stocks = $stocks;

        $this->addOption(
            '--file',
            '-f',
            InputOption::VALUE_OPTIONAL,
            'Path to the CSV file with the inventory data',
            $this->basePath . '/inventory.csv'
        );
    }

    public function handle()
    {
        $this->info('Importing inventory data ...');
        $filename = $this->input->getOption('file');

        if (! file_exists($filename)) {
            throw new \LogicException(sprintf("CSV file %s doesn't exists.", $filename));
        }

        $product = $this->products->create('Fertiliser');
        $this->stocks->create($product->id);

        $this->loadCSV($filename, $product->id);
    }

    private function loadCSV(string $filename, int $productId)
    {
        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(0);

        $header = ['date', 'type', 'quantity', 'unit_price'];
        $stmt = (new Statement())->offset(0);

        foreach ($stmt->process($csv, $header) as $item) {
            if (strtolower($item['type']) == 'purchase') {
                $this->stocks->addItemToStock(
                    $productId,
                    (int) $item['quantity'],
                    (float) $item['unit_price'],
                    Carbon::createFromFormat('d/m/Y', $item['date'])
                );
            } else {
                $this->stocks->removeFromStock(
                    $productId,
                    abs((int) $item['quantity']),
                    Carbon::createFromFormat('d/m/Y', $item['date'])
                );
            }
        }
    }
}
