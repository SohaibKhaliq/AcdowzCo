<?php

namespace Botble\Language\Commands;

use Botble\Base\Commands\Traits\ValidateCommandInput;
use Botble\Base\Supports\Language;
use Botble\Language\Facades\Language as LanguageFacade;
use Botble\Language\Models\Language as LanguageModel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand('cms:language:add', 'Add a new language to the system')]
class AddLanguageCommand extends Command implements PromptsForMissingInput
{
    use ValidateCommandInput;

    public function handle(): int
    {
        try {
            $languageId = $this->argument('language_id');
            $isDefault = $this->option('default');
            $order = $this->option('order');

            $languages = Language::getListLanguages();

            if (! isset($languages[$languageId])) {
                $matchingLanguages = $this->findMatchingLanguages($languageId, $languages);

                if (empty($matchingLanguages)) {
                    $this->components->error(sprintf(
                        'Language ID "%s" is not valid. Available language IDs: %s',
                        $languageId,
                        implode(', ', array_keys($languages))
                    ));

                    return self::FAILURE;
                } else {
                    $selectedLanguageId = $this->selectLanguageFromMatches($languageId, $matchingLanguages);

                    if ($selectedLanguageId === null) {
                        return self::FAILURE;
                    }

                    // Update the language ID to the selected one
                    $languageId = $selectedLanguageId;
                }
            }

            $language = $languages[$languageId];

            if ($this->languageExists($language[1], $languageId)) {
                $this->components->error(trans('plugins/language::language.added_already'));

                return self::FAILURE;
            }

            $this->ensureDirectoriesExist();
            $this->importLocaleIfMissing($language[0]);

            $this->createLanguage(
                $language[2],
                $language[0],
                $language[1],
                $language[4],
                $language[3] === 'rtl',
                $order,
                $isDefault
            );

            $this->clearRoutesCache();
            LanguageFacade::clearCache();

            $this->components->info(sprintf('Language "%s" has been added successfully!', $language[2]));

            return self::SUCCESS;
        } catch (Exception $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    protected function languageExists(string $code, string $languageId): bool
    {
        return LanguageModel::query()
            ->where('lang_code', $code)
            ->orWhere('lang_locale', $languageId)
            ->exists();
    }

    protected function ensureDirectoriesExist(): void
    {
        File::ensureDirectoryExists(lang_path('vendor'));

        if (! File::isWritable(lang_path()) || ! File::isWritable(lang_path('vendor'))) {
            throw new Exception(
                trans('plugins/translation::translation.folder_is_not_writeable', ['lang_path' => lang_path()])
            );
        }
    }

    protected function importLocaleIfMissing(string $locale): void
    {
        $defaultLocale = lang_path($locale);

        if (File::exists($defaultLocale)) {
            return;
        }

        File::copyDirectory(lang_path('en'), $defaultLocale);

        $this->components->info(sprintf('Created locale folder for "%s"', $locale));
    }

    protected function createLanguage(
        string $name,
        string $locale,
        string $code,
        string $flag,
        bool $isRtl,
        int $order,
        bool $isDefault
    ): LanguageModel {
        if ($isDefault) {
            LanguageModel::query()->where('lang_is_default', 1)->update(['lang_is_default' => 0]);
        }

        if (! LanguageModel::query()->exists()) {
            $isDefault = true;
        }

        return LanguageModel::query()->create([
            'lang_name' => $name,
            'lang_locale' => $locale,
            'lang_code' => $code,
            'lang_flag' => $flag,
            'lang_is_rtl' => $isRtl,
            'lang_order' => $order,
            'lang_is_default' => $isDefault,
        ]);
    }

    protected function clearRoutesCache(): void
    {
        $this->call('route:clear');
    }

    protected function findMatchingLanguages(string $input, array $languages): array
    {
        $matches = [];
        $input = strtolower($input);

        foreach ($languages as $languageId => $languageData) {
            $languageIdLower = strtolower($languageId);
            $languageName = $this->normalizeString($languageData[2]);
            $languageCode = strtolower($languageData[1]);

            if (str_starts_with($languageIdLower, $input) ||
                str_contains($languageName, $input) ||
                str_starts_with($languageCode, $input) ||
                str_contains($languageName, $this->normalizeString($input))) {
                $matches[$languageId] = $languageData;
            }
        }

        return $matches;
    }

    protected function normalizeString(string $string): string
    {
        $string = strtolower($string);

        $accents = [
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n',
        ];

        return str_replace(array_keys($accents), array_values($accents), $string);
    }

    protected function selectLanguageFromMatches(string $input, array $matchingLanguages): ?string
    {
        $this->components->warn(sprintf('Language ID "%s" is not valid.', $input));
        $this->components->info('Did you mean one of these languages?');
        $this->newLine();

        $languageIds = [];
        $index = 0;

        foreach ($matchingLanguages as $languageId => $languageData) {
            $this->line(sprintf(
                '  [<fg=green>%d</>] %s - %s (%s)',
                $index,
                $languageId,
                $languageData[2],
                $languageData[1]
            ));
            $languageIds[$index] = $languageId;
            $index++;
        }

        $this->line(sprintf('  [<fg=green>%d</>] Cancel - Don\'t add any language', $index));
        $languageIds[$index] = null;

        $this->newLine();

        $maxIndex = $index;
        $selectedIndex = $this->ask(
            sprintf('Please select an option (0-%d)', $maxIndex),
            null
        );

        if (! is_numeric($selectedIndex) || $selectedIndex < 0 || $selectedIndex > $maxIndex) {
            $this->components->error('Invalid selection.');

            return null;
        }

        $selectedIndex = (int) $selectedIndex;

        if ($languageIds[$selectedIndex] === null) {
            $this->components->info('Language addition cancelled.');

            return null;
        }

        return $languageIds[$selectedIndex];
    }

    protected function configure(): void
    {
        $this
            ->addArgument('language_id', InputArgument::REQUIRED, 'The ID of the language (e.g. en_US)')
            ->addOption('order', null, InputOption::VALUE_OPTIONAL, 'The order of the language', 0)
            ->addOption('default', null, InputOption::VALUE_NONE, 'Whether the language is default');
    }
}
