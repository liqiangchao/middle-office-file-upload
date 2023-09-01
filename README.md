# middle-office-file-upload
oss/s3上传

## 环境 v3.2
- PHP8.1+
- Laravel9.0+

#### v2.1
- PHP7.2+
- Laravel6+

#### env 配置

FILESYSTEM_DISK=oss  

OSS_ACCESS_KEY=
OSS_SECRET_KEY=
OSS_ENDPOINT=
OSS_BUCKET=

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false

#### filesystems.php 配置

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => true,
        ],
        'oss' => [
            'driver' => 'oss',
            'root' => '',
            'access_key' => env('OSS_ACCESS_KEY'),
            'secret_key' => env('OSS_SECRET_KEY'),
            'endpoint' => env('OSS_ENDPOINT'),
            'bucket' => env('OSS_BUCKET'),
            'isCName'    => env('OSS_IS_CNAME', false)
        ],


#### 使用
图片上传
--$res = (new Upload())->uploadImg($request->file('file'));
--响应
--array:3 [
--  "success" => true
--  "relative_path" => "bmo_mr_api/2023-09-01/169356700974888.jpg"
--  "url" => "https://bmo-notice-api.cq4oss.ctyunxs.cn/bmo_mr_api/2023-09-01/169356700974888.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=FXO9mdZow6tPtrSAEQpZ%2F20230901%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20230901T111650Z&X-Amz-SignedHeaders=host&X-Amz-Expires=3600&X-Amz-Signature=98f0001affb9b05e574270baa6653acd08947a24756ffbadd00678f2d587cb36"
--]

$res = (new Upload())->getSignPath($relative_path)
array:2 [
  "success" => true
  "url" => "https://bmo-notice-api.cq4oss.ctyunxs.cn/bmo_mr_api/2023-09-01/169356700974888.jpg?X-Amz-Content-Sha256=UNSIGNED-PAYLOAD&X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=FXO9mdZow6tPtrSAEQpZ%2F20230901%2Fap-southeast-1%2Fs3%2Faws4_request&X-Amz-Date=20230901T111650Z&X-Amz-SignedHeaders=host&X-Amz-Expires=3600&X-Amz-Signature=98f0001affb9b05e574270baa6653acd08947a24756ffbadd00678f2d587cb36"
]

具体参数看文档
