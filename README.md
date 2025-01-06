# Vulcan CLI

Add the following to `$HOME/.composer/composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "git@github.com:HydraForge/vulcan-cli.git"
    }
  ]
}
```

run the following to install the command:

```bash
composer global require hydra-forge/vulcan-cli
```

use the command to scaffold new projects

```bash
vulcan new todo-app
```
