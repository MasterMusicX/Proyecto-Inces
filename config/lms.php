<?php
return [
    'name'              => env('APP_NAME', 'INCES LMS'),
    'max_file_size_mb'  => env('MAX_FILE_SIZE_MB', 50),
    'allowed_types'     => ['pdf','docx','xlsx','pptx','mp4','avi','mov','webm','jpg','jpeg','png','gif'],
    'document_types'    => ['pdf','docx','xlsx','pptx'],
    'video_types'       => ['mp4','avi','mov','webm'],
    'image_types'       => ['jpg','jpeg','png','gif','webp'],
    'roles'             => ['admin','instructor','student'],
];
