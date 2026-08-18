<?php

namespace App\Console\Commands;

use App\Services\Donations\StripeCheckoutImporter;
use Illuminate\Console\Command;

class ImportStripeDonations extends Command
{
    protected $signature = 'donations:import-stripe
                            {--dry-run : Preview sessions without writing to the database}
                            {--payment-link= : Optional payment link filter}';

    protected $description = 'Import historical paid Stripe checkout sessions into the donations table';

    public function handle(StripeCheckoutImporter $importer): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $paymentLinkId = $this->option('payment-link') ?: config('donations.stripe_payment_link_id');

        if ($dryRun) {
            $this->warn('Dry run — no database writes.');
        }

        try {
            $result = $importer->import(
                dryRun: $dryRun,
                paymentLinkId: is_string($paymentLinkId) && $paymentLinkId !== '' ? $paymentLinkId : null,
            );
        } catch (\InvalidArgumentException|\RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Processed %d Stripe page(s): %d imported, %d skipped.',
            $result['pages'],
            $result['imported'],
            $result['skipped'],
        ));

        if (! $dryRun) {
            $this->comment('Donation goal cache will refresh on the next request.');
        }

        return self::SUCCESS;
    }
}
