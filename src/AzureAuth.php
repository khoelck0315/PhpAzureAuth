<?php declare(strict_types=1);
namespace Khoelck\PhpAzureAuth {        
    use stdClass;

    class AzureAuth {

        /**
         *  This constructor is intended to be called by your authentication script at authentication time.  The POST parameters should be
         *  checked, then passed in to generate the token and stored in the PHP $_SESSION variable. 
         *  @param string username - Active Directory username to be passed for Auth
         *  @param string password - Active directory password to be passed for Auth
         *  @param Scope scope - A static property of the Scope class to be passed and evaluated to the URL scope for the application that will be accessed.  Note that if tokens for different scopes
         *  are needed, separate AzureAuth requests must be made for each scope as the tokens will be unique to that scope.
         */
        public function __construct(string $username, string $password, string $scope) {
            $this->Username = $username;
            $this->Password = $password;
            $this->ClientID = AzureConfig::$ClientID;
            $this->ClientSecret = AzureConfig::$ClientSecret;
            $this->GrantType = "password";
            $this->AuthURL = AzureConfig::$AuthURL;
            $this->Scope = $scope;
            $this->TenantID = AzureConfig::$TenantID;

            try {
                $this->Token = $this->GetAccessToken();
            }
            catch(Exception $e) {
                error_log("Unable to create new instance of AzureAuth.  ".$e->getTrace());
            }
        }

        public static function AddToSession(stdClass $token): array {
            $expiration = time() + $token->expires_in;
            return array("BaseScope"=> $token->scope,
                         "Token"=>$token,
                         "Expiration"=>$expiration
                        );
        }

        public static function RefreshToken(stdClass $token) : bool {
            $getToken = curl_init();
            
            $tokenRequest =  "client_id="     .  AzureConfig::$ClientID       . "&";
            $tokenRequest .= "tenant="        .  AzureConfig::$TenantID       . "&";
            $tokenRequest .= "grant_type="    .  "refresh_token"              . "&";
            $tokenRequest .= "refresh_token=" .  $token->refresh_token        . "&";
            $tokenRequest .= "client_secret=" .  AzureConfig::$ClientSecret;

            curl_setopt($getToken, CURLOPT_URL, AzureConfig::$AuthURL);
            curl_setopt($getToken, CURLOPT_POST, 1);
            curl_setopt($getToken, CURLOPT_POSTFIELDS, $tokenRequest);
            curl_setopt($getToken, CURLOPT_RETURNTRANSFER, true);

            // Make the HTTP request for the report, and parse the JSON into an object
            $token = json_decode(curl_exec($getToken));
            curl_close($getToken);        
            if(property_exists($token, "error")) {
                error_log("Error obtaining token: ".$token->error_description);
                return false;
            }
            else {
                // Update the report token
                $_SESSION['AzureAuth'] = self::AddToSession($token);
                return true;
            }
        }

        private function GetAccessToken(): stdClass  {
            $getToken = curl_init();
            
            $tokenRequest =  "client_id="     .  $this->ClientID       . "&";
            $tokenRequest .= "grant_type="    .  $this->GrantType      . "&";
            $tokenRequest .= "scope="         .  $this->Scope          . "&";
            $tokenRequest .= "username="      .  $this->Username       . "&";
            $tokenRequest .= "password="      .  $this->Password       . "&";
            $tokenRequest .= "tenant="        .  $this->TenantID       . "&";
            $tokenRequest .= "client_secret=" .  $this->ClientSecret;

            curl_setopt($getToken, CURLOPT_URL, AzureConfig::$AuthURL);
            curl_setopt($getToken, CURLOPT_POST, 1);
            curl_setopt($getToken, CURLOPT_POSTFIELDS, $tokenRequest);
            curl_setopt($getToken, CURLOPT_RETURNTRANSFER, true);

            // Make the HTTP request for the report, and parse the JSON into an object
            echo $tokenRequest;
            $token = json_decode(curl_exec($getToken));
            curl_close($getToken);
            if(property_exists($token, "error")) {
                error_log("Error obtaining access token: ".$token->error_description);
                throw new Exception();
            }
            else return $token;
        }

        private string $Username;
        private string $Password;
        private string $ClientID;
        private string $ClientSecret;
        private string $GrantType;
        private string $AuthURL;
        private string $Scope;
    }

}
?>
