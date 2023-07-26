<?php declare(strict_types=1);
namespace Khoelck\PhpAzureAuth {

    class AzureConfig {
        /*
        * Configuration file for client specific Azure AD properties
        */

        // Client ID is found in the App Registrations > Overview section
        public static string $ClientID = "";

        // Client secret is found in the App Registrations > Certificates and secrets section.  The secret must be copied
        // at the time of generation, it will be unavailable afterwards.
        public static string $ClientSecret = "";

        // AuthURL is found in the App Registrations > Overview > Endpoints section as the "OAuth 2.0 token endpoint".  This is needed
        // to access Azure APIs.  It follows the below format:
        // https://login.microsoftonline.com/<TENANT/oauth2/v2.0/token";
        public static string $AuthURL = "";
        
        // Tenant ID is found in the App Registrations > Overview section
        public static string $TenantID = "";
    }
}

?>
