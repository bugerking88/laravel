<?php


namespace App\Tools;


class Rsa
{
    const PRIVATE_KEY = '-----BEGIN PRIVATE KEY-----ZXlKc0lqcDdJbU4xYzNSdmJXVnlUbUZ0WlNJNkltOXpWa0psUVN0RGNGTm5NRWxwYUZSWmFsbzFUSEJMYkd3d0swcHdVMkYwWEM4cmVYTnJNMGR6Y2xWcGJXZEhaMDVIYWt0SmJHOWxXbkZjTDJScFFYZHhiMXd2YlVnelRUTnZRMGhUU210b1NVVjNkRE01VjIxdmJqTm1lV1JVYjJSQ1QzVkVTMkppYkdORFVFOHpVV2czVW5OUE1ISlpLMnR6ZERGbU1sRlNYQzkyZUc1Y0wyVndhMHQxUjFGNVpHaG9PVkJpV2xCMFJEZGlZaXRLWlhOV2JrOWxZMDVFYkc5clVYQm1aWEpQT0VKdFZsUlllVnd2SzBOT2JWVk9hVTF2WkZwd1UwVkJhMUVyYjFGS1RHVXJaMjFtTVRScmNWUndTM2RrYkZwMEsyaHRlWFpJVGpKY0wzRmphRzFaUmtoSFpXNVNaRWhaWlVWUWVrOTBZMlJZYUZnNGJXeHBNelV4ZFVaamEzZDBNMjh3Y0VVNFJEUlhkVkZCUW1kdVlXRlFlRGhzWWxoYWFHaHRhbVJRY0daNllWWmxkMHQyZUVRME0wbFBWVUZJZVdSR1VEQkpZM1ZaVUZwVlNtTlhZa3BsZUdnNVZuaFFNazUxUjNjOVBTSXNJbU4xYzNSdmJXVnlTV1FpT2lKR2JucHllREZzUkZsUGRHODRkRnd2U21WNVdXaGFTVmRhTURaYU1GQkdRMDlqWm0xc01XNTBVa2xUTjNKd2RtdFlWMWN3WVRsblFtWjNVRVJpU2toc1FrdENkbVJ4UkROeFowOXRSMmhPUjFkVWEwVkRZbEZ6Um1WRVNYYzJTbHBZZWxKTFQzVkJORGhxWmxOb1FVVjVSWGd4ZVVKbFIwWmFhM0o0V1dsTVVuVTJWREV3U0RReVZuRXdVRWs0VG5sWmRVVmFlaXMwVTBOcVNXTnFhaXRMVERCeWNuQjBSMEk0VGpkSU9XSmlUREpPYlhGTVVXRjZNMWxvTmxVM1NuRjVjRGQ0TXpsSlkySjNaWGhUUjJJcmNYSlNibEZoVkRWeWMxd3ZNbGRaUmpOYVZtSkhhSEZDTm1WMlVVMUlZVGRHYTA5TmRHWlViRmRSTjFscFNFOHdZMmM0TUZsNldHUnRhV2RsTVVWS1VFZGxLMjVxYldwc1RVSjRVVEp0YTJ4a2QwZFNVVFJUWlVaUlJtNW1iM2xKUVVNeFUxSklZMXBCT0VsMk5IaENRMFkwWkZkYVoyZE9Na0YyZVdZMlNHRk9TV1ZIT1N0NWR6MDlJaXdpWVdkbGJuUWlPaUpHYm5weWVERnNSRmxQZEc4NGRGd3ZTbVY1V1doYVNWZGFNRFphTUZCR1EwOWpabTFzTVc1MFVrbFROM0p3ZG10WVYxY3dZVGxuUW1aM1VFUmlTa2hzUWt0Q2RtUnhSRE54WjA5dFIyaE9SMWRVYTBWRFlsRnpSbVZFU1hjMlNscFllbEpMVDNWQk5EaHFabE5vUVVWNVJYZ3hlVUpsUjBaYWEzSjRXV2xNVW5VMlZERXdTRFF5Vm5Fd1VFazRUbmxaZFVWYWVpczBVME5xU1dOcWFpdExUREJ5Y25CMFIwSTRUamRJT1dKaVRESk9iWEZNVVdGNk0xbG9ObFUzU25GNWNEZDRNemxKWTJKM1pYaFRSMklyY1hKU2JsRmhWRFZ5YzF3dk1sZFpSak5hVm1KSGFIRkNObVYyVVUxSVlUZEdhMDlOZEdaVWJGZFJOMWxwU0U4d1kyYzRNRmw2V0dSdGFXZGxNVVZLVUVkbEsyNXFiV3BzVFVKNFVUSnRhMnhrZDBkU1VUUlRaVVpSUm01bWIzbEpRVU14VTFKSVkxcEJPRWwyTkhoQ1EwWTBaRmRhWjJkT01rRjJlV1kyU0dGT1NXVkhPU3Q1ZHowOUlpd2laWGh3YVhKbElqb2lVU3R0ZDJWMlRIbzFhRll6T1U1NlFUVlNSbWRaVlV4M2NscFpORXRsZDNwMk1HSmhObVowVlZReU1tdzVNeXRqUVcweFVUVTJUMFUwYlZaTmRGbEhkbE00VXpCSWNVdEdiVkUzVVZSWVkyNUJaRnd2YUUxbFIwUlhXR1p0UkhCclNIUm1jMk5jTDFsc2VrMTBRVlJ1U0UxYU0yUm9aMGc1UzBWT2IzUjZkMWQyTld4WmVuRklkeXN5Y0ZGb1ZtaE9RakJGV1dseldreHNhWFpJY21OWGRqVkRlbTFCTmpVNWMzYzVNMFZNUnpOWk9XRm9VVWxLYmtaelkwY3plblpWTTA0eVNHcHhVVlZxTlRWSWMzZzVkSE5KVFdzNVNXOWhNRnBZZUd0MGVGbERjalJaTlRGbk9ETXdVU3RrTmpKMGFrODBkbFpGV1hkalpXRmpZVlJZZVRkVFFVcDZRbHBKZWpWbmFuaG1aM0ZhWVhSRmFIZHJTSFZrVERkTmIyMVRNVkpqZVVwT2VpczBXSE5tVW5GUWNUSndaVE5PYkZWMGQxbHJVRnBQVVdwcVpuWkdRMkpKT1cxNlZIWTFNVmQ0YVVaTWJGZFpabmM5UFNKOUxDSnpJanA3SW1JaU9pSWlMQ0pqSWpvaUlpd2laQ0k2SWlKOWZRPT0=-----END PRIVATE KEY-----';
    const PUBLIC_KEY = '-----BEGIN PUBLIC KEY-----
PUBLIC_KEY
-----END PUBLIC KEY-----';

