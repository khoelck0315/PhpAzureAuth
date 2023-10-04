<?php

// Client ID is found in the App Registrations > Overview section
$ClientID = "";

// Client secret is found in the App Registrations > Certificates and secrets section.  The secret must be copied
// at the time of generation, it will be unavailable afterwards.
$ClientSecret = "";

// AuthURL is found in the App Registrations > Overview > Endpoints section as the "OAuth 2.0 token endpoint".  This is needed
// to access Azure APIs.  It follows the below format:
// https://login.microsoftonline.com/<TENANT/oauth2/v2.0/token";
$AuthURL = "";

// Tenant ID is found in the App Registrations > Overview section
$TenantID = "";

// Add them to environment variables for usage
putenv("CLIENT_ID=$ClientID");
putenv("CLIENT_SECRET=$ClientSecret");
putenv("AUTH_URL=$AuthURL");
putenv("TENANT_ID=$TenantID");

?>
