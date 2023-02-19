<?php

namespace App\Console\Commands\Scout;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\MeiliSearchEngine;
use Laravel\Scout\Searchable;
use Spatie\ModelInfo\ModelFinder;
use Spatie\ModelInfo\ModelInfo;

class ScoutSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:sync {model? : Class name of model to update settings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync your configuration with meilisearch';

    /**
     * The meilisearch client.
     */
    private MeiliSearchEngine $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = app(EngineManager::class)->driver('meilisearch');
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($model = $this->argument('model')) {
            $this->syncModel($model);

            return;
        }

        $this->syncAll();
    }

    private function syncModel(string $model): void
    {
        if ($this->hasSettings($model)) {
            $this->updateSettings(new $model);
        }
    }

    private function syncAll(): void
    {
        ModelFinder::all()
            ->filter(fn ($model) => in_array(Searchable::class, class_uses_recursive($model)))
            ->unique()
            ->each(
                function ($model) {
                    $this->syncModel($model);
                }
            );
    }

    private function updateSettings(Model $model): void
    {
        $index = $this->client->index($model->searchableAs());

        $meilisearchSettings = $model->meilisearchSettings;

        // make sure softdelete is filterable
        if (
            in_array(SoftDeletes::class, class_uses_recursive($model))
            && config('scout.soft_delete')
        ) {
            $meilisearchSettings['updateFilterableAttributes'] = array_merge(
                $meilisearchSettings['updateFilterableAttributes'] ?? [],
                ['__soft_deleted']
            );
        }

        collect($meilisearchSettings)->each(
            function ($value, $key) use ($index, $model) {
                if (! array_diff($value, ['*'])) {
                    $value = ModelInfo::forModel($model)
                        ->attributes->pluck(
                            'name'
                        )->toArray();
                }

                $status = $index->{$key}($value);

                $this->info(
                    class_basename($model).' '.
                    str_replace('update', '', $key).
                    ' has been updated, updateId: '.$status['taskUid']
                );
            }
        );
    }

    private function hasSettings(string $model): bool
    {
        return in_array(Searchable::class, class_uses_recursive($model))
            && (
                property_exists($model, 'meilisearchSettings')
                || in_array(SoftDeletes::class, class_uses_recursive($model))
            );
    }
}
