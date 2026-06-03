# DOH SRS - Security Implementation Guide

## Overview

This document outlines the security measures implemented in the Service Request System (SRS) application, with focus on data integrity, session security, and file upload hardening.

**Date:** June 3, 2026  
**Version:** 1.0  
**Status:** Production-Ready

---

## Table of Contents

1. [TRACK_ACCESS_SECRET - Token Verification](#track_access_secret)
2. [Session Security - Cookie Hardening](#session-security)
3. [Image Upload & Data URI Validation](#image-validation)
4. [Production Deployment Checklist](#deployment-checklist)
5. [Security Best Practices](#best-practices)
6. [Troubleshooting](#troubleshooting)

---

## TRACK_ACCESS_SECRET

### What It Does

The `TRACK_ACCESS_SECRET` is a cryptographic key used to sign and verify service request tracking tokens (HMAC-based signatures). This prevents attackers from forging tracking links if they somehow obtain the Laravel encryption key (`APP_KEY`).

### Why It's Important

**Separation of Concerns:** Two keys for two different purposes
- `APP_KEY`: Encrypts sensitive data in Laravel (passwords, tokens, etc.)
- `TRACK_ACCESS_SECRET`: Signs tracking URLs (public-facing verification tokens)

**Risk Scenario:**
```
If APP_KEY is leaked (through old backup, compromised server, etc.):
- Attacker can decrypt all encrypted data ❌
- Attacker CANNOT forge tracking tokens (because they need separate TRACK_ACCESS_SECRET) ✅
```

### Configuration

**File:** `.env`

```env
# Generate two independent secrets with different values
TRACK_ACCESS_SECRET=your_32_char_secret_here_change_me_now
APP_KEY=base64:your_different_32_char_secret_here
```

**How to Generate:**

```bash
# Generate a random 32-character secret
php -r "echo base64_encode(random_bytes(32));"

# Or use Laravel Tinker
php artisan tinker
>>> base64_encode(random_bytes(32))
```

**Do NOT:**
- ❌ Use the same value as `APP_KEY`
- ❌ Use a short/simple string
- ❌ Commit to version control
- ❌ Share or log the value

### Validation

The system automatically validates on boot:

```php
// app/Providers/AppServiceProvider.php - boot()
if (empty(env('TRACK_ACCESS_SECRET'))) {
    throw new RuntimeException(
        'TRACK_ACCESS_SECRET is not configured. This is required for secure service request tracking.'
    );
}
```

**Error Message If Missing:**
```
RuntimeException: TRACK_ACCESS_SECRET is not configured. This is required for secure service 
request tracking. Set TRACK_ACCESS_SECRET in your .env file to a random, 32+ character string. 
Do not use APP_KEY as a fallback.
```

**Solution:** Add to `.env`:
```env
TRACK_ACCESS_SECRET=your_random_secret_here
```

---

## Session Security

### Cookie Hardening

Session cookies are protected with three security measures:

#### 1. SESSION_SECURE_COOKIE (HTTPS Only)

**What It Does:** Cookies only sent over HTTPS, never HTTP

```env
# .env Local Development
SESSION_SECURE_COOKIE=false

# .env Production (REQUIRED)
SESSION_SECURE_COOKIE=true
```

**Why:** Prevents eavesdropping on unencrypted connections

**Without This:** Man-in-the-middle attacker on public WiFi could steal session cookies → impersonate user

#### 2. SESSION_HTTP_ONLY (Script Access Prevention)

**What It Does:** Cookies not accessible to JavaScript (XSS protection)

```env
SESSION_HTTP_ONLY=true  # Default: enabled
```

**Why:** Prevents malicious JavaScript from stealing session cookies

**Without This:** If site is vulnerable to XSS, attacker's JS could send `document.cookie` to attacker server

#### 3. SESSION_SAME_SITE (CSRF Protection)

**What It Does:** Cookies only sent for same-site requests

```env
SESSION_SAME_SITE=lax  # Default: lax (recommended)
# Other values: strict, none
```

**Why:** Prevents Cross-Site Request Forgery attacks

**Without This:** Attacker could craft malicious link that makes authenticated request

### Production Checklist

```env
# Minimal production .env for session security
SESSION_DRIVER=database
SESSION_ENCRYPT=false
SESSION_LIFETIME=120
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true      # REQUIRED for production
SESSION_HTTP_ONLY=true           # Should always be true
SESSION_SAME_SITE=lax            # Recommended value
```

### Configuration Flow

```
User Login
    ↓
[Session Created]
    ↓
Session Cookie Set with:
  - Secure flag (HTTPS only)
  - HttpOnly flag (no JS access)
  - SameSite=lax (CSRF protection)
    ↓
Subsequent Requests
    ↓
[Browser sends cookie automatically]
    ↓
Server validates & authenticates
```

---

## Image Validation

### Overview

Images uploaded to the system (signatures, photos, attachments) go through multiple validation layers:

1. **MIME Type Check** - Whitelist only allowed formats
2. **File Extension Check** - Must match declared type
3. **File Size Check** - Not exceeding limits
4. **Magic Bytes Validation** - Binary content matches type
5. **Image Content Check** - Actual image data verification

### Supported Image Formats

```
✅ image/jpeg    - .jpg, .jpeg
✅ image/png     - .png
✅ image/webp    - .webp
✅ image/bmp     - .bmp

❌ image/gif     - Not supported
❌ image/tiff    - Not supported
❌ image/svg     - Not supported
❌ application/* - Rejected
```

### Magic Bytes Validation

Each image format has a unique binary signature (magic bytes) at the start of the file:

```
Format   | Hex            | ASCII
---------|----------------|----------
PNG      | 89 50 4E 47    | .PNG
JPEG     | FF D8 FF       | ÿØÿ
WebP     | 52 49 46 46    | RIFF (+ WEBP at byte 8)
BMP      | 42 4D          | BM
```

**Example Attack Prevented:**

```
Attacker renames:
  malicious.exe → malicious.jpg

Our validation:
1. Check MIME type from Laravel upload → REJECTED (MIME: application/x-msdownload)
2. Check extension → OK (image.jpg)
3. Check magic bytes → REJECTED (starts with MZ, not FF D8 FF)

Result: ✅ File rejected despite .jpg extension
```

### Data URI Validation

Drawn signatures are submitted as base64-encoded data URIs:

```
data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==
```

**Validation Steps:**

1. ✅ Verify format: `data:image/TYPE;base64,BASE64STRING`
2. ✅ Validate MIME type in allowlist
3. ✅ Decode base64
4. ✅ Check decoded size ≤ 5MB
5. ✅ Validate magic bytes match MIME type

**Code Location:** `app/Http/Controllers/ServiceRequestController.php`

```php
private function decodeImageDataUri(?string $value): ?array
{
    // Format check
    // MIME type allowlist
    // Size validation (max 5MB decoded)
    // Magic bytes verification
}
```

### Size Limits

```
Drawn Signatures:
  - Max base64 string: 8MB (submitted field)
  - Max decoded size: 5MB (actual binary)
  - Max stored: 5MB per signature

Uploaded Attachments:
  - Max file size: 5MB
  - Max decoded size (if base64): 5MB

Description Photos:
  - Max file size: 5MB
  - Max decoded size: 5MB
```

### Security Benefits

```
Before: Minimal validation
  ❌ Could upload .exe as .png
  ❌ Could upload corrupted/invalid images
  ❌ Could upload 100MB file as "5KB PNG"
  ❌ Could exploit image parsing vulnerabilities

After: Comprehensive validation
  ✅ Format must be one of: JPEG, PNG, WebP, BMP
  ✅ Binary content verified against MIME type
  ✅ Actual file size strictly limited to 5MB
  ✅ Magic bytes checked before processing
  ✅ Corrupted images rejected at binary level
```

---

## Production Deployment Checklist

### Pre-Deployment (Development)

- [ ] Generate `TRACK_ACCESS_SECRET` (do not use APP_KEY)
- [ ] Generate strong `APP_KEY` (if not already)
- [ ] Test with `.env.example` locally
- [ ] Run test suite: `php artisan test`
- [ ] Verify no hardcoded secrets in code

### Deployment Day

- [ ] Set production `.env` variables:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  APP_KEY=base64:your_key
  TRACK_ACCESS_SECRET=your_secret
  SESSION_SECURE_COOKIE=true
  ```

- [ ] Verify SSL/HTTPS is configured
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`

### Post-Deployment

- [ ] Monitor logs for `TRACK_ACCESS_SECRET` validation errors
- [ ] Test user login works
- [ ] Test image upload works
- [ ] Test signature drawing/upload works
- [ ] Verify HTTPS is enforced
- [ ] Check session cookies have Secure flag: 
  ```
  F12 → Application → Cookies → Check Secure ✓
  ```
- [ ] Verify no debug information in errors

### Monitoring

**Watch for these errors:**

```
1. RuntimeException: TRACK_ACCESS_SECRET is not configured
   → Action: Add TRACK_ACCESS_SECRET to .env

2. "Unable to store signature" or "Invalid image data"
   → Action: Check file permissions on storage/app

3. Failed image upload with no clear reason
   → Action: Check logs for magic bytes validation failures
```

---

## Best Practices

### For Developers

#### 1. Never Commit Secrets
```bash
# ✅ Good
echo ".env" >> .gitignore
echo "TRACK_ACCESS_SECRET=" >> .env.example

# ❌ Bad
git add .env  # Don't do this!
```

#### 2. Use Separate Keys for Different Purposes
```env
# ✅ Good
APP_KEY=base64:...
TRACK_ACCESS_SECRET=...
BREEZE_MAGIC_PHRASE=...

# ❌ Bad  
APP_KEY=mykey
TRACK_ACCESS_SECRET=mykey  # Reusing key
```

#### 3. Validate Images at Multiple Levels
```php
// ✅ Good - Multiple validation layers
if (!$this->isValidImageUpload($file)) return false;
if (!$this->validateImageMagicBytes($binary, $mime)) return false;
if (strlen($binary) > MAX_SIZE) return false;

// ❌ Bad - Only check extension
if (str_ends_with($file, '.jpg')) { save($file); }
```

#### 4. Handle Validation Errors Gracefully
```php
// ✅ Good - Inform user what happened
return back()->withErrors(['photo' => 'Photo must be a valid image under 5MB']);

// ❌ Bad - Silent failure
if (!validate($file)) return back();
```

### For Operations/DevOps

#### 1. Rotate Secrets Periodically
```bash
# Every 90 days (minimum)
# 1. Generate new TRACK_ACCESS_SECRET
# 2. Update .env
# 3. Monitor for errors
# 4. Archive old secret
```

#### 2. Use Separate .env for Production
```bash
# ✅ Process
.env.example → (template)
.env → (local, git-ignored)
.env.production → (server, git-ignored)

# Deploy to server
scp .env.production server:/app/.env
```

#### 3. Monitor Session Security
```bash
# Check HTTPS is enforced
curl -i https://your-app.gov.ph | grep Secure

# Verify session cookies
curl -i https://your-app.gov.ph/login | grep Set-Cookie
# Should show: Secure; HttpOnly; SameSite=Lax
```

#### 4. Log Validation Failures
```php
// Add to log:
// - Failed image uploads (mime, magic bytes, size)
// - Failed signature validations
// - TRACK_ACCESS_SECRET missing errors
```

---

## Troubleshooting

### Issue: "TRACK_ACCESS_SECRET is not configured"

**Cause:** `TRACK_ACCESS_SECRET` not set in `.env`

**Fix:**
```bash
# Generate a secret
php -r "echo base64_encode(random_bytes(32));"

# Add to .env
TRACK_ACCESS_SECRET=<paste_generated_secret>

# Restart application
```

### Issue: Image Upload Returns "Invalid Image Data"

**Possible Causes:**

1. **Wrong MIME type**
   ```php
   // Not in allowlist
   Supported: jpeg, png, webp, bmp
   Provided: gif, tiff, svg
   ```

2. **Corrupted image file**
   ```bash
   # Test file integrity
   file your-image.jpg
   # Should show: JPEG image data, JFIF standard
   ```

3. **Spoofed file** (e.g., .exe renamed to .jpg)
   ```bash
   # Check magic bytes
   xxd -l 10 your-image.jpg
   # Should start with: ff d8 ff (JPEG signature)
   ```

4. **File size exceeded**
   - Check: Is file > 5MB?
   - Solution: Compress or reduce image size

**Diagnostic Steps:**
```bash
# 1. Check file type
file your-image.jpg

# 2. Check file size
ls -lh your-image.jpg  # Should be < 5MB

# 3. Check magic bytes
xxd -l 4 your-image.jpg

# 4. Check logs for specific error
tail -f storage/logs/laravel.log | grep -i "image\|signature"
```

### Issue: Session Cookie Not Setting Properly

**Possible Causes:**

1. **HTTP instead of HTTPS in production**
   ```
   SESSION_SECURE_COOKIE=true
   But accessing: http://site.com ← Won't send cookie
   Solution: Enforce HTTPS
   ```

2. **Domain mismatch**
   ```env
   SESSION_DOMAIN=.your-domain.ph
   But accessing: sub.your-domain.ph ← Won't match
   ```

3. **Cookie settings in session.php not loaded**
   ```bash
   # Clear config cache
   php artisan config:clear
   ```

**Diagnostic Steps:**
```bash
# 1. Check cookies in browser
F12 → Application → Cookies → Look for session cookie

# 2. Verify cookie flags
Secure ✓
HttpOnly ✓
SameSite=Lax ✓

# 3. Check config loaded
php artisan tinker
>>> config('session.secure')
```

### Issue: Performance Degradation After Update

**Possible Cause:** Magic bytes validation on every image

**Solution:**
```php
// Consider caching validation results for known-good images
Cache::remember('image_valid_' . md5($binary), 3600, function () {
    return $this->validateImageMagicBytes($binary, $mime);
});
```

---

## Contact & Support

For security issues:
- **Email:** security@doh.gov.ph
- **Hotline:** +63-2-XXXX-XXXX
- **Secure Report:** [Report Security Issue](https://your-doh-site.gov.ph/security)

For technical support:
- **Email:** tech-support@doh.gov.ph
- **Hours:** Monday-Friday, 8 AM - 5 PM (PST)

---

## Document History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-06-03 | Security Team | Initial comprehensive guide |

---

**Last Updated:** June 3, 2026  
**Next Review:** September 3, 2026
