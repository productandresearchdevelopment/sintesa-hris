<?php

namespace App\Libraries;

use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\SystemModels\Globals\Upload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;

class FileUpload
{
    const TEMP_DIR = "tmp";

    /** @var string|null */
    private $PATH;
    /** @var string|null */
    private $UPLOAD_PATH;
    /** @var string|null */
    private $WATERMARK;
    /** @var string|null */
    private $CATEGORY;

    /**
     * @param string|null $category
     * @param string|null $watermark
     * @param string|null $path
     */
    public function __construct($category = null, $watermark = null, $path = null)
    {
        $this->WATERMARK = $watermark ?: null;
        $this->CATEGORY = $category ?: null;
        $this->PATH = $path ?: ($category . '/' . date('Ymd'));

        $this->UPLOAD_PATH = config('filesystems.upload_dir');
        $this->UPLOAD_PATH = $this->UPLOAD_PATH ? $this->UPLOAD_PATH . '/' : '';
    }

    /**
     * UPLOAD FILE MENGAMBIL DARI NAMA PARAMETER
     *
     * @param string|UploadedFile|mixed $param
     * @param string|null $category
     * @param string|null $watermark
     * @param string|null $path
     * @return mixed
     */
    public static function upload($param, $category = null, $watermark = null, $path = null)
    {
        $fileUpload = new FileUpload($category, $watermark, $path);
        $request = request();

        if (is_uploaded_file($param)) return $fileUpload->pushFile($param);
        else if ($file = $request->file($param)) {
            if (gettype($file) == 'array') {
                $result = [];
                foreach ($file as $f) {
                    if ($f) {
                        if ($res = $fileUpload->pushFile($f)) $result[] = $res;
                    }
                }
                return $result;
            }
            return $fileUpload->pushFile($file);
        } else if ($content = $request->input($param)) {
            if (gettype($content) == 'array') {
                $result = [];
                foreach ($content as $c) {
                    if ($c) {
                        if ($res = $fileUpload->pushContent($c)) $result[] = $res;
                    }
                }
                return $result;
            } else if ($content) return $fileUpload->pushContent($content);
        }

        return null;
    }

    /**
     * UPLOAD FILE DARI TEXT BASE64
     *
     * @param mixed $dataFile
     * @param string|null $category
     * @param string|null $watermark
     * @param string|null $path
     * @return mixed
     */
    public static function push($dataFile, $category = null, $watermark = null, $path = null)
    {
        $fileUpload = new FileUpload($category, $watermark, $path);
        if (gettype($dataFile) == 'array') {
            $result = [];
            foreach ($dataFile as $tfile) {
                if ($tfile) $result[] = $fileUpload->pushContent($tfile);
            }
            return $result;
        } else if ($dataFile) return $fileUpload->pushContent($dataFile);

        return null;
    }

    /**
     * HAPUS BATCH FILE
     *
     * @param string|array $category
     * @param string|null $from
     * @param string|null $to
     * @return mixed
     */
    public static function removePath($category, $from = null, $to = null)
    {
        if ($category) {
            $query = Upload::select('path', DB::raw('count(*) as total'))->groupBy('path');

            if ($category != '*') {
                if (is_array($category)) $query->whereIn('category', $category);
                else $query->where('category', $category);
            }

            if ($from) {
                $to = $to ?: $from;
                $query->whereBetween('created_at', ["$from 00:00:00", "$to 23:59:59"]);
            }

            $data = $query->get();

            foreach ($data as $r) {
                $path = $r->path;

                $uploadPath = config('filesystems.upload_dir');
                $uploadPath = $uploadPath ? $uploadPath . '/' : '';
                $uploadPath = $uploadPath . $path;

                Storage::disk(config('filesystems.upload_disk'))->deleteDirectory($uploadPath);
                Upload::where('path', $path)->delete();
            }

            return $data;
        }
        return null;
    }

    /**
     * @param string|array|null $ids
     * @return mixed
     */
    public static function removeFileById($ids)
    {
        if ($ids) {
            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $data = Upload::whereIn('id', $ids)->get();

            foreach ($data as $upload) {
                $path = $upload->path;
                $filename = $upload->filename;

                $patternFileName = '/[^\/]+$/';
                preg_match($patternFileName, $filename, $matches);
                $fileName = $matches[0] ?? null;

                if (!$fileName) {
                    Log::warning("No filename found for path: " . $filename);
                    continue;
                }

                $disk = Storage::disk(config('filesystems.upload_disk'));
                $uploadPath = config('filesystems.upload_dir') ? config('filesystems.upload_dir') . '/' : '';
                $filePath = $uploadPath . $path . '/' . $fileName;

                if ($disk->exists($filePath)) {
                    $disk->delete($filePath);
                } else {
                    Log::warning("File not found, could not delete: " . $filePath);
                }

                $directoryPath = dirname($filePath);
                if (!$disk->files($directoryPath) && !$disk->directories($directoryPath)) {
                    $disk->deleteDirectory($directoryPath);
                }

                $upload->forceDelete();
            }

            return $data;
        }

        return null;
    }

