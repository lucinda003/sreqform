# Image Validation - Developer Reference

**Quick Start Guide for Working with Image Uploads & Validation**

---

## Image Upload Flow

```
User selects image
    ↓
[Browser validates size < 5MB]
    ↓
[Browser validates MIME type in allowlist]
    ↓
Submit to /service-requests/store
    ↓
[Laravel validates file upload]
    ├─ Check: isValidImageUpload()
    │  ├─ Instance check: instanceof UploadedFile
    │  ├─ Valid flag: $file->isValid()
    │  ├─ MIME type whitelist (jpeg, png, webp, bmp)
    │  ├─ Extension whitelist (.jpg, .jpeg, .png, .webp, .bmp)
    │  ├─ File size: ≤ 5MB (5,242,880 bytes)
    │  ├─ Real path exists
    │  └─ Magic bytes validation via getimagesize()
    │
    └─ Check: validateImageMagicBytes()
       ├─ PNG: 89 50 4E 47
       ├─ JPEG: FF D8 FF
       ├─ WebP: 52 49 46 46 + WEBP at byte 8
       └─ BMP: 42 4D
    ↓
[If valid: Encrypt & store]
    ├─ Call: EncryptedSignature::storeBinary()
    └─ Path: storage/app/private/signatures/...
    ↓
[If invalid: Return error to user]
    └─ Message: "Invalid image data" or specific validation error
    ↓
Return response
```

---

## Data URI (Canvas/Drawn) Validation

```
User draws signature on canvas
    ↓
Canvas converts to base64 data URI
    └─ Format: data:image/png;base64,iVBORw0KG...
    ↓
Submit as approved_by_signature_drawn field
    ↓
[Server calls decodeImageDataUri()]
    ├─ Step 1: Check format matches regex
    │  └─ Pattern: ^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$
    │
    ├─ Step 2: Validate MIME type in allowlist
    │  └─ Allowed: jpeg, png, webp, bmp
    │
    ├─ Step 3: Base64 decode
    │  └─ Check: Result is not false/empty
    │
    ├─ Step 4: Check decoded size
    │  └─ Limit: ≤ 5,242,880 bytes (5MB)
    │
    └─ Step 5: Validate magic bytes
       └─ Call: validateImageMagicBytes($binary, $mime)
    ↓
[If valid: Return decoded array]
    └─ Array: ['mime' => 'image/png', 'binary' => '...']
    ↓
[If invalid: Return null]
    ↓
[Process result]
```

---

## Common Validation Failures & Solutions

### 1. Upload Returns "Invalid Image Data"

**Cause:** File fails one of the validation checks

**Debug Steps:**

```bash
# Step 1: Check file type
file /path/to/uploaded/file.jpg
# Output should show: JPEG image data, JFIF standard

# Step 2: Check file size
ls -lh /path/to/uploaded/file.jpg
# Should be < 5MB (5242880 bytes)

# Step 3: Check magic bytes (first 4 bytes)
xxd -l 4 /path/to/uploaded/file.jpg
# JPEG should show: ff d8 ff f0 or similar
# PNG should show: 89 50 4e 47

# Step 4: Check getimagesize works
php -r "var_dump(getimagesize('/path/to/file.jpg'));"
# Should return array with image info, not false
```

**Solutions:**

```
File type issue?
  → Use ImageMagick: convert file.jpg -format jpg file-fixed.jpg
  
File corrupted?
  → Re-download original from source
  
File too large?
  → Compress: convert file.jpg -quality 85 file-small.jpg
  → Or: use online tool to reduce size
  
Wrong format?
  → Convert to supported format (JPEG, PNG, WebP, BMP)
  → Example: convert file.gif file.jpg
```

### 2. Data URI Validation Fails

**Cause:** Canvas-drawn signature doesn't meet requirements

**Common Issues:**

