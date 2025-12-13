File uploads
============

File uploads allow web applications to transfer real files (binary data) efficiently and reliably, while preserving file content and metadata in a standardized way that works across browsers and servers.

## Issues

The native PHP `$_FILES` superglobal has several limitations and pitfalls that developers often run into:

- Awkward structure: When handling multiple files, `$_FILES` becomes a deeply nested array that is difficult to iterate over and easy to misuse.
- Upload errors are easy to overlook: Failing to check `$_FILES['error']` can result in processing incomplete or failed uploads.
- Populated without uploads: `$_FILES` may contain entries even when no file was uploaded, typically with the `UPLOAD_ERR_NO_FILE` error code, which can be misleading if not handled explicitly.

To address these issues, a dedicated file upload component was introduced.

## Prepare

To submit a file using an HTML form, ensure the form uses the POST method and the correct encoding type:

    <form action="/upload" method="POST" enctype="multipart/form-data">
      <label for="document">Choose a document:</label>
      <input type="file" id="document" name="document">
      <button type="submit">Upload</button>
    </form>

Alternatively, files can be sent via JavaScript using FormData:

    function upload() {
      const fileInput = document.getElementById("document");
      const file = fileInput.files[0];
    
      const formData = new FormData();
      formData.append("file", file);
    
      fetch("/upload", {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => console.log(data))
      .catch(err => console.error(err));
    }
    
    upload();

## Accessing uploaded file

To access uploaded files, call the `getFiles()` method on the request service from within your controller:

    public function uploadAction()
    {
        // Get file instance from input named "document"
        $file = $this->request->getFiles('document');
    
        if ($file) {
            $file->getName();       // Original filename
            $file->getUniqueName(); // Unique filename (preserves extension)
            $file->getType();       // Guessed MIME type
            $file->getTmpName();    // Temporary file path
            $file->getError();      // Native PHP upload error code
            $file->getSize();       // File size in bytes
    
            // ...
        }
    
        // ...
    }

If no file was uploaded, `getFiles()` returns an empty array.

Regardless of input name or nesting depth, this method always returns a normalized and predictable collection of uploaded files, unlike the native `$_FILES` superglobal.

## Uploading a file

To persist uploaded files, use the dedicated uploader component:

    <?php
    
    use Krystal\Http\FileTransfer\FileUploader;
    
    class User
    {
        public function upload()
        {
            $files = $this->request->getFiles('files'); // If parameter provided, only from that input data is exacracted
            
            if ($files) { // If there's a file
                $destination = $this->appConfig->getUploadsDir(); // e.g /data/uploads/
                
                $uploader = new FileUploader();
                $uploader->upload($destination, $files); // Returns boolean value
            }
        }
    }

