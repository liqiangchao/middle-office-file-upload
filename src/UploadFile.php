<?php

namespace Lqc\MiddleOfficeFileUpload;

use Lqc\MiddleOfficeFileUpload\Error\UploadError;
use Lqc\MiddleOfficeFileUpload\Exception\UploadException;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;

class UploadFile
{

    private $ext = ['jpg', 'jpeg', 'png'];

    private $et;

    /**
     * 图片连接的默认有效时间
     * @var int
     */
    private $time = 3600;

    private $maxSize = 3 * 1024 * 1024;
    /**
     * @var string
     */
    private $disk;
    private $driver = ['oss', 's3'];

    public function __construct($disk = '')
    {
        if (empty($disk)) {
            $disk = config('filesystems.default') ?? 'oss';
        }
        $this->disk = $disk;
        $this->checkDisk();
    }


    /**
     * @throws UploadException
     */
    protected function checkDisk(): void
    {
        if (empty($this->disk)) {
            throw new UploadException(UploadError::FILE_DISK);
        }

        $disks = config('filesystems.disks');
        if (!array_key_exists($this->disk, $disks) || !array_key_exists('driver', $disks[$this->disk]) || !in_array($disks[$this->disk]['driver'], $this->driver)) {
            throw new UploadException(UploadError::FILE_DISK_CONFIG_ERROR);
        }
    }


    /**
     * 检验文件
     * @param $file
     * @return void
     */
    protected function checkFile($file): void
    {
        if (empty($file)) {
            throw new UploadException(UploadError::FILE_EMPTY);
        }

        $fileSize = $file->getSize();
        if ($fileSize > $this->maxSize) {
            throw new UploadException(UploadError::FILE_MAX_SIZE);
        }

        $ext = $file->getClientOriginalExtension();
        if (!in_array($ext, $this->ext)) {
            throw new UploadException(UploadError::FILE_TYPE);
        }

        $this->et = $ext;
    }

    /**
     * 文件上传
     *
     * @param UploadedFile $file 文件
     * @param string $dirname 文件夹名称
     * @param string $rename 不带后缀
     * @param array $option
     * @return array
     */
    public function uploadFile(UploadedFile $file, string $dirname = 'upload', string $rename = '', $option = []): array
    {
        $this->checkFile($file);

        $dir = "$dirname/" . date('Y-m-d') . '/';
        if (!$rename) {
            $rename = time() . rand(10000, 90000) . '.' . $this->et;
        } else {
            $rename = $rename . '.' . $this->et;
        }
        $path = $dir . $rename;
        $bool = Storage::disk($this->disk)->put($path, file_get_contents($file));
        if ($bool) {
            return ['relative_path' => $path, 'url' => Storage::disk($this->disk)->temporaryUrl($path, $this->getTime($this->time), $option)];
        }
        throw new UploadException(UploadError::FILE_UPLOAD_ERROR);

    }

    /**
     * oss需要int  s3需要对象
     * @param int $time
     */
    private function getTime(int $time)
    {
        $disks = config('filesystems.disks');
        if (array_key_exists('driver', $disks[$this->disk]) && $disks[$this->disk]['driver'] == 's3') {
            $time = Date::now()->addSeconds($time);
        }
        return $time;
    }

    /**
     * 获得签名路径
     * @param string $path 相对路径
     * @param array $option 设置获取图片的宽高 等  参考看文档
     * @return array
     */
    public function getSignPath(string $path, array $option = [])
    {

        if (empty($path)) {
            throw new UploadException(UploadError::FILE_UPLOAD_PATH_ERROR);

        }
        $bool = Storage::disk($this->disk)->exists($path);
        if ($bool) {
            return ['relative_path'=>$path ,'url' => Storage::disk($this->disk)->temporaryUrl($path,$this->getTime($this->time),$option)];

        }
        throw new UploadException(UploadError::FILE_NOT_EXIST);
    }



    /**
     * 获取源操作对象
     * @return array|FilesystemAdapter|true[]
     */
    public function getAdapter()
    {
        return Storage::disk($this->disk);
    }


}