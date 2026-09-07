# SDK publishing

Libya Dev API keeps each SDK in the monorepo but publishes each package independently.

## Package names and release tags

| SDK | Registry package | Release tag |
|---|---|---|
| JavaScript | `libya-dev-api-client` | `js-vX.Y.Z` |
| Python | `libya-dev-api-client` | `python-vX.Y.Z` |
| Dart / Flutter | `libya_dev_api` | `dart-vX.Y.Z` |

The version inside the package metadata must match the version in its release tag.

## npm

Workflow: `.github/workflows/publish-npm.yml`

npm Trusted Publishing requires the package to already exist. Bootstrap the first version from an npm account with 2FA enabled:

```bash
cd sdks/javascript
npm test
npm pack --dry-run
npm login
npm publish --access public
```

Then configure npm Trusted Publishing for:

```text
GitHub owner: ayagaidi
Repository: libya-dev-api
Workflow: publish-npm.yml
```

Allow direct `npm publish`, then future versions can be released by pushing tags such as:

```text
js-v0.5.0
```

The GitHub workflow uses OIDC and does not require a long-lived npm publish token.

## PyPI

Workflow: `.github/workflows/publish-pypi.yml`

Create a pending or existing Trusted Publisher on PyPI for:

```text
PyPI project: libya-dev-api-client
GitHub owner: ayagaidi
Repository: libya-dev-api
Workflow: publish-pypi.yml
Environment: pypi
```

Create a GitHub deployment environment named `pypi`. A tag such as `python-v0.4.0` builds the wheel/source distribution and publishes with PyPI Trusted Publishing via OIDC.

No long-lived PyPI token is required.

## pub.dev

Workflow: `.github/workflows/publish-pubdev.yml`

pub.dev automated publishing only works for an existing package, so bootstrap the first version once:

```bash
cd sdks/dart
dart pub get
dart analyze
dart test
dart pub publish --dry-run
dart pub publish
```

After the first package exists, enable automated publishing in the package Admin tab:

```text
GitHub repository: ayagaidi/libya-dev-api
Tag pattern: dart-v{{version}}
```

Future releases are triggered by tags such as:

```text
dart-v0.5.0
```

## Release safety

- Never publish from an unreviewed branch.
- Keep publishing workflows isolated and least-privileged.
- Prefer OIDC / Trusted Publishing over long-lived secrets.
- Run SDK Quality before creating a package tag.
- Do not reuse an already-published version number; registry releases are immutable.
