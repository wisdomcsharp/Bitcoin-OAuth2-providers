<?php
/****************************
 *      The MIT License (MIT)
 *******
 *    Copyright (c) 19/10/2015 Wisdom Oparaocha, 21faucet.com, @bitNyeFe
 ****
 *    Permission is hereby granted, free of charge, to any person obtaining a copy
 *    of this software and associated documentation files (the "Software"), to deal
 *    in the Software without restriction, including without limitation the rights
 *    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *    copies of the Software, and to permit persons to whom the Software is
 *    furnished to do so, subject to the following conditions:
 *
 *    The above copyright notice and this permission notice shall be included in
 *    all copies or substantial portions of the Software.
 *
 *    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *    THE SOFTWARE.
 ****************************
 */






class settings
{
    
    function LoadDetails($provider) //load details for OAuth2 service providers....
    {
        $provider = strtolower($provider);
        
        switch ($provider) {
            case 'xapo':
                
                $details = array(
                    'name' => $provider, //xapo
                    'entry_point' => '', //i.e. http://xxxx.com/
                    'app_id' => '',
                    'app_secret' => '',
                    'redirect_uri' => '', //i.e. http://xxxx.com/xxx.php must match the default redirect url set on your API page.
                );
                return $details;
                break;
            
            default:
                return false;
                break;
                
        }
        
    }
}

class invoke
{
    
    
    //step 1: produces a link which the user should click to redirect them to the API provide. on success, the provider should return a code (for Xapo)... 
    function getAuthorizationUrl($details) //returns an authorization url which will then be displayed to the user....
    {
        switch ($details['name']) {
            case 'xapo':
                $param = $details['entry_point'] . 'oauth2/authorization?' . 'redirect_uri=' . $details['redirect_uri'] . '&client_id=' . $details['app_id'] . '&response_type=code' . '&scope=all pay';
                return $param;
                break;
        }
    }
    
    //step 2: Converts the 'code' which was previously obtained to an access token, and a refresh token.
    function getAccessToken($details, $code)
    {
        
        switch ($details['name']) {
            case 'xapo': //xapo faucets...
                
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, $details['entry_point'] . "oauth2/token?grant_type=authorization_code&code=" . $code . "&redirect_uri=" . $details['redirect_uri']);
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //allow return data...
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //certificate verification...  !!SHOULD BE ENABLED!!
                curl_setopt($ch, CURLOPT_POST, TRUE);
                
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&redirect_uri=' . $details['redirect_uri'] . '&code=' . $code);
                
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "Authorization: Basic " . base64_encode($details['app_id'] . ':' . $details['app_secret'])
                ));
                
                $response = json_decode(curl_exec($ch));
                curl_close($ch);
                
                return $response;
                
                break;
                
        }
        
    }
    
    
    //refresh access token, after it expires... This function is used to refresh an access token, if it has expired. It returns a new access token.
    function refreshAccessToken($details, $refreshToken)
    {
        
        switch ($details['name']) {
            
            case 'xapo':
                
                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL, $details['entry_point'] . "oauth2/token?grant_type=refresh_token&refresh_token=" . $refreshToken);
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //allow return data...
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //certificate verification...  !!SHOULD BE ENABLED!!
                curl_setopt($ch, CURLOPT_POST, TRUE);
                
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=refresh_token&refresh_token=' . $refreshToken);
                
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Content-Type: application/x-www-form-urlencoded",
                    "Authorization: Basic " . base64_encode($details['app_id'] . ':' . $details['app_secret'])
                ));
                
                $response = json_decode(curl_exec($ch));
                curl_close($ch);
                
                return $response;
                
                break;
                
        }
        
    }
    
    
    
}