<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Console;

use Commanded\Core\ValueObject\DateTime\DateTime;
use Commanded\Core\ValueObject\Money\CurrencyCode;
use Commanded\Core\ValueObject\Money\Money;
use Hyperf\Command\Command;
use League\Csv\Reader;
use League\Csv\Statement;
use Stock\Application\ProductService;
use Stock\Application\StockService;
use Stock\Domain\ProductId;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command
{
    private ProductService $products;

    private StockService $stocks;

    private CurrencyCode $currency;

    private string $basePath = BASE_PATH . '/storage';

    public function __construct(ProductService $products, StockService $stocks, CurrencyCode $currency)
    {
        parent::__construct('app:install');

        $this->products = $products;
        $this->stocks = $stocks;
        $this->currency = $currency;

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

        $productId = ProductId::next();
        $this->products->create($productId, 'Fertiliser');

        $this->loadCSV($filename, $productId);
    }

    private function loadCSV(string $filename, ProductId $productId)
    {
        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(0);

        $header = ['date', 'type', 'quantity', 'unit_price'];
        $stmt = (new Statement())->offset(0);

        foreach ($stmt->process($csv, $header) as $item) {
            if (strtolower($item['type']) == 'purchase') {
                $this->stocks->addItem(
                    $productId,
                    (int) $item['quantity'],
                    Money::fromAmount((float) $item['unit_price'], $this->currency),
                    DateTime::fromFormat('d/m/Y', $item['date'])
                );
            } else {
                $this->stocks->applyQuantity(
                    $productId,
                    abs((int) $item['quantity']),
                    DateTime::fromFormat('d/m/Y', $item['date'])
                );
            }
        }
    }
}
