<?php

namespace MintHCM\MintCLI\Commands;

use MintHCM\MintCLI\Services\UpgradeRequirementsService;
use MintHCM\MintCLI\Services\UpgradeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Upgrade extends Command
{
    protected static $defaultName = 'upgrade';
    protected static $defaultDescription = 'Upgrade MintHCM to a specific version tag';

    private SymfonyStyle $io;
    private InputInterface $input;
    private UpgradeService $upgrade_service;
    private UpgradeRequirementsService $requirements_service;
    private array $skip_steps = [];
    private array $intermediate_upgrade_error = [];

    protected function configure(): void
    {
        $this
            ->setHelp('Upgrades MintHCM to the specified git tag. Reads version requirements directly from the target tag without checking it out first.')
            ->addOption('tag', 't', InputOption::VALUE_REQUIRED, 'Target version tag to upgrade to (e.g. 4.3.1)')
            ->addOption('skip-checks', null, InputOption::VALUE_NONE, 'Skip environment requirement checks (not recommended)')
            ->addOption('git-user', null, InputOption::VALUE_REQUIRED, 'Git username for HTTPS authentication')
            ->addOption('git-pass', null, InputOption::VALUE_REQUIRED, 'Git password or personal access token for HTTPS authentication')
            ->addOption('yes', 'y', InputOption::VALUE_NONE, 'Automatically confirm all prompts (for non-interactive/batch use)')
            ->addOption('owner', null, InputOption::VALUE_REQUIRED, 'File owner to set as user:group (e.g. www-data:www-data)', 'www-data:www-data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->input = $input;
        $this->upgrade_service = new UpgradeService($output);
        $this->requirements_service = new UpgradeRequirementsService();

        $this->io->title('MintHCM Upgrade');

        $current_version = $this->upgrade_service->getCurrentVersion();

        $tag = $this->resolveTag($input, $current_version);
        if ($tag === null) {
            return Command::FAILURE;
        }

        $effective_upgrade = $this->resolveEffectiveUpgradeVersion($tag, $current_version);
        if ($effective_upgrade === null) {
            $steps_path = implode(' → ', array_merge([$current_version], $this->intermediate_upgrade_error, [$tag]));
            $this->io->error([
                "Cannot upgrade directly from {$current_version} to {$tag}.",
                "Multiple intermediate upgrades are required.",
                "Please upgrade step by step: {$steps_path}",
            ]);
            return Command::FAILURE;
        }

        $this->io->text("Current version : <info>{$current_version}</info>");
        $this->io->text("Target version  : <info>{$tag}</info>");
        if ($effective_upgrade !== $tag) {
            $this->io->text("Upgrade scripts : <comment>{$effective_upgrade}</comment>");
        }
        $this->io->newLine();

        $this->resolveGitCredentials($input);

        $state = $this->upgrade_service->loadState();
        $completed_steps = [];

        if ($state !== null && ($state['tag'] ?? '') === $tag) {
            if (!$this->confirmResume($state)) {
                return Command::SUCCESS;
            }
            $completed_steps = $state['completed_steps'] ?? [];
            $effective_upgrade = $state['effective_upgrade'] ?? $effective_upgrade;
        } else {
            if ($state !== null) {
                $this->io->warning("Found an interrupted upgrade state for tag '{$state['tag']}', but you selected '{$tag}'. The old state will be discarded.");
                $this->upgrade_service->clearState();
            }
            if (!$this->checkRequirements($tag, $current_version, (bool) $input->getOption('skip-checks'), $effective_upgrade)) {
                return Command::FAILURE;
            }
            if (!$this->confirmUpgrade($current_version, $tag)) {
                return Command::SUCCESS;
            }
        }

        $owner = (string) $input->getOption('owner');

        $steps = [
            'pre_upgrade'               => fn() => $this->runPreUpgrade($effective_upgrade),
            'fetch_and_checkout'        => fn() => $this->fetchAndCheckout($tag),
            'apply_permissions'         => fn() => $this->applyPermissions($owner),
            'instance_rebuild'          => fn() => $this->runInstanceRebuild(),
            'migrations'                => fn() => $this->runMigrations($effective_upgrade),
            'post_upgrade'              => fn() => $this->runPostUpgrade($effective_upgrade),
            'finally_apply_permissions' => fn() => $this->applyPermissions($owner),
        ];

        foreach ($steps as $step_name => $step) {
            if (in_array($step_name, $completed_steps, true)) {
                $this->io->text("  <comment>Skipping (already completed): {$step_name}</comment>");
                continue;
            }
            if (in_array($step_name, $this->skip_steps, true)) {
                $this->io->text("  <comment>Skipping (not required for this version): {$step_name}</comment>");
                $completed_steps[] = $step_name;
                continue;
            }
            if (!$step()) {
                $this->upgrade_service->saveState($tag, $completed_steps, $step_name, $effective_upgrade);
                $this->io->newLine();
                $this->io->note([
                    "Upgrade interrupted at step: {$step_name}",
                    'Details have been written to: ' . UpgradeService::UPGRADE_LOG,
                    "To resume, run: ./MintCLI upgrade --tag={$tag}",
                ]);
                return Command::FAILURE;
            }
            $completed_steps[] = $step_name;
        }

        $this->upgrade_service->clearState();

        $this->io->success([
            "MintHCM has been upgraded to {$tag} successfully!",
            'Log file: ' . UpgradeService::UPGRADE_LOG,
        ]);

        return Command::SUCCESS;
    }

    private function resolveGitCredentials(InputInterface $input): void
    {
        if (!$this->upgrade_service->isHttpsRemote()) {
            return;
        }

        $user = $input->getOption('git-user') ?? '';
        $pass = $input->getOption('git-pass') ?? '';

        $yes = $input->getOption('yes') || !$input->isInteractive();

        if (empty($user) && !$yes) {
            $user = (string) $this->io->ask('Git username (leave empty to skip if credentials are already configured)', null);
        }

        if (!empty($user) && empty($pass) && !$yes) {
            $pass = (string) $this->io->askHidden('Git password or personal access token');
        }

        if (!empty($user) && !empty($pass)) {
            $this->upgrade_service->setGitCredentials($user, $pass);
        }
    }

    private function confirmResume(array $state): bool
    {
        $this->io->caution([
            "An interrupted upgrade to {$state['tag']} was detected.",
            'Failed step  : ' . ($state['failed_step'] ?? 'unknown'),
            'Failed at    : ' . ($state['failed_at'] ?? 'unknown'),
            'Completed    : ' . (implode(', ', $state['completed_steps'] ?? []) ?: '(none)'),
        ]);

        if ($this->input->getOption('yes') || !$this->input->isInteractive()) {
            $this->io->text('Auto-confirming resume (--yes or non-interactive mode).');
            return true;
        }

        return $this->io->confirm('Do you want to resume the upgrade from the failed step?', true);
    }

    private function resolveTag(InputInterface $input, string $current_version): ?string
    {
        $tag = $input->getOption('tag');
        if (!empty($tag)) {
            return $tag;
        }

        $available_tags = $this->upgrade_service->getAvailableTags($current_version);
        if (empty($available_tags)) {
            $this->io->error("No version tags higher than the current version ({$current_version}) were found.");
            return null;
        }

        return $this->io->choice('Select target version to upgrade to', $available_tags, end($available_tags));
    }

    private function checkRequirements(string $tag, string $current_version, bool $skip, string $effective_upgrade): bool
    {
        if ($skip) {
            $this->io->warning('Skipping environment checks (--skip-checks flag is set).');
            return true;
        }

        $this->io->section("Reading upgrade requirements from tag {$tag}...");
        $requirements = $this->requirements_service->loadRequirements($tag, $effective_upgrade);

        if ($requirements === null) {
            $this->io->warning("No requirements.json found for tag {$tag} (upgrade/{$effective_upgrade}/requirements.json). Proceeding without checks.");
            return true;
        }

        $notes = $this->requirements_service->getNotes($requirements);
        if ($notes) {
            $this->io->note($notes);
        }

        $this->io->section('Verifying environment...');
        $passed = $this->requirements_service->verify($requirements, $current_version);

        foreach ($this->requirements_service->getSuccesses() as $msg) {
            $this->io->text("<fg=green>[OK]</> {$msg}");
        }

        $warnings = $this->requirements_service->getWarnings();
        if ($warnings) {
            $this->io->warning($warnings);
        }

        if (!$passed) {
            $this->io->newLine();
            $this->io->error($this->requirements_service->getErrors());
            return false;
        }

        $this->skip_steps = $this->requirements_service->getSkipSteps($requirements);

        $this->io->success('All requirements satisfied.');
        return true;
    }

    private function confirmUpgrade(string $current_version, string $tag): bool
    {
        $this->io->caution([
            "You are about to upgrade MintHCM from {$current_version} to {$tag}.",
            'Ensure you have a FULL BACKUP of your database and files before proceeding.',
        ]);

        if ($this->input->getOption('yes') || !$this->input->isInteractive()) {
            $this->io->text('Auto-confirming upgrade (--yes or non-interactive mode).');
            return true;
        }

        if (!$this->io->confirm('Do you want to continue with the upgrade?', false)) {
            $this->io->text('Upgrade cancelled.');
            return false;
        }

        return true;
    }

    private function runPreUpgrade(string $tag): bool
    {
        $this->io->section('Running pre-upgrade scripts...');
        if (!$this->upgrade_service->runPreUpgradeScripts($tag)) {
            $this->io->error('Pre-upgrade script failed. Upgrade aborted.');
            return false;
        }
        return true;
    }

    private function fetchAndCheckout(string $tag): bool
    {
        $this->io->section('Fetching remote changes...');
        if (!$this->upgrade_service->gitFetch()) {
            $this->io->error('git fetch failed. Upgrade aborted.');
            return false;
        }

        $this->io->newLine();
        $this->io->section("Checking out {$tag}...");
        if (!$this->upgrade_service->gitCheckout($tag)) {
            $this->io->error("git checkout {$tag} failed. Upgrade aborted.");
            return false;
        }

        return true;
    }

    private function applyPermissions(string $owner = 'www-data:www-data'): bool
    {
        $this->io->section("Setting file ownership and permissions (owner: {$owner})...");
        if (!$this->upgrade_service->setupPermissions($owner)) {
            $this->io->error('Failed to set file ownership/permissions.');
            return false;
        }
        return true;
    }

    private function runInstanceRebuild(): bool
    {
        $this->io->section('Running instance rebuild...');
        if (!$this->upgrade_service->runInstanceRebuild()) {
            $this->io->error('Instance rebuild failed. Check upgrade.log for details.');
            return false;
        }
        return true;
    }

    private function runMigrations(string $tag): bool
    {
        $this->io->section('Running database migrations...');
        if (!$this->upgrade_service->runMigrations($tag)) {
            $this->io->error('Database migration failed. Check upgrade.log for details.');
            return false;
        }
        return true;
    }

    private function runPostUpgrade(string $tag): bool
    {
        $this->io->section('Running post-upgrade scripts...');
        if (!$this->upgrade_service->runPostUpgradeScripts($tag)) {
            $this->io->error('Post-upgrade script failed. Check upgrade.log for details.');
            return false;
        }
        return true;
    }

    /**
     * Determine which upgrade/{version}/ scripts to use.
     *
     * Returns the effective upgrade version to pass to pre_upgrade/migrations/post_upgrade.
     * Returns null when there are ≥2 intermediate upgrades — sets $this->intermediate_upgrade_error
     * with the list of intermediate versions so the caller can build a helpful error message.
     */
    private function resolveEffectiveUpgradeVersion(string $tag, string $current_version): ?string
    {
        $versions_on_tag = $this->upgrade_service->getUpgradeVersionsOnTag($tag);

        // Direct upgrade directory exists — normal flow
        if (in_array($tag, $versions_on_tag, true)) {
            return $tag;
        }

        // Find intermediate versions that have upgrade dirs on the target tag
        $intermediates = array_values(array_filter(
            $versions_on_tag,
            fn($v) => version_compare($v, $current_version, '>') && version_compare($v, $tag, '<')
        ));
        usort($intermediates, 'version_compare');

        if (count($intermediates) === 0) {
            // No upgrade scripts at all — existing script runners handle missing dirs gracefully
            return $tag;
        }

        if (count($intermediates) === 1) {
            return $intermediates[0];
        }

        // ≥2 intermediates — cannot skip; caller must abort with step-by-step instructions
        $this->intermediate_upgrade_error = $intermediates;
        return null;
    }
}
