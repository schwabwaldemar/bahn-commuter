# Repository Guidelines

## Project Structure & Module Organization
- `src/` holds PSR-4 code (e.g., `App\\Station`, `App\\Schedule`); keep services cohesive and constructor-injected.
- Symfony config belongs in `config/`; split domain-specific wiring into files under `config/packages/` instead of bloating `services.yaml`.
- Doctrine entities and repositories live in `src/Entity/` and `src/Repository/`; migrations are versioned in `migrations/`.
- UI resources: Twig templates in `templates/`, Encore sources in `assets/`, compiled bundles in `public/build/`.
- Tests mirror namespaces inside `tests/` with optional `Unit/`, `Integration/`, and `Functional/` subfolders.

## Build, Test, and Development Commands
- `ddev start` / `ddev stop` control the environment; run all Composer work with `ddev composer …` to avoid host permission issues.
- `ddev composer install` installs PHP deps; add packages with `ddev composer require vendor/package`.
- Database lifecycle: `ddev exec bin/console doctrine:database:create`, `doctrine:migrations:migrate`, `make:migration` for schema diffs.
- `ddev exec bin/phpunit` runs the suite; append `--coverage-html var/coverage` when validating coverage.
- Front-end: `ddev exec npm install` once, `ddev exec npm run dev` for local builds, `ddev exec npm run dev -- --watch` while iterating, and `ddev exec npm run build` for production bundles.

## Coding Style & Naming Conventions
- PHP 8.3, `declare(strict_types=1);`, PSR-12 formatting, PascalCase classes, camelCase methods, UPPER_SNAKE_CASE constants.
- Twig templates use snake_case filenames; component partials sit in `templates/_partials/`.
- JS/TS modules under `assets/` use kebab-case filenames and named exports for Stimulus controllers.

## Testing Guidelines
- PHPUnit is standard; name files `*Test.php` and align namespaces (`tests/Service/FareCalculatorTest.php`).
- Pair new features with unit coverage and a functional test when touching controllers, forms, or Doctrine queries.
- Reset persistent state with transactions/fixtures; keep new module coverage at or above 80%.

## Commit & Pull Request Guidelines
- Follow Conventional Commits (`feat:`, `fix:`, `chore:`) in imperative mood.
- PRs should state the problem, solution, and verification (`ddev exec bin/phpunit`, `npm run build`, screenshots for UI work) and reference issues via `Closes #123`.
- Require one maintainer review and green CI before merging; favor squash merges to keep history linear.

## Front-End Workflow Notes
- Manage entrypoints in `webpack.config.js` via `Encore.addEntry('app', './assets/app.ts')`.
- Assets referenced in Twig via `{{ asset('build/app.css') }}`; static files (fonts/images) stay under `assets/`.
- Run `ddev exec npm run build` before releases to ensure optimized output in `public/build/`.
