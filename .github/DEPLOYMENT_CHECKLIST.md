# DOH SRS - Production Deployment Checklist

**Project:** DOH Service Request System (SRS)  
**Environment:** Production  
**Date:** _______________  
**Deployed By:** _______________  

---

## Pre-Deployment (72 Hours Before)

### Security Configuration

- [ ] Generate `TRACK_ACCESS_SECRET`
  ```bash
  php -r "echo base64_encode(random_bytes(32));"
  ```
  - Value: `_______________________________`
  - Stored in secure vault: ☐

- [ ] Verify `APP_KEY` is strong and unique
  - Value: `base64:_______________________________`
  - Different from TRACK_ACCESS_SECRET: ☐

- [ ] Database credentials verified
  - Host: `_______________________________`
  - User: `_______________________________`
  - Database name: `_______________________________`
  - Password stored in vault: ☐

### Code Verification

- [ ] All security fixes committed
  - TRACK_ACCESS_SECRET handling: ☐
  - Session cookie hardening: ☐
  - Image validation enhancement: ☐
  - .gitignore updated: ☐

- [ ] No hardcoded secrets in codebase
  ```bash
  grep -r "APP_KEY\|password\|secret" app/ --exclude-dir=vendor
  # Should return zero results for hardcoded values
  ```
  Result: _______________

- [ ] All tests passing
  ```bash
  php artisan test
  ```
  - Total tests: ______
  - Passed: ______
  - Failed: ______

### Dependencies

- [ ] PHP version verified: _________ (Required: 8.3+)
- [ ] Database version verified: _________ (MySQL 8.0+)
- [ ] Composer dependencies frozen
  ```bash
  composer install --no-dev --optimize-autoloader
  ```

---

## Deployment Day (Pre-Go-Live)

### Environment Setup

- [ ] Server timezone set to `Asia/Manila`
  ```bash
  timedatectl set-timezone Asia/Manila
  ```

- [ ] SSL/HTTPS certificate installed and valid
  - Domain: `_______________________________`
  - Expiry date: `_______________________________`
  - Certificate type: [Self-signed / CA-signed]

- [ ] .env file created with production values
  ```env
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://your-domain.gov.ph
  APP_KEY=base64:your_key_here
  TRACK_ACCESS_SECRET=your_secret_here
  SESSION_SECURE_COOKIE=true
  ```
  - Location: `/home/app/.env` or `/var/www/app/.env`
  - Permissions: `0600` (read-write for owner only)
  - Tested: ☐

- [ ] Storage directory permissions
  ```bash
  sudo chown -R www-data:www-data storage/
  sudo chmod -R 755 storage/
  ```

- [ ] Log rotation configured
  ```
  Logrotate config: /etc/logrotate.d/laravel
  ```

### Database

