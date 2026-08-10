# Phase 53 — Secure Notification Provider Credential Vault

Adds an encrypted credential store for configured notification providers.

## Stored fields
- API key
- API secret
- token
- account ID

The UI never displays the stored secret value after saving. To rotate a secret, enter the new value and save.

## Cryptography
Credentials are encrypted with AES-256-GCM using a key derived from WordPress `AUTH_KEY` and `SECURE_AUTH_KEY`. A random 12-byte nonce and authentication tag are stored with each ciphertext. No plaintext credential is committed to the repository.

If OpenSSL is unavailable, credential writes fail instead of silently storing plaintext.

## Admin
Settings → Provider Credentials

Only administrators with `manage_options` can save or delete credentials, and each action is protected by a provider-specific WordPress nonce.

## Adapter boundary
Provider adapters can retrieve credentials through `NotificationCredentialVault::get($provider)`; the vault is deliberately independent from provider APIs.

## Operational note
Back up WordPress authentication salts securely. Changing the salts invalidates access to previously encrypted credentials; credentials must then be re-entered. This phase does not log or expose credential values.
