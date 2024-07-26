<?php

namespace App\Http\Admin\Swagger;

use Hyperf\Swagger\Annotation as OA;

#[OA\OpenApi(
    openapi: '3.0.0',
    info: new OA\Info(
        version: '3.0.0',
        description: 'MineAdmin 是一款基于 Hyperf 开发的开源管理系统，提供了用户管理、权限管理、系统设置、系统监控等功能。',
        title: 'MineAdmin',
        termsOfService: 'https://www.mineadmin.com',
        contact: new OA\Contact(name: 'MineAdmin',url: 'https://www.mineadmin.com/about'),
        license: new OA\License(name: 'Apache2.0',url: 'https://github.com/mineadmin/MineAdmin/blob/master/LICENSE')
    ),
    servers: [
        new OA\Server(
            url: 'http://127.0.0.1:9501',
            description: '本地服务'
        ),
        new OA\Server(
            url: 'https://demo.mineadmin.com',
            description: '演示服务',
        )
    ],
    externalDocs: new OA\ExternalDocumentation(description: '开发文档', url: 'https://v3.doc.mineadmin.com')
)]
#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
#[OA\SecurityScheme(
    securityScheme: 'token',
    type: 'apiKey',
    in: 'header'
)]
class Server
{

}