    public function index() {
        $pwd = 'abcdefg';
        $password = $this->enRSA_private($pwd);
        echo 'mm =  '.$password;
        echo '<br>';
        $password = $this->deRSA_public($password);
        echo 'mm =  '.$password;
    }

    /*-----------------------------  公钥加密, 私钥解密 --------------------------------------*/
    /*
     * RSA公钥加密
     * 使用私钥解密
     */
    public static function enRSA_public($aString) {
        $pu_key = openssl_pkey_get_public(self::PUBLIC_KEY);//这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt($aString, $encrypted, $pu_key);//公钥加密，私钥解密
        $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        return $encrypted;
    }
    /*
     * RSA私钥解密
     * 有可能传过来的aString是经过base64加密的，则传来前需先base64_decode()解密
     * 返回未经base64加密的字符串
     */
    public static function deRSA_private($aString) {
        $private_key_res = openssl_get_privatekey(self::PRIVATE_KEY);
        var_dump($private_key_res);
        openssl_private_decrypt(base64_decode($aString), $decrypted, $private_key_res, OPENSSL_PKCS1_OAEP_PADDING);

//        $pr_key = openssl_pkey_get_private(self::PRIVATE_KEY);//这个函数可用来判断私钥是否是可用的
//        var_dump($pr_key);
//        openssl_private_decrypt($aString, $decrypted, $pr_key);//公钥加密，私钥解密
        return $decrypted;
    }

    /*-----------------------------  私钥加密, 公钥解密 --------------------------------------*/
    /*
     * RSA私钥加密
     * 加密一个字符串，返回RSA加密后的内容
     * aString 需要加密的字符串
     * return encrypted rsa加密后的字符串
     */
    public static function enRSA_private($aString) {
        //echo "------------",$aString,"====";
        $pr_key = openssl_pkey_get_private(self::PRIVATE_KEY);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_encrypt($aString, $encrypted, $pr_key);//私钥加密
        $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
        //echo "加密后:",$encrypted,"\n";
        return $encrypted;
    }
    /*
     * RSA公钥解密
     */
    public static function deRSA_public($aString) {
        $pu_key = openssl_pkey_get_public(self::PUBLIC_KEY);//这个函数可用来判断公钥是否是可用的
        openssl_public_decrypt(base64_decode($aString), $decrypted, $pu_key);//公钥加密，私钥解密
        return $decrypted;
    }
}
