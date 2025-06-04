# Simple PHP Image Uploader

A minimalist, modern-style image hosting service running on PHP 8.3. It features a dark mode interface by default and does not require user login.

## Features

*   **No Login Required:** Upload images anonymously.
*   **Modern Interface:** Clean and user-friendly design with a default dark theme.
*   **Drag and Drop (Browser Default):** The file input should support drag and drop in most modern browsers.
*   **Image Preview:** See a preview of your uploaded image.
*   **Copy URL:** Easily copy the direct URL of the uploaded image.
*   **Supported Formats:** JPG, JPEG, PNG, GIF.

## Requirements

*   PHP 8.3 or higher
*   Web server (Apache, Nginx, etc.) with write permissions for the `uploads/` directory.

## Setup

1.  **Clone the repository or download the files.**
    ```bash
    git clone <repository_url>
    cd <repository_directory>
    ```
    (Or simply place the files `index.php`, `upload.php`, `style.css`, `script.js` and the `uploads` directory in your web server's document root or a subdirectory).

2.  **Ensure `uploads/` directory is writable.**
    The `upload.php` script will attempt to create this directory. However, you should ensure your web server has write permissions for it.
    If you create it manually:
    ```bash
    mkdir uploads
    chmod -R 777 uploads
    ```
    (Adjust permissions as necessary for your server environment; 777 is broadly permissive, 755 might be sufficient if the web server user owns the directory).

3.  **Configure your web server** (if not placing in the root directory).
    Point your web server (Apache, Nginx, etc.) to serve `index.php`. If you're placing it in a subdirectory (e.g., `yourdomain.com/uploader/`), ensure the paths in the application (especially the image URL generation in `upload.php` and links in `index.php`) work correctly. The current `upload.php` generates absolute URLs which should generally work.

4.  **Access `index.php` in your browser.**
    Navigate to the URL where you've set up the application (e.g., `http://localhost/index.php` or `http://yourdomain.com/uploader/index.php`).

## File Structure

*   `index.php`: The main HTML page for uploading images.
*   `upload.php`: The PHP script that handles file uploads.
*   `style.css`: Contains all the CSS for styling the application (dark mode, modern look).
*   `script.js`: JavaScript for handling client-side interactions (uploading, previewing, copying URL).
*   `uploads/`: Directory where uploaded images are stored.
*   `MANUAL_TEST_CASES.md`: Contains a list of test cases to manually verify application functionality.

## How it Works

1.  The user selects an image file using the form on `index.php`.
2.  JavaScript (`script.js`) captures the file and sends it to `upload.php` using a `fetch` POST request.
3.  `upload.php` validates the file (type, size, errors), generates a unique name, and moves it to the `uploads/` directory.
4.  `upload.php` returns a JSON response with the direct URL to the uploaded image or an error message.
5.  `script.js` receives the response. If successful, it displays a preview of the image, the image URL, and a "Copy URL" button. Otherwise, it shows an error.

## PHP Configuration Notes

*   Ensure your `php.ini` has appropriate `upload_max_filesize` and `post_max_size` directives to allow for the desired maximum image file sizes.
*   The `fileinfo` PHP extension is recommended for more robust MIME type checking on the server side, though not explicitly used in this basic version for simplicity (relies on file extension).
