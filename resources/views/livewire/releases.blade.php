<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;
use Illuminate\Support\Str;

new class extends Component {
    public function fetchReleases()
    {
        return Cache::remember('mary-releases', 864000, function () {
            return Http::withToken(config('services.github.token'), type: 'token')->get("https://api.github.com/repos/robsontenorio/mary/releases")->collect()->take(5);
        });
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <div class="loading loading-spinner"></div>
        </div>
        HTML;
    }

    public function with(): array
    {
        return [
            'releases' => $this->fetchReleases()
        ];
    }
}; ?>

<div>
    @foreach($releases as $release)
        <x-list-item :item="$release" sub-value="created_at">
            <x-slot:actions>
                @if(Str::contains($release['body'], 'Breaking'))
                    <x-badge class="badge-error" value="breaking change" />
                @endif

                <x-button icon="o-arrow-up-right" :link="$release['html_url']" external class="btn-sm" />
            </x-slot:actions>
        </x-list-item>
    @endforeach
</div>
