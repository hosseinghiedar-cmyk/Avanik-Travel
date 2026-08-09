# Phase 18 — Passenger Production Security

Implemented:
- Passenger audit log now indexes audit actions and supports bounded recent-booking audit retrieval.
- Production guard is available to fail sensitive writes when the encryption key is absent.
- Controlled legacy migration helper encrypts plaintext passport/national-ID records in bounded batches when a valid key is configured.
- No encryption key or passenger plaintext is stored in the repository.

Important deployment sequence:
1. Configure `AVANIK_DATA_KEY` outside Git.
2. Back up the database.
3. Run and verify migration batches.
4. Verify decrypted values through authorized application paths.
5. Enable production write guard.
6. Review and retain audit records according to company policy.

Next: wire the guard and migration into an admin-only maintenance screen, connect audit calls to every passenger read/write path, and complete agency/customer UI permission enforcement.