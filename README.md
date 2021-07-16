# Config
Framework config. files manager. Implements PSR-11

## Configuration files formats
This component accepts Yaml and Json files formats.

## Full Example
With JsonLoader

```json
// myConfig.json

{
  "FOO": "BAR"
}
```

```php
// PHP Code

use Climbx\Bag\Bag;
use Climbx\Config\Loader\JsonLoader;
use Climbx\Filesystem\FileHelper;
use Climbx\Config\Parser\EnvVarParser;
use Climbx\Config\Reader\Reader;
use Climbx\Config\ConfigContainer;

// Bag with array of .env data

// JsonLoader
$jsonLoader = new JsonLoader(__DIR__ . 'config/', new FileHelper());

// Config files Reader
$env = new Bag(['FOO' => 'BAR']); // loaded from .env file
$reader = new Reader($jsonLoader, new EnvVarParser($env));

// Container
$container = new ConfigContainer($reader);

/*
 * get() method
 * 
 * If the config file exists it will be returned
 * 
 * If not, a NotFoundException is thrown.
 * 
 * If the config file is not valid, a ConfigurationParserException
 * is thrown
 * 
 * If a referenced .env var is missing in .env file,
 * a EnvParameterNotFoundException is thrown.
 */
$config = $container->get('myConfigId');

/*
 * has() method.
 * 
 * This method returns true if the config exists and is readable,
 * and false otherwise.
 */
$config = $container->has('myConfigId');
```

### Env Vars Parser
It is possible to add a reference to a `.env` var into a configuration
var. It is done with the magic expression `$env(MY_ENV_VAR)`.
If the reference exists in `.env`, it will be replaced by its value.
If not, a EnvParameterNotFoundException will be thrown.

```dotenv
# .env file

FOO=BAR
```

```yaml
# config.yaml

BAZ: $env(FOO)
```

```php
// PHP Code

$container = new ConfigContainer($reader);

$config = $container->get('config');
echo $config->get('BAZ'); // Will print: BAR
```