```javascript
// ❌ WRONG - Base64 string without data URI format
const wrong = "iVBORw0KGgoAAAA...";

// ✅ CORRECT - Proper data URI format
const correct = "data:image/png;base64,iVBORw0KGgoAAAA...";

// ❌ WRONG - Wrong MIME type
const wrong = "data:image/gif;base64,iVBORw0KGgoAAAA...";

// ✅ CORRECT - Supported MIME types
const correct = "data:image/png;base64,...";  // PNG ✓
const correct = "data:image/jpeg;base64,..."; // JPEG ✓
const correct = "data:image/webp;base64,..."; // WebP ✓
const correct = "data:image/bmp;base64,...";  // BMP ✓
```

**Debug PHP:**

```php
// Test data URI
$dataUri = "data:image/png;base64,iVBORw0KG...";

// Check format
$matches = [];
if (preg_match('/^data:(image\/[a-zA-Z0-9.+-]+);base64,(.+)$/s', $dataUri, $matches)) {
    echo "Format valid\n";
    echo "MIME type: " . $matches[1] . "\n";
    echo "Base64 length: " . strlen($matches[2]) . "\n";
} else {
    echo "Format invalid\n";
}

// Decode and check size
$binary = base64_decode($matches[2], true);
echo "Decoded size: " . strlen($binary) . " bytes\n";
echo "Size OK: " . (strlen($binary) <= 5242880 ? "Yes" : "No") . "\n";
```

### 3. "Unsupported MIME type" Error

**Cause:** Uploading in unsupported format

**Unsupported Formats:**

```
❌ GIF (.gif)
  → Solution: Convert to PNG or JPEG
  
❌ TIFF (.tif, .tiff)
  → Solution: Convert to PNG or JPEG
  
❌ SVG (.svg)
  → Solution: Export as PNG or convert with Inkscape
  
❌ BMP (.bmp) - Actually supported!
  → If rejected: Check magic bytes (should be 42 4D)
  
❌ WEBP not working?
  → Check PHP has webp support: php -r "var_dump(extension_loaded('gd'));"
  → Check magic bytes: First 4 bytes should be 52 49 46 46 (RIFF)
```

**Convert Image (Using ImageMagick):**

```bash
# GIF to PNG
convert input.gif output.png

# TIFF to JPEG
convert input.tif output.jpg

# SVG to PNG (requires imagemagick with SVG support)
convert input.svg output.png

# Multiple formats
for f in *.gif; do convert "$f" "${f%.gif}.png"; done
```

---

## Testing Image Validation Locally

### Create Test Images

```bash
# 1. Create valid PNG
php -r "
\$image = imagecreatetruecolor(100, 100);
\$white = imagecolorallocate(\$image, 255, 255, 255);
imagefill(\$image, 0, 0, \$white);
imagepng(\$image, 'test.png');
imagedestroy(\$image);
echo 'PNG created\n';
"

# 2. Create valid JPEG
php -r "
\$image = imagecreatetruecolor(100, 100);
\$white = imagecolorallocate(\$image, 255, 255, 255);
imagefill(\$image, 0, 0, \$white);
imagejpeg(\$image, 'test.jpg');
imagedestroy(\$image);
echo 'JPEG created\n';
"

# 3. Create corrupted image
echo "not an image" > corrupted.jpg

# 4. Create too-large image
php -r "
\$image = imagecreatetruecolor(10000, 10000);
imagejpeg(\$image, 'large.jpg', 50);
echo 'Large image created\n';
"
```

### Test Upload Endpoint

```bash
# Upload valid image
curl -X POST http://localhost:8000/service-requests/store \
  -F "photo=@test.png" \
  -F "other_fields=value"

# Upload corrupted image (should fail)
curl -X POST http://localhost:8000/service-requests/store \
  -F "photo=@corrupted.jpg"

# Upload too-large image (should fail)
curl -X POST http://localhost:8000/service-requests/store \
  -F "photo=@large.jpg"
```

### Test Data URI Validation

```bash
# Create base64 encoded PNG
BASE64=$(base64 -w 0 test.png)
DATA_URI="data:image/png;base64,$BASE64"

# Use in JSON request
curl -X POST http://localhost:8000/api/signature/validate \
  -H "Content-Type: application/json" \
  -d "{\"signature_drawn\": \"$DATA_URI\"}"

# Test invalid MIME type
INVALID_URI="data:image/gif;base64,$BASE64"
curl -X POST http://localhost:8000/api/signature/validate \
  -H "Content-Type: application/json" \
  -d "{\"signature_drawn\": \"$INVALID_URI\"}"
```

---

## Magic Bytes Reference

When adding new image formats, use these signatures:

```
Format | Magic Bytes | Hex | Decimal | Usage
-------|------------|-----|---------|-------
PNG    | .PNG       | 89 50 4E 47 | 137 80 78 71 | Logo, screenshot
JPEG   | ÿØÿ        | FF D8 FF | 255 216 255 | Photo, signature
WebP   | RIFF...WEBP| 52 49 46 46...57 45 42 50 | Modern format
BMP    | BM         | 42 4D | 66 77 | Simple image
GIF87a | GIF87a     | 47 49 46 38 37 61 | Animated (not supported)
GIF89a | GIF89a     | 47 49 46 38 39 61 | Animated (not supported)
TIFF   | II* or MM* | 49 49 2A 00 or 4D 4D 00 2A | Scan (not supported)
```

**Adding New Format:**

```php
// In validateImageMagicBytes()
$magicPatterns = [
    'image/jpeg' => [0xFF, 0xD8, 0xFF],
    'image/png' => [0x89, 0x50, 0x4E, 0x47],
    'image/webp' => [0x52, 0x49, 0x46, 0x46],
    'image/bmp' => [0x42, 0x4D],
    // Add new format here:
    // 'image/tiff' => [0x49, 0x49, 0x2A, 0x00], // or [0x4D, 0x4D, ...]
];
```

---

## Performance Tips

### Cache Validation Results

```php
// Instead of validating every time:
$key = 'image_valid_' . md5($binary);
$isValid = Cache::remember($key, 3600, function () use ($binary, $mime) {
    return $this->validateImageMagicBytes($binary, $mime);
});
```

### Pre-Validate in Browser

```javascript
// Check MIME type before upload
const validateBeforeUpload = (file) => {
    const allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/bmp'];
    return allowedMimes.includes(file.type);
};

// Check size before upload
const validateSize = (file) => {
    const maxSize = 5242880; // 5MB
    return file.size <= maxSize;
};
```

### Batch Processing

```php
// Process multiple images efficiently
$images = $request->file('photos');
$validated = collect($images)->map(function ($photo) {
    if (!$this->isValidImageUpload($photo)) {
        throw new ValidationException(...);
    }
    return $photo;
})->toArray();
```

---

## Logs & Monitoring

### What to Log

```php
// Log failed validations
Log::warning('Image validation failed', [
    'reason' => 'magic_bytes_mismatch',
    'expected_mime' => 'image/png',
    'actual_bytes' => bin2hex(substr($binary, 0, 4)),
    'file_size' => strlen($binary),
    'user_id' => auth()->id(),
]);

// Log suspicious uploads
Log::alert('Suspicious upload attempt', [
    'file_name' => $request->file('photo')->getClientOriginalName(),
    'claimed_mime' => $request->file('photo')->getMimeType(),
    'actual_mime' => mime_content_type($path),
    'user_id' => auth()->id(),
    'ip_address' => $request->ip(),
]);
```

### Monitoring Queries

```bash
# Count failed uploads
grep "Image validation failed" storage/logs/laravel.log | wc -l

# Find suspicious patterns
grep "Suspicious upload" storage/logs/laravel.log

# Recent failures
tail -100 storage/logs/laravel.log | grep -i "image\|signature\|validation"
```

---

## References

- [Laravel File Upload Validation](https://laravel.com/docs/validation#image)
- [Image Magic Bytes (File Signatures)](https://en.wikipedia.org/wiki/List_of_file_signatures)
- [PHP getimagesize() Function](https://www.php.net/manual/en/function.getimagesize.php)
- [OWASP File Upload Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/File_Upload_Cheat_Sheet.html)

---

**Last Updated:** June 3, 2026  
**Maintained By:** Security Team  
**Questions?** Contact: tech-support@doh.gov.ph
