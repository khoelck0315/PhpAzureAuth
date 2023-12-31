<?php declare(strict_types=1);
namespace Khoelck\PhpAzureAuth {        
    require_once "AzureConfig.php";
    use stdClass;
    use Exception;

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
            $this->ClientID = getenv("CLIENT_ID");
            $this->ClientSecret = getenv("CLIENT_SECRET");
            $this->GrantType = "password";
            $this->AuthURL = getenv("AUTH_URL");
            $this->Scope = $scope;
            $this->TenantID = getenv("TENANT_ID");

            try {
                $this->Token = $this->GetAccessToken();
            }
            catch(Exception $e) {
                error_log("Unable to create new instance of AzureAuth.  ".$e->getMessage());
            }
        }

        public static function AddToSession($token): array {
            $expiration = time() + $token->expires_in;
            return array("BaseScope"=> $token->scope,
                         "Token"=>$token,
                         "Expiration"=>$expiration
                        );
        }

        public static function RefreshToken(stdClass $token) : bool {
            $getToken = curl_init();
            
            $tokenRequest =  "client_id="     .  getenv("CLIENT_ID")      . "&";
            $tokenRequest .= "tenant="        .  getenv("TENANT_ID")       . "&";
            $tokenRequest .= "grant_type="    .  "refresh_token"              . "&";
            $tokenRequest .= "refresh_token=" .  $token->refresh_token        . "&";
            $tokenRequest .= "client_secret=" .  getenv("CLIENT_SECRET");

            curl_setopt($getToken, CURLOPT_URL, getenv("AUTH_URL"));
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

            curl_setopt($getToken, CURLOPT_URL, $this->AuthURL);
            curl_setopt($getToken, CURLOPT_POST, 1);
            curl_setopt($getToken, CURLOPT_POSTFIELDS, $tokenRequest);
            curl_setopt($getToken, CURLOPT_RETURNTRANSFER, true);

            // Make the HTTP request for the report, and parse the JSON into an object
            //echo $tokenRequest;
			$result = curl_exec($getToken);
			if(!$result) {
				echo curl_error($getToken);
				error_log(curl_error($getToken));
			}
			else {
				$token = json_decode($result);	
			    if(property_exists($token, "error")) {
					error_log("Error obtaining access token: ".$token->error_description);
					throw new Exception("Error obtaining access token: ".$token->error_description);
				}
				else return $token;
			}
            curl_close($getToken);
         
        }

        private string $Username;
        private string $Password;
        private string $ClientID;
        private string $ClientSecret;
        private string $GrantType;
        private string $AuthURL;
        private string $Scope;
        private string $TenantID;
        public stdClass $Token;
    }

}
?>
