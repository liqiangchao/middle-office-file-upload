<?php

namespace Lqc\MiddleOfficeFileUpload;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class Upload
{
    private $ext = ['jpg', 'jpeg', 'png'];

    /**
     * 文件后缀
     * @var string
     */
    private  $et;

    /**
     * 图片连接的默认有效时间
     * @var int
     */
    private  $time = 3600;

    /**
     * @var string
     */

    private  $disk;

    /**
     * 源操作对象
     * @var FilesystemAdapter
     */
    protected  $Adapter;




    /**
     * 校验 文件
     * @param $file
     * @return array
     */
    protected function checkFile($file): array
    {
        if (empty($file)) {
            return ['success' => false, 'message' => '上传文件不能为空'];
        }
        // 验证文件大小是否符合限制
        $maxSize = 3 * 1024 * 1024;
        $fileSize = $file->getSize();
        if ($fileSize > $maxSize) {
            return ['success' => false, 'message' => '上传文件大小超过限制'];
        }
        $ext = $file->getClientOriginalExtension();
        if (! in_array($ext, $this->ext)) {
            return ['success' => false, 'message' => '不支持的上传格式'];
        }

        $this->et = $ext;
        return ['success' => true];
    }

    /**
     * 校验disk
     * @param $disk
     * @return array|true[]
     */
    private function checkDisk($disk = ''){
        if (empty($disk)) {
            $disk = config('filesystems.default');
        }
        if(empty($disk)){
            return ['success' => false, 'message' => '上传驱动不能为空'];
        }

        $disks = config('filesystems.disks');
        if(!array_key_exists($disk,$disks) || !array_key_exists('driver',$disks[$disk])){
            return ['success' => false, 'message' => '驱动需要在app中配置'];
        }
        $this->disk = $disk;
        $this->Adapter = Storage::disk($this->disk);
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
    public function uploadImg(UploadedFile $file, string $disk = '', string $dirname = 'bmo_mr_api', string $rename = '',$option = []): array
    {
        $resFile = $this->checkFile($file);
        if(!$resFile['success']){
            return $resFile;
        }
        $resDisk = $this->checkDisk($disk);
        if(!$resDisk['success']){
            return $resDisk;
        }

        $dir = "$dirname/".date('Y-m-d').'/';
        if (! $rename) {
            $rename = time().rand(10000, 90000).'.'.$this->et;
        } else {
            $rename = $rename.'.'.$this->et;
        }
        $path = $dir.$rename;
        $bool = $this->Adapter->put($path, file_get_contents($file));
        if ($bool) {
            return ['success' => true, 'relative_path'=>$path ,'url' => $this->Adapter->temporaryUrl($path,$this->getTime($this->time),$option)];
        }
        return ['success' => false, 'message' => '上传失败'];

    }


    /**
     * 获得签名路径
     * @param string $path 相对路径
     * @param int $time 秒
     * @param array $option 设置获取图片的宽高 等  参考看文档
     * @return array
     */
    public function getSignPath(string $path,$disk = '',int $time = 3600 ,array $option = []){

        if(empty($path)){
            return ['success' => false, 'message' => '路径不能为空'];
        }

        $res = $this->checkDisk($disk);
        if(!$res['success']){
            return $res;
        }
        $bool = Storage::disk($this->disk)->exists($path);
        if($bool){
            return ['success' => true, 'url' => $this->Adapter->temporaryUrl($path,$this->getTime($time),$option)];
        }
        return ['success' => false, 'message' => '文件不存在'];
    }


    /**
     * oss需要int  s3需要对象
     * @param int $time
     * @return \Illuminate\Support\Carbon|int
     */
    private function getTime(int $time){
//        $disks = config('filesystems.disks');
//        if(array_key_exists('driver',$disks[$this->disk]) &&  $disks[$this->disk]['driver'] == 's3'){
//            $time = Carbon::now()->addSeconds($time);
//        }
        $time = Date::now()->addSeconds($time);
        return $time;
    }


    /**
     * 获取源操作对象
     * @param $disk
     * @return array|FilesystemAdapter|true[]
     */
    public function getAdapter($disk = ''){
        $res = $this->checkDisk($disk);
        if(!$res['success']){
            return $res;
        }
        return $this->Adapter;
    }

}