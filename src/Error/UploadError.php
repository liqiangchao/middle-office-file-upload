<?php

namespace Lqc\MiddleOfficeFileUpload\Error;

class UploadError
{

    /**
     * 图片不能为空
     */
    public const FILE_EMPTY = '文件不能为空';

    /**
     * 超过上传大小限制
     */
    public const FILE_MAX_SIZE = '上传文件大小超过限制';

    /**
     * 上传文件格式限制
     */
    public const FILE_TYPE = '不支持的上传格式';

    /**
     * 上传驱动不能为空
     */
    public const FILE_DISK = '上传驱动不能为空';

    /**
     * 配置错误
     */
    public const FILE_DISK_CONFIG_ERROR = '配置错误';
    public const FILE_UPLOAD_ERROR = '上传失败';

}