<?php

function encrypt($data)
{
  $publicKeyPath = __DIR__ . "../../keys/keypublic/key_public.pem";
  $publicKey     = openssl_pkey_get_public(file_get_contents($publicKeyPath));
  $encrypted     = '';
  openssl_public_encrypt($data, $encrypted, $publicKey);
  return base64_encode($encrypted);
}

function decrypt($encryptedData)
{
  $privateKeyPath =  __DIR__ . "../../keys/keysecret/key_secret.pem";
  $privateKey     = openssl_pkey_get_private(file_get_contents($privateKeyPath));
  $decrypted      = '';
  openssl_private_decrypt(base64_decode($encryptedData), $decrypted, $privateKey);
  return $decrypted;
}
