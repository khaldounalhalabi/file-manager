<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\FileVersion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Jfcherng\Diff\Differ;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Renderer\RendererConstant;

class StoreDiffJob implements ShouldQueue
{
    use Queueable;

    public FileVersion $oldVersion;
    public FileVersion $newVersion;
    public File $file;

    /**
     * Create a new job instance.
     */
    public function __construct(File $file)
    {
        $versions = $file->fileVersions()->orderByDesc('version')->take(2)->get();
        $this->newVersion = $versions->first();
        $this->oldVersion = $versions->last();
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $differOptions = [
            'context' => Differ::CONTEXT_ALL,
            'ignoreCase' => false,
            'ignoreLineEnding' => true,
            'ignoreWhitespace' => true,
            'lengthLimit' => 100000,
            'fullContextIfIdentical' => true,
        ];
        $rendererOptions = [
            'detailLevel' => 'char',
            'language' => 'eng',
            'lineNumbers' => true,
            'separateBlock' => true,
            'showHeader' => true,
            'spacesToNbsp' => false,
            'tabSize' => 4,
            'mergeThreshold' => 0.8,
            'cliColorization' => RendererConstant::CLI_COLOR_ENABLE,
            'outputTagAsString' => false,
            'jsonEncodeFlags' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
            'wordGlues' => [' ', '-'],
            'resultForIdenticals' => null,
            'wrapperClasses' => ['diff-wrapper'],
        ];
        DiffHelper::getStyleSheet();
        $old = file_get_contents($this->oldVersion->file_path['absolute_path']);
        $new = file_get_contents($this->newVersion->file_path['absolute_path']);

        $this->file->update([
            'last_comparison' => DiffHelper::calculate($old, $new, 'Json', $differOptions)
        ]);
    }
}
