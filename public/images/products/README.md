# Product Images Directory

This directory contains product images for the SafeNest e-commerce platform.

## Required Images

Please add the following product images to this directory:

1. **bullet-security-camera.jpg** - Bullet-style outdoor security camera (white/black, mounted on wall)
2. **spherical-security-camera.jpg** - Indoor spherical security camera (white with black front, on shelf)
3. **smart-door-camera.jpg** - Smart doorbell camera (silver, rectangular with LED ring)
4. **smart-door-lock.jpg** - Biometric smart door lock (dark gray/black with fingerprint scanner)

## Image Specifications

- **Format:** JPG or PNG
- **Recommended Size:** 1200x800px or larger
- **Aspect Ratio:** 3:2 or 16:9
- **File Size:** Optimized for web (under 500KB recommended)

## How to Add Images

1. Save your product images in this directory with the exact filenames listed above
2. Run the database seeder to add products:
   ```bash
   php artisan db:seed --class=CatalogSeeder
   ```

The seeder will automatically link these images to the products.









