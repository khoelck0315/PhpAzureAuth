<?php declare(strict_types=1);
namespace Khoelck\PhpAzureAuth {

    class Scope {
        /*
        * Define valid scopes to be passed to the Microsoft Auth API.
        * These are scopes that are configured and permissions from the App Registrations > App > API Permissions section.
        * Feel free to add any additional needed scopes here, then pass them to the AzureAuth Constructor arguments as Scope::Property
        * NOTE: It's imperitive that the scope defined includes the offline_access flag in order to generate a refresh token.
        **/

        public static string $PowerBI = 'https://analysis.windows.net/powerbi/api/.default offline_access';
        public static string $Graph = 'https://graph.microsoft.com/.default offline_access';
    }

}
?>