# Bitcoin-OAuth2-providers
This is an OAuth2.0 client library which mainly focuses on services provider who use OAuth2.0 within the Bitcoin ecosystem. The purpose of this library is to simplify the process of obtaining an OAuth2.0 access token and refreshing those tokens.

### Current providers
#### [Xapo](http://docs.xapo.apiary.io)
***
### Setup
1. `require ('\OAuth2\API.php');`

2. `$settings = new settings();`
3. `$invoke = new invoke();`

***

### Obtaining details for a provider
 `$details = $settings -> LoadDetails('Xapo');`

### Obtaining authorization URL
`$auth_url = $invoke -> getAuthorizationUrl($details);`
>  returns code parameter for Xapo if successful

### Obtaining access token
`$accesToken = $invoke -> getAccessToken($details, $_GET['code']);`
 > returns access token, refresh token and expirey time...

### Refreshing access token
 `$newAccesToken = $invoke -> refreshAccessToken($details, $refreshToken);`

