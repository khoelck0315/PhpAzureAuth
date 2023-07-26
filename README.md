# PhpAzureAuth
PhpAzureAuth is a very simple PHP library to generate Azure tokens for consuming services, specifically using ROPC authentication method via PHP.  More on that from Microsoft [here.](https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-oauth-ropc)

It is a dependency package of the [PhpPowerBI](https://github.com/khoelck0315/PhpPowerBI/tree/main) package.

# Installation
The recommended way to install is via composer

### Composer install
```
composer require phpazureauth
```

### Manual install
Copy the contents of the src folder to your include_path, and include the libraries in your authentication script as below:
```
require "include_path/AzureAuth/Scope.php";
require "include_path/AzureAuth/AzureAuth.php";
use Khoelck\PhpAzureAuth\AzureAuth;
use Khoelck\PhpAzureAuth\Scope;
```

# Use
This package is primarily designed to be used inside of your app's authentication script.  Used within this script, the username and password will be able to be passed through to the AzureAuth constructor, then used to obtain the token.  Please see Example.php for more information.

# Related packages
Also check out my package for embedding PowerBI reports via PHP.

[PhpPowerBI](https://github.com/khoelck0315/PhpPowerBI/tree/main)
