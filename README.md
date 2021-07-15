# Config
Framework config. files manager

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
use Climbx\Config\ConfigContainer;

// Get config dir
$configDir = __DIR__ . 'config/';

// Bag with array of .env data
$env = new Bag(['FOO' => 'BAR']);

// Container dependencies 
$fileHelper = new FileHelper();
$envVarParser = new EnvVarParser($env);

// Instantiate the Json loader
$jsonLoader = new JsonLoader($configDir, $fileHelper, $envVarParser);

// Container
$container = new ConfigContainer($jsonLoader);

/*
 * get() method
 * 
 * If the config file exists it will be returned
 * If not, the method returns false.
 */
$config = $container->get('lib/myConfig');

/*
 * require() method.
 * 
 * If the config file exists it will be returned
 * If not, a MissingConfigurationException will be thrown. 
 */
$config = $container->require('myConfigFile');
```

### Note
The configuration files names extensions has to be dismissed.
They are added automatically by the loader. They depend
on the loader that has been passed to the config container.

### Env Vars Parser
It is possible to add a reference to a .env var into a configuration
item. It is done with the magic expression `$env(MY_ENV_PARAMETER)`.
If the reference exists in the env Bag that has been passed, it will
be automatically replaced by its .env value. If it doesn't exist,
a MissingEnvParameterException will be thrown.

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

$container = new ConfigContainer($yamlLoader);

$config = $container->get('config');
echo $config->get('BAZ'); // Will print: BAR
```