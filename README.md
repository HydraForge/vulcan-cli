# Vulcan CLI

Add the following to `$HOME/.composer/composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:HydraForge/vulcan-cli.git"
    }
  ],
  "require": {
    "hydraforge/vulcan-cli": "dev-main"
  }
}
```

run the following to install the command:

```bash
composer require hydraforge/vulcan-cli
```

use the command to scaffold new projects

```bash
vulcan new todo-app
```
