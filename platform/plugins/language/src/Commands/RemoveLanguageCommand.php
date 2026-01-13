<?php

namespace Botble\Language\Commands;

use Botble\Base\Commands\Traits\ValidateCommandInput;
use Botble\Language\Facades\Language as LanguageFacade;
use Botble\Language\Models\Language as LanguageModel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand('cms:language:remove', 'Remove a language from the system')]
class RemoveLanguageCommand extends Command implements PromptsForMissingInput
{
    use ValidateCommandInput;

    public function handle(): int
    {
        try {
            $languageCode = $this->argument('language_code');
            $force = $this->option('force');

            $availableLanguages = LanguageModel::query()->get();

            if ($availableLanguages->count() <= 1) {
                $this->components->error('Cannot remove language. At least one language must remain in the system.');

                return self::FAILURE;
            }

            if ($languageCode) {
                $language = $this->findLanguageByCodeOrLocale($languageCode, $availableLanguages);

                if (! $language) {
                    return self::FAILURE;
                }
            } else {
                $language = $this->selectLanguageToRemove($availableLanguages);

                if (! $language) {
                    return self::FAILURE;
                }
            }

            if ($language->lang_is_default) {
                $this->components->error('Cannot remove the default language. Please set another language as default first.');

                return self::FAILURE;
            }

            if (! $force && ! $this->confirmRemoval($language)) {
                $this->components->info('Language removal cancelled.');

                return self::FAILURE;
            }

            $this->removeLanguage($language);

            $this->components->info(sprintf('Language "%s" (%s) has been removed successfully!', $language->lang_name, $language->lang_code));

            return self::SUCCESS;
        } catch (Exception $exception) {
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    protected function findLanguageByCodeOrLocale(string $input, $availableLanguages): ?LanguageModel
    {
        $language = $availableLanguages->where('lang_code', $input)->first();

        if ($language) {
            return $language;
        }

        $languagesByLocale = $availableLanguages->where('lang_locale', $input);

        if ($languagesByLocale->isEmpty()) {
            $this->components->error(sprintf('Language with code or locale "%s" not found.', $input));
            $this->displayAvailableLanguages($availableLanguages);

            return null;
        }

        if ($languagesByLocale->count() === 1) {
            $language = $languagesByLocale->first();

            if ($language->lang_is_default) {
                $this->components->error('Cannot remove the default language. Please set another language as default first.');

                return null;
            }

            $this->components->info(sprintf('Found language by locale: %s (%s)', $language->lang_name, $language->lang_code));

            if (! $this->option('force') && ! $this->confirmRemoval($language)) {
                $this->components->info('Language removal cancelled.');

                return null;
            }

            return $language;
        }

        return $this->selectFromMultipleLocaleMatches($input, $languagesByLocale);
    }

    protected function selectFromMultipleLocaleMatches(string $locale, $matchingLanguages): ?LanguageModel
    {
        $this->components->warn(sprintf('Multiple languages found with locale "%s".', $locale));
        $this->components->info('Please select which language to remove:');
        $this->newLine();

        $removableLanguages = $matchingLanguages->where('lang_is_default', false);

        if ($removableLanguages->isEmpty()) {
            $this->components->error('No languages available for removal. All matching languages are default languages.');

            return null;
        }

        $languageIds = [];
        $index = 0;

        foreach ($removableLanguages as $language) {
            $this->line(sprintf(
                '  [<fg=green>%d</>] %s (%s)',
                $index,
                $language->lang_name,
                $language->lang_code
            ));
            $languageIds[$index] = $language;
            $index++;
        }

        $this->line(sprintf('  [<fg=green>%d</>] Cancel - Don\'t remove any language', $index));
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
            $this->components->info('Language removal cancelled.');

            return null;
        }

        $selectedLanguage = $languageIds[$selectedIndex];

        if (! $this->option('force') && ! $this->confirmRemoval($selectedLanguage)) {
            $this->components->info('Language removal cancelled.');

            return null;
        }

        return $selectedLanguage;
    }

    protected function selectLanguageToRemove($availableLanguages): ?LanguageModel
    {
        $this->components->info('Select a language to remove:');
        $this->newLine();

        $removableLanguages = $availableLanguages->where('lang_is_default', false);

        if ($removableLanguages->isEmpty()) {
            $this->components->error('No languages available for removal. The default language cannot be removed.');

            return null;
        }

        $languageIds = [];
        $index = 0;

        foreach ($removableLanguages as $language) {
            $this->line(sprintf(
                '  [<fg=green>%d</>] %s (%s)',
                $index,
                $language->lang_name,
                $language->lang_code
            ));
            $languageIds[$index] = $language;
            $index++;
        }

        $this->line(sprintf('  [<fg=green>%d</>] Cancel - Don\'t remove any language', $index));
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
            $this->components->info('Language removal cancelled.');

            return null;
        }

        return $languageIds[$selectedIndex];
    }

    protected function confirmRemoval(LanguageModel $language): bool
    {
        $this->components->warn('This action will permanently remove the language and all its associated data.');
        $this->newLine();
        $this->line(sprintf('Language: <fg=yellow>%s (%s)</>', $language->lang_name, $language->lang_code));
        $this->newLine();

        return $this->confirm('Are you sure you want to remove this language?', false);
    }

    protected function removeLanguage(LanguageModel $language): void
    {
        $locale = $language->lang_locale;

        $language->delete();

        $this->removeLocaleDirectory($locale);
        $this->clearRoutesCache();
        LanguageFacade::clearCache();
    }

    protected function removeLocaleDirectory(string $locale): void
    {
        $localePath = lang_path($locale);

        if (File::exists($localePath) && File::isDirectory($localePath)) {
            File::deleteDirectory($localePath);
            $this->components->info(sprintf('Removed locale directory for "%s"', $locale));
        }
    }

    protected function clearRoutesCache(): void
    {
        $this->call('route:clear');
    }

    protected function displayAvailableLanguages($availableLanguages): void
    {
        $this->components->info('Available languages:');
        $this->newLine();

        foreach ($availableLanguages as $language) {
            $defaultText = $language->lang_is_default ? ' (default)' : '';
            $this->line(sprintf(
                '  %s - Code: %s, Locale: %s%s',
                $language->lang_name,
                $language->lang_code,
                $language->lang_locale,
                $defaultText
            ));
        }
    }

    protected function configure(): void
    {
        $this
            ->addArgument('language_code', InputArgument::OPTIONAL, 'The code of the language to remove (e.g. fr_FR)')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force removal without confirmation');
    }
}
