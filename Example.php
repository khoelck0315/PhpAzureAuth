<?php
	// This minimal example shows how to use AzureAuth to generate a token during your authentication, using Microsoft's 
	// OAuth 2.0 ROPC grant flow.  Ideally, this would be included in your app's authentication script.
	// https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-oauth-ropc
	
	require "../vendor/autoload.php";
	require "include_path/AzureAuth/AzureConfig.php";
	use Khoelck\PhpAzureAuth\AzureAuth;
	use Khoelck\PhpAzureAuth\Scope;
	
	// Create a new instance of AzureAuth, using the string username and string password passed to the application.  Also,
	// specify a valid scope for the token.  Add to the Scope.php class if needed for use here!
	$azureAuth = new AzureAuth($username, $password, Scope::$PowerBI);
	
	// Add the token to your session variable to be retrieved and used to gain access to resources on subsequent pages.
	// Adding an 'AzureAuth' to the Session variable as done below is required to store the data.
	$_SESSION['AzureAuth'] = AzureAuth::AddToSession($azureAuth->Token);
?>
