# Manual Test Cases for Image Uploader

## 1. Successful Image Upload

**Objective:** Verify that valid image files can be uploaded and displayed correctly.

*   **Test Case 1.1: Upload JPG Image**
    1.  Open `index.php` in a web browser.
    2.  Click the "Choose File" (or similarly labeled) input field.
    3.  Select a valid JPG image file (e.g., `test.jpg`).
    4.  Click the "Upload" button.
    5.  **Expected Result:**
        *   The "Upload" button should briefly show "Uploading..." and then revert to "Upload".
        *   The initial text "Your uploaded images will appear here." should be replaced.
        *   A preview of the uploaded JPG image should appear in the "imagePreview" area.
        *   Below the image, its full URL (e.g., `http://localhost/uploads/img_xxxxxxxx.jpg`) should be displayed as a clickable link.
        *   Click the displayed URL. It should open the image directly in a new browser tab.
        *   The file should be present in the `uploads/` directory on the server.

*   **Test Case 1.2: Upload PNG Image**
    1.  Refresh `index.php` or ensure the previous upload is cleared.
    2.  Click the "Choose File" input field.
    3.  Select a valid PNG image file (e.g., `test.png`).
    4.  Click the "Upload" button.
    5.  **Expected Result:**
        *   Similar to JPG, a preview of the PNG image should appear.
        *   Its URL should be displayed and be functional.
        *   The file should be present in the `uploads/` directory.

*   **Test Case 1.3: Upload GIF Image**
    1.  Refresh `index.php` or ensure the previous upload is cleared.
    2.  Click the "Choose File" input field.
    3.  Select a valid GIF image file (e.g., `test.gif`).
    4.  Click the "Upload" button.
    5.  **Expected Result:**
        *   Similar to JPG/PNG, a preview of the GIF image should appear (animated if it's an animated GIF).
        *   Its URL should be displayed and be functional.
        *   The file should be present in the `uploads/` directory.

## 2. Copy URL Functionality

**Objective:** Verify the "Copy URL" button works as expected.

*   **Test Case 2.1: Copy URL and Verify**
    1.  Successfully upload any valid image as per Section 1.
    2.  Locate the "Copy URL" button associated with the uploaded image.
    3.  Click the "Copy URL" button.
    4.  **Expected Result:**
        *   The button text should immediately change to "Copied!".
        *   The button style might change (e.g., background color).
        *   Open a text editor or another browser tab's address bar and paste (Ctrl+V or Cmd+V). The pasted content should be the exact URL displayed for the image.
        *   After approximately 2 seconds, the button text should revert to "Copy URL", and its style should return to normal.

## 3. User Interface and Styling

**Objective:** Verify the visual appearance and basic responsiveness.

*   **Test Case 3.1: Default Theme and Appearance**
    1.  Open `index.php` in a web browser.
    2.  **Expected Result:**
        *   The page should have a dark background (#1a1a1a).
        *   Text color should be light (#e0e0e0).
        *   The main container should have a darker background (#2c2c2c) and rounded corners.
        *   The heading "Upload Your Image" should be white.
        *   The file input should have a dashed border and custom styling for its button.
        *   The "Upload" button should be blue (#007bff).
        *   The image preview area should initially contain the text "Your uploaded images will appear here." with specific styling.

*   **Test Case 3.2: Hover States**
    1.  Hover over the file input area.
        *   **Expected Result:** Border color should change (e.g., to blue).
    2.  Hover over the "Upload" button.
        *   **Expected Result:** Background color should darken, and the button might slightly lift (transform).
    3.  After uploading an image, hover over the image link and the "Copy URL" button.
        *   **Expected Result:** Link background should change; "Copy URL" button background should darken.

*   **Test Case 3.3: Basic Responsiveness (Conceptual)**
    1.  If using a desktop browser, try resizing the browser window to be narrower.
    2.  **Expected Result:**
        *   The container should resize gracefully, not exceeding the viewport width.
        *   Elements should ideally stack or resize appropriately to remain usable. (e.g., long URLs in the preview should wrap).
        *   No horizontal scrollbars should appear due to fixed-width elements if possible.

## 4. Error Handling

**Objective:** Verify the application handles common error scenarios gracefully.

*   **Test Case 4.1: No File Selected**
    1.  Open `index.php`.
    2.  Do not select any file using the file input.
    3.  Click the "Upload" button.
    4.  **Expected Result:** An alert box should appear with the message: "Please select an image file first."

*   **Test Case 4.2: Invalid File Type**
    1.  Open `index.php`.
    2.  Click the "Choose File" input.
    3.  Select a non-image file (e.g., a `.txt` file, `.pdf` file).
    4.  Click the "Upload" button.
    5.  **Expected Result:** An alert box should appear with a message similar to: "Upload failed: Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed."

*   **Test Case 4.3: File Too Large (Conceptual - Requires Server Configuration)**
    *   **Note:** This test depends on `upload_max_filesize` and `post_max_size` in `php.ini`. If these are very large, this might be hard to test without reconfiguring the server.
    1.  Attempt to upload an image file that is known to be larger than the server's configured limit.
    2.  **Expected Result:** An alert box should appear with a message similar to: "Upload failed: File is too large."

*   **Test Case 4.4: Uploads Directory Not Writable (Conceptual - Requires Manual Intervention)**
    *   **Note:** This requires temporarily changing permissions on the `uploads/` directory on the server (e.g., to `chmod 444 uploads`). Remember to change it back to `chmod 777 uploads` afterwards.
    1.  Make the `uploads/` directory read-only.
    2.  Attempt to upload a valid image.
    3.  **Expected Result:** An alert box should appear with a message similar to: "Upload failed: Failed to move uploaded file. Check permissions." or a more generic server error if the PHP script's error reporting is different.

## 5. Multiple Uploads

**Objective:** Verify that multiple images can be uploaded sequentially and are displayed correctly.

*   **Test Case 5.1: Sequential Uploads**
    1.  Upload a valid image (e.g., `image1.jpg`).
        *   **Expected Result:** `image1.jpg` preview appears.
    2.  Select and upload another valid image (e.g., `image2.png`) without refreshing the page.
        *   **Expected Result:** `image2.png` preview should appear *above* `image1.jpg` in the preview area. The `imagePreviewElement.prepend(imageContainer)` in `script.js` dictates this.
    3.  Upload a third image (e.g., `image3.gif`).
        *   **Expected Result:** `image3.gif` preview should appear *above* `image2.png`.
    4.  Verify that each uploaded image has its own image preview, URL, and "Copy URL" button.
    5.  Test the "Copy URL" functionality for each of the uploaded images to ensure they copy the correct, distinct URLs.

This list covers the main functionalities and common scenarios for the image uploader application.
