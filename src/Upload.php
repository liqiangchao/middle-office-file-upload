<?php

namespace Lqc\MiddleOfficeFileUpload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Upload
{
    /**
     * 限定上传格式
     *
     * @var array|string[]
     */
    public array $ext = ['jpg', 'jpeg', 'png'];

    protected string $et;

    /**
     * 限定驱动类型
     *
     * @var array|string[]
     */
    protected array $disks = ['oss', 's3'];

    protected string $disk;
    protected function check($file, $disk): array
    {
        if (empty($file)) {
            return ['success' => false, 'msg' => '上传文件不能为空'];
        }
        // 验证文件大小是否符合限制
        $maxSize = 3 * 1024 * 1024;
        $fileSize = $file->getSize();
        if ($fileSize > $maxSize) {
            return ['success' => false, 'msg' => '上传文件大小超过限制'];
        }
        $ext = $file->getClientOriginalExtension();
        if (! in_array($ext, $this->ext)) {
            return ['success' => false, 'msg' => '不支持的上传格式'];
        }
        if (! in_array(strtolower($disk), $this->disks)) {
            return ['success' => false, 'msg' => '不支持的上传驱动'];
        }

        $this->disk = $disk;
        $this->et = $ext;

        return ['success' => true];
    }


    /**
     * 文件上传
     *
     * @param UploadedFile $file 文件
     * @param string $disk 驱动
     * @param string $dirname 文件夹名称
     * @param string $rename 不带后缀
     *
     * @return array
     */
    public function uploadImg(UploadedFile $file, string $disk = '', string $dirname = 'bmo_mr_api', string $rename = ''): array
    {

        if (empty($disk)) {
            $disk = config('filesystems.default') ?? 'oss';
        }
        $data = $this->check($file, $disk);
        if ($data['success']) {
            $dir = "$dirname/".date('Y-m-d').'/';
            if (! $rename) {
                $rename = time().rand(10000, 90000).'.'.$this->et;
            } else {
                $rename = $rename.'.'.$this->et;
            }
            $path = $dir.$rename;

            $bool = Storage::disk($this->disk)->put($path, file_get_contents($file));
            if ($bool) {

                return ['success' => true, 'url' => Storage::disk($this->disk)->temporaryUrl($path,now()->addHour(),['x-oss-process' => 'image/circle,r_100'])];
            }
            return ['success' => false, 'message' => '上传失败'];
        }

        return $data;
    }

}