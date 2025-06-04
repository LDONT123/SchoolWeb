document.addEventListener('DOMContentLoaded', () => {
    const imageFileElement = document.getElementById('imageFile');
    const uploadButtonElement = document.getElementById('uploadButton');
    const imagePreviewElement = document.getElementById('imagePreview');
    const initialPreviewText = imagePreviewElement.innerHTML; // Store initial text

    uploadButtonElement.addEventListener('click', async () => {
        const file = imageFileElement.files[0];

        if (!file) {
            alert('Please select an image file first.');
            return;
        }

        // Clear previous previews if it's the first successful upload
        if (imagePreviewElement.innerHTML === initialPreviewText) {
            imagePreviewElement.innerHTML = '';
        }

        const formData = new FormData();
        formData.append('imageFile', file);

        // Disable button and show loading state (optional)
        uploadButtonElement.disabled = true;
        uploadButtonElement.textContent = 'Uploading...';

        try {
            const response = await fetch('upload.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success && result.url) {
                // Create elements to display the image and its URL
                const imageContainer = document.createElement('div');
                imageContainer.className = 'uploaded-image-container';

                const img = document.createElement('img');
                img.src = result.url;
                img.alt = 'Uploaded Image';

                const link = document.createElement('a');
                link.href = result.url;
                link.textContent = result.url;
                link.className = 'image-link';
                link.target = '_blank'; // Open in new tab

                const copyButton = document.createElement('button');
                copyButton.textContent = 'Copy URL';
                copyButton.className = 'copy-button';
                copyButton.addEventListener('click', () => {
                    navigator.clipboard.writeText(result.url).then(() => {
                        copyButton.textContent = 'Copied!';
                        copyButton.classList.add('copied');
                        setTimeout(() => {
                            copyButton.textContent = 'Copy URL';
                            copyButton.classList.remove('copied');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy URL: ', err);
                        alert('Failed to copy URL. Please copy it manually.');
                    });
                });

                imageContainer.appendChild(img);
                imageContainer.appendChild(link);
                imageContainer.appendChild(copyButton);

                // Prepend the new image container to the preview area
                imagePreviewElement.prepend(imageContainer);

            } else {
                alert('Upload failed: ' + (result.error || 'Unknown error'));
            }

        } catch (error) {
            console.error('Error uploading file:', error);
            alert('An error occurred during upload. Check the console for details.');
        } finally {
            // Re-enable button and restore text
            uploadButtonElement.disabled = false;
            uploadButtonElement.textContent = 'Upload';
            imageFileElement.value = ''; // Clear the file input
        }
    });
});