    /**
     * @param UploadedFile|mixed $file
     * @return string|null
     */
    public function pushFile($file)
    {
        $id = (string) Str::uuid();
        $ext = $file->getClientOriginalExtension() ?: ($file->extension() ?: 'png');
        $filename = "$id.$ext";

        /** @var FilesystemAdapter $localDisk */
        $localDisk = Storage::disk('local');

        if (!$localDisk->exists(self::TEMP_DIR)) {
            $localDisk->makeDirectory(self::TEMP_DIR);
        }

        $localDisk->putFileAs(self::TEMP_DIR, $file, $filename);
        $result = $this->save($id, $filename, $ext, $file->getClientOriginalName());
        $localDisk->delete($this->tempFile($filename));

        return $result;
    }

    /**
     * @param string|array $content
     * @return mixed
     */
    public function pushContent($content)
    {
        $data = explode(':', $content);
        if (count($data) > 1 && $data[0] == 'data') {
            $data = explode(';', $data[1]);
            if (count($data) > 1) {
                $mime = $data[0];
                $mimes = explode('/', $mime);
                $data = explode(',', $data[1]);

                if (count($mimes) > 1 && count($data) > 1) {
                    $id = (string)Str::uuid();
                    $data = $data[1];
                    $ext = $mimes[1];
                    $filename = "$id.$ext";

                    /** @var FilesystemAdapter $localDisk */
                    $localDisk = Storage::disk('local');

                    if (!$localDisk->exists(self::TEMP_DIR)) {
                        $localDisk->makeDirectory(self::TEMP_DIR);
                    }

                    $localDisk->put($this->tempFile($filename), base64_decode($data));
                    $result = $this->save($id, $filename, $ext);
                    $localDisk->delete($this->tempFile($filename));

                    return $result;
                }
            }
        }
        return null;
    }

    /**
     * @param string $id
     * @param string $filename
     * @param string $extension
     * @param string|null $filenameOrigin
     * @return string|null
     */
    public function save($id, $filename, $extension, $filenameOrigin = null)
    {
        $tmpfile = $this->tempFile($filename);

        /** @var FilesystemAdapter $localDisk */
        $localDisk = Storage::disk('local');
        $tmppath = $localDisk->path($tmpfile);

        if (!file_exists($tmppath)) {
            Log::error("Temp file does not exist at path: " . $tmppath);
            return null;
        }

        try {
            $mime = $localDisk->mimeType($tmpfile) ?: 'application/octet-stream';
        } catch (\Throwable $e) {
            $mime = 'application/octet-stream';
        }

        try {
            $size = $localDisk->size($tmpfile);
        } catch (\Throwable $e) {
            $size = @filesize($tmppath) ?: 0;
        }

        $type = explode('/', $mime)[0];
        $pathfile = $this->pathFile($filename);

        $watermark = null;
        if ($this->WATERMARK && $type == 'image') $watermark = $this->watermark($tmppath);

        $uploadPath = $this->UPLOAD_PATH . $pathfile;

        if (Storage::disk(config('filesystems.upload_disk'))->put($uploadPath, @file_get_contents($tmppath), 'public')) {
            Upload::create([
                'id' => $id,
                'filename' => $pathfile,
                'category' => $this->CATEGORY,
                'path' => $this->PATH,
                'type' => $type,
                'mime' => $mime,
                'extension' => $extension,
                'size' => $size,
                'watermark' => $watermark,
                'filename_origin' => $filenameOrigin
            ]);
            return $id;
        }

        return null;
    }

    /**
     * @param string $pathfile
     * @param string $filesystem
     * @return string|null
     */
    public function watermark($pathfile, $filesystem = 'local')
    {
        if ($pathfile) {
            if ($watermark = $this->WATERMARK) {
                $watermark = str_replace('{date}', date('Y-m-d'), $watermark);
                $watermark = str_replace('{time}', date('H:i:s'), $watermark);
                $watermark = str_replace('{datetime}', date('Y-m-d H:i:s'), $watermark);

                /** @var \Intervention\Image\Image $img */
                $img = Image::make($pathfile);
                $img->text($watermark, 10, $img->height() - 10, function ($font) {
                    $font->file(storage_path('font/quicksand/medium.ttf'));
                    $font->size(20);
                    $font->color('#FFFFFF');
                    $font->align('left');
                    $font->valign('bottom');
                    $font->angle(0);
                });
                $img->save($pathfile);
                return $watermark;
            }
        }
        return null;
    }

    /**
     * @param string $filename
     * @return string
     */
    public function tempFile($filename)
    {
        return self::TEMP_DIR . '/' . $filename;
    }

    /**
     * @param string $filename
     * @return string
     */
    public function pathFile($filename)
    {
        return $this->PATH . '/' . $filename;
    }
}
