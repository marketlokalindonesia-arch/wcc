<?php
// utils/FileUploader.php

class FileUploader {
    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private $maxFileSize = 5 * 1024 * 1024; // 5MB
    private $uploadPath = "uploads/products/";
    private $errors = [];

    public function __construct($customPath = null) {
        if ($customPath) {
            $this->uploadPath = $customPath;
        }
        
        // Create upload directory if it doesn't exist
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function uploadProductImage($file, $productId) {
        $this->errors = [];

        // Validate file
        if (!$this->validateFile($file)) {
            return false;
        }

        // Generate secure filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = $this->generateSecureFilename($productId, $extension);
        $filepath = $this->uploadPath . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Create thumbnail
            $this->createThumbnail($filepath, $this->uploadPath . 'thumbs/' . $filename, 300, 300);
            
            return [
                'filename' => $filename,
                'filepath' => $filepath,
                'url' => $this->getFileUrl($filepath),
                'size' => $file['size'],
                'type' => $file['type']
            ];
        }

        $this->errors[] = "Failed to move uploaded file.";
        return false;
    }

    private function validateFile($file) {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadError($file['error']);
            return false;
        }

        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            $this->errors[] = "File size exceeds maximum allowed size (5MB).";
            return false;
        }

        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            $this->errors[] = "File type not allowed. Allowed types: " . implode(', ', $this->allowedExtensions);
            return false;
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ];

        if (!in_array($mime, $allowedMimes)) {
            $this->errors[] = "Invalid file type detected.";
            return false;
        }

        // Additional image validation
        if (!$this->isValidImage($file['tmp_name'])) {
            $this->errors[] = "File is not a valid image.";
            return false;
        }

        return true;
    }

    private function isValidImage($filepath) {
        $imageInfo = getimagesize($filepath);
        return $imageInfo !== false;
    }

    private function generateSecureFilename($productId, $extension) {
        $timestamp = time();
        $randomString = bin2hex(random_bytes(8));
        return "product_{$productId}_{$timestamp}_{$randomString}.{$extension}";
    }

    private function createThumbnail($sourcePath, $destPath, $width, $height) {
        if (!is_dir(dirname($destPath))) {
            mkdir(dirname($destPath), 0755, true);
        }

        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) return false;

        list($originalWidth, $originalHeight, $type) = $imageInfo;

        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case IMAGETYPE_WEBP:
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }

        $thumbnail = imagecreatetruecolor($width, $height);

        // Preserve transparency for PNG and GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagecolortransparent($thumbnail, imagecolorallocatealpha($thumbnail, 0, 0, 0, 127));
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
        }

        // Calculate aspect ratio
        $aspectRatio = $originalWidth / $originalHeight;
        $thumbRatio = $width / $height;

        if ($aspectRatio >= $thumbRatio) {
            $newHeight = $height;
            $newWidth = $width * ($originalHeight / $height);
            $srcX = ($originalWidth - $newWidth) / 2;
            $srcY = 0;
        } else {
            $newWidth = $width;
            $newHeight = $height * ($originalWidth / $width);
            $srcX = 0;
            $srcY = ($originalHeight - $newHeight) / 2;
        }

        imagecopyresampled($thumbnail, $sourceImage, 0, 0, $srcX, $srcY, $width, $height, $newWidth, $newHeight);

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($thumbnail, $destPath, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumbnail, $destPath, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($thumbnail, $destPath);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($thumbnail, $destPath, 85);
                break;
        }

        imagedestroy($sourceImage);
        imagedestroy($thumbnail);

        return true;
    }

    private function getUploadError($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive in HTML form',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        ];

        return $errors[$errorCode] ?? 'Unknown upload error';
    }

    private function getFileUrl($filepath) {
        // Adjust this based on your application's URL structure
        return str_replace($_SERVER['DOCUMENT_ROOT'], '', $filepath);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function deleteFile($filepath) {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
    
 public function uploadMultiple($files, $productId) {
        $results = [];
        
        foreach($files['tmp_name'] as $index => $tmp_name) {
            if($files['error'][$index] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$index],
                    'type' => $files['type'][$index],
                    'tmp_name' => $tmp_name,
                    'error' => $files['error'][$index],
                    'size' => $files['size'][$index]
                ];

                $result = $this->uploadProductImage($file, $productId);
                if($result) {
                    $results[] = $result;
                }
            }
        }

        return $results;
    }

    public function createImageVariations($filepath) {
        $variations = [];
        $sizes = [
            'thumbnail' => [300, 300],
            'medium' => [600, 600],
            'large' => [1200, 1200]
        ];

        foreach($sizes as $size => $dimensions) {
            $variation_path = $this->getVariationPath($filepath, $size);
            if($this->createThumbnail($filepath, $variation_path, $dimensions[0], $dimensions[1])) {
                $variations[$size] = $variation_path;
            }
        }

        return $variations;
    }

    private function getVariationPath($filepath, $size) {
        $pathinfo = pathinfo($filepath);
        return $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '-' . $size . '.' . $pathinfo['extension'];
    }

    public function validateImageDimensions($filepath, $minWidth = null, $minHeight = null) {
        $imageInfo = getimagesize($filepath);
        if(!$imageInfo) return false;

        list($width, $height) = $imageInfo;

        if($minWidth && $width < $minWidth) return false;
        if($minHeight && $height < $minHeight) return false;

        return true;
    }

    public function compressImage($filepath, $quality = 85) {
        $imageInfo = getimagesize($filepath);
        if(!$imageInfo) return false;

        $type = $imageInfo[2];

        switch($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($filepath);
                imagejpeg($image, $filepath, $quality);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($filepath);
                imagepng($image, $filepath, 9 - round($quality / 10));
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($filepath);
                imagewebp($image, $filepath, $quality);
                break;
        }

        if(isset($image)) {
            imagedestroy($image);
            return true;
        }

        return false;
    }
}
?>
?>