- [ ] Database created and empty
  ```bash
  mysql -u root -p
  > CREATE DATABASE srs_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

- [ ] Migration files reviewed
  - Number of migrations: ______
  - Latest migration: `_______________________________`

- [ ] Migrations executed
  ```bash
  php artisan migrate --force
  ```
  - Migrations run: ______
  - Result: [Success / Failed]
  - Error (if any): _______________

- [ ] Database users created (non-root)
  ```sql
  CREATE USER 'srs_app'@'localhost' IDENTIFIED BY 'strong_password';
  GRANT ALL PRIVILEGES ON srs_production.* TO 'srs_app'@'localhost';
  ```

### Application Startup

- [ ] Clear config cache
  ```bash
  php artisan config:clear
  php artisan config:cache
  ```

- [ ] Clear route cache
  ```bash
  php artisan route:clear
  php artisan route:cache
  ```

- [ ] Optimize composer autoloader
  ```bash
  composer install --optimize-autoloader --no-dev
  ```

- [ ] Web server started
  - Server type: [Nginx / Apache]
  - Status: [Running / Not Running]
  - Process: `_______________________________`

- [ ] Application accessible
  ```bash
  curl -I https://your-domain.gov.ph
  # Should return 200 OK
  ```
  - Response code: _______
  - HTTPS enforced: [Yes / No]

### Security Verification

- [ ] HTTPS is enforced (redirect from HTTP)
  ```bash
  curl -I http://your-domain.gov.ph
  # Should redirect to https://
  ```

- [ ] Security headers present
  ```bash
  curl -I https://your-domain.gov.ph | grep -i "strict\|x-frame\|x-content"
  ```
  - Strict-Transport-Security: [Present / Missing]
  - X-Frame-Options: [Present / Missing]
  - X-Content-Type-Options: [Present / Missing]

- [ ] Session cookies have security flags
  - [ ] Secure flag enabled (HTTPS only)
  - [ ] HttpOnly flag enabled (no JS access)
  - [ ] SameSite=lax flag enabled

- [ ] TRACK_ACCESS_SECRET is loaded
  ```bash
  php artisan tinker
  >>> config('app.track_access_secret')
  # Should return non-null value
  ```
  - Value present: [Yes / No]
  - Matches .env: [Yes / No]

---

## Smoke Testing (Post-Go-Live)

### User Workflows

- [ ] User can register/login
  - Test user: `_______________________________`
  - Password: `_______________________________`

- [ ] User can create service request
  - Request ID: `_______________________________`
  - Status: [Pending / Failed]

- [ ] User can upload photo
  - Photo: [Accepted / Rejected]
  - Result: _______________

- [ ] User can draw signature
  - Signature: [Accepted / Rejected]
  - Result: _______________

- [ ] User can track request by reference code
  - Reference code: `_______________________________`
  - Status: [Found / Not Found]

### Admin Workflows

- [ ] Admin can approve request
  - Request ID: `_______________________________`
  - Admin user: `_______________________________`
  - Result: [Success / Failed]

- [ ] Admin can upload signature
  - Signature file: `_______________________________`
  - Result: [Accepted / Rejected]

- [ ] Admin can add chat messages
  - Message: `_______________________________`
  - Result: [Success / Failed]

### Error Handling

- [ ] 404 errors handled gracefully
- [ ] 500 errors logged (not shown to user)
- [ ] Validation errors clear and helpful
- [ ] No debug information visible

### Performance

- [ ] Page load time acceptable (< 2 seconds)
  - Homepage: ______ seconds
  - Login: ______ seconds
  - Dashboard: ______ seconds

- [ ] Database queries optimized
  - N+1 queries found: [Yes / No]
  - Slow query log checked: [Yes / No]

---

## Monitoring Setup

### Log Monitoring

- [ ] Application logs configured
  ```bash
  tail -f storage/logs/laravel.log
  ```
  - Errors: [None / Some]
  - Warnings: [None / Some]

- [ ] Database logs monitored
  ```bash
  tail -f /var/log/mysql/error.log
  ```
  - Errors: [None / Some]

- [ ] Web server logs monitored
  ```bash
  # Nginx
  tail -f /var/log/nginx/error.log
  # Apache
  tail -f /var/log/apache2/error.log
  ```

### Alert Configuration

- [ ] High error rate alert configured (threshold: ___%)
- [ ] Database connection failure alert
- [ ] Disk space alert (threshold: ___GB)
- [ ] CPU usage alert (threshold: __%)
- [ ] Memory usage alert (threshold: __%)

### Backup Configuration

- [ ] Database backup scheduled
  - Frequency: [Daily / Weekly]
  - Time: _______________
  - Location: `_______________________________`
  - Retention: ______ days

- [ ] File backup scheduled
  - Includes: [Yes / No] storage/
  - Includes: [Yes / No] config/
  - Frequency: [Daily / Weekly]

---

## Post-Deployment (24 Hours)

### Health Checks

- [ ] All critical paths tested again
- [ ] No error rate spikes
- [ ] Database connections stable
- [ ] Memory/CPU usage normal

### Security Review

- [ ] TRACK_ACCESS_SECRET working correctly
  ```bash
  # Test tracking token generation
  php artisan tinker
  >>> hash_hmac('sha256', 'test', config('app.track_access_secret'))
  ```

- [ ] Session security headers present
- [ ] HTTPS enforced and working
- [ ] No sensitive data in logs
- [ ] Failed login attempts logged

### User Feedback

- [ ] No critical issues reported
- [ ] Performance acceptable to users
- [ ] Image uploads working smoothly
- [ ] Signatures accepting correctly

### Documentation

- [ ] Deployment log created
  ```
  Location: /var/log/deployments/2026-06-03-srs-deploy.log
  ```

- [ ] Runbook updated with new procedures
- [ ] Team trained on new security measures
- [ ] Support team briefed on changes

---

## Rollback Plan (If Needed)

**Trigger:** Critical errors affecting more than 10% of users or complete data loss risk

**Steps:**
1. [ ] Notify stakeholders
2. [ ] Stop accepting new requests
3. [ ] Revert to previous deployment
   ```bash
   git revert <commit_hash>
   php artisan migrate:rollback
   ```
4. [ ] Restart application
5. [ ] Verify rollback success
6. [ ] Conduct post-mortem

**Rollback Decision:** [Proceed / Hold]  
**Time to Rollback:** _____ minutes  
**Authorized By:** _______________________________

---

## Sign-Off

| Role | Name | Date | Time | Status |
|------|------|------|------|--------|
| Lead Dev | _____________ | ______ | ______ | [✓/✗] |
| QA Lead | _____________ | ______ | ______ | [✓/✗] |
| DevOps | _____________ | ______ | ______ | [✓/✗] |
| Project Manager | _____________ | ______ | ______ | [✓/✗] |

**Deployment Status:** [APPROVED / PENDING / FAILED]

**Notes/Issues:**
```
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________
```

---

## Emergency Contacts

- **On-Call DevOps:** _________________ | +63-9__________
- **DBA:** _________________ | +63-9__________
- **Dev Lead:** _________________ | +63-9__________
- **Security Team:** _________________ | +63-9__________

---

**Document saved at:** `.github/deployment-checklist-2026-06-03.md`

**Next review date:** 2026-06-10
