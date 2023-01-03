<?php

namespace Basecamp;


/**
 * OAuth client
 */
class OAuthClient
{
    /**
     * Get OAuth token from transient for given API.
     *
     * @param string $url
     * @param string $id
     * @param string $secret
     * @param string $grantType
     * @param array  $config  Optional array with additional options.
     * @return mixed string or false for error
     */
    public static function getToken($url, $id,$secret,$grantType,$config = array()) {
        $configDefaults = array(
            'idFieldName' => 'client_id',
            'secretFieldName' => 'client_secret',
            'grantFieldName' => 'grant_type',
            'forceRefresh' => false, //New token will be obtained from the auth server even if one is already stored.,
            'accessTokenFieldName' => 'access_token',
            'expiresFieldName' => 'expires_in',

        );
        $config = wp_parse_args( $config, $configDefaults );

        $transientName = "Bearer Token:" . $url;

        $transient = get_transient($transientName);
        if($transient === false || $config['forceRefresh']) {
            $requestResult = self::fireRequest($url, $id,$secret,$grantType,$config);
            if($requestResult === false){
                return false;
            }
            if(!isset($requestResult[$config['accessTokenFieldName']]) || !isset($requestResult[$config['expiresFieldName']])){
                return false;
            }
            set_transient($transientName, $requestResult[$config['accessTokenFieldName']], $requestResult[$config['expiresFieldName']]);
            return $requestResult[$config['accessTokenFieldName']];
        } else {
            return $transient;
        }
    }

    /**
     * Attemp authentication from the provider.
     *
     * @param string $url
     * @param string $id
     * @param string $secret
     * @param string $grantType
     * @param array $config  Optional array with additional options.
     * @return mixed string or false if failed
     */
    private static function fireRequest($url, $id,$secret,$grantType,$config) {
        $client = new \GuzzleHttp\Client(['http_errors' => false,]);
        $formData = array(
            $config['idFieldName'] => $id,
            $config['secretFieldName'] => $secret,
            $config['grantFieldName'] => $grantType,
        );
        $response = $client->request('POST', $url, array(
            'form_params' => $formData,
        ));
        if($response->getStatusCode()!== 200){
            return false;
        }
        $result = json_decode($response->getBody(), true);
        if( json_last_error() !== JSON_ERROR_NONE ){
            return false;
        } else {
            return $result;
        }
    }

}

