<?php

$dir = 'c:\\laragon\\www\\yahaira\\resources\\views';

function scanAllDir($dir) {
    $result = [];
    foreach(scandir($dir) as $filename) {
        if ($filename[0] === '.') continue;
        $filePath = $dir . '\\' . $filename;
        if (is_dir($filePath)) {
            foreach (scanAllDir($filePath) as $childFilename) {
                $result[] = $childFilename;
            }
        } else {
            $result[] = $filePath;
        }
    }
    return $result;
}

$files = scanAllDir($dir);

$pattern = '/\s*<div class="top-side-icon">[\s\S]*?<\/div>/';

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $content = file_get_contents($file);
        if (strpos($content, 'class="top-side-icon"') !== false) {
            $newContent = preg_replace($pattern, '', $content);
            file_put_contents($file, $newContent);
            echo "Updated: $file\n";
        }
    }
}
echo "Done.";
