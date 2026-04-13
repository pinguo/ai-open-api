# 1. Open API签名算法
## 1.1. SDK
* [Golang](https://github.com/pinguo/open-api-sdk-go/tree/v2)
```
go get github.com/pinguo/open-api-sdk-go/v2
```

## 1.2. 算法说明
* [算法说明](https://github.com/pinguo/open-api-sdk-go/blob/v2/README.md)


# 2. 接口文档

## 概述

**接口地址**： https://ai-open-api.pinguo.cn

## 认证方式

所有接口均需要签名认证，使用 Open API 签名机制。

### 公共请求头

| Header | 类型 | 必填 | 说明 |
|--------|------|------|------|
| PG-AccessKey | string | 是 | 应用访问密钥 |
| PG-Timestamp | string | 是 | 请求时间戳（Unix 秒） |
| PG-Sign | string | 是 | 请求签名 |
| Content-Type | string | 是 | application/json |
| PG-Callback | string | 否 | 回调 URL，用于接收任务完成通知 |

## 2.1. 创建任务

### 接口信息

- **路径**: `POST /v1/task/create/{modelID}`
- **说明**: 创建异步任务

### 路径参数

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| modelID | string | 是 | 模型 ID，例如：pg-fast-restorer |

### 请求体

请求体内容根据具体模型服务要求而定，以 JSON 格式传递。
请求/响应参数请参考[模型列表](#model-list)中的参数详细说明。

**示例（快速超分）**:
```json
{
    "image_url": "http://example.com/image.jpg",
    "output_url": "http://example.com/image.jpg",
    "output_method": "PUT",
    "scale": 2
}
```

### 响应

**成功响应 (200)**:
```json
{
    "taskID": "69d5c494a79d195c1c39407f"
}
```

**错误响应**:
```json
{
    "code": 500,
    "message": "模型请求报错！请联系管理员"
}
```

### 错误码

| 错误码 | 说明 |
|--------|------|
| 400 | 参数错误（modelID 无效） |
| 432 | 签名验证失败 |
| 500 | 模型请求失败 |

### 请求示例

```bash
curl -X POST 'http://localhost:8000/v1/task/create/pg-fast-restorer' \
  -H 'Content-Type: application/json' \
  -H 'PG-AccessKey: OYHLHUoiQd3jTz7+eP1EUmcm' \
  -H 'PG-Timestamp: 1712345678' \
  -H 'PG-Sign: abc123...' \
  -d '{
    "image_url": "http://example.com/image.jpg",
    "output_url": "http://example.com/image.jpg",
    "output_method": "PUT",
    "scale": 2
}'
```

---

## 2.2. 查询任务详情

### 接口信息

- **路径**: `GET /v1/task/detail/{modelID}/{taskID}`
- **说明**: 查询异步任务的执行状态和结果

### 路径参数

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| modelID | string | 是 | 模型 ID，例如：pg-fast-restorer |
| taskID | string | 是 | 任务 ID（由创建任务接口返回） |

### 响应

**成功响应 (200)**:
```json
{
    "taskID": "69d61e5ea79d195c1c3962c2",
    "status": "done",
    "data": "模型本身响应的JSON字符串"
}
```
### 响应参数
| 参数 | 类型 | 说明 |
|------|------|------|
| taskID | string | 任务 ID（由创建任务接口返回） |
| status | string | 任务状态， "done" 表示已完成(init: 初始化中,running: 运行中,done: 已完成,cancel: 已取消) |
| data | string | 模型本身响应的JSON字符串 |


**错误响应**:
```json
{
    "code": 500,
    "message": "模型请求报错！请联系管理员"
}
```

### 错误码

| 错误码 | 说明 |
|--------|------|
| 400 | 参数错误（modelID 或 taskID 无效） |
| 432 | 签名验证失败 |
| 500 | 模型请求失败 |

### 请求示例

```bash
curl -X GET 'http://localhost:8000/v1/task/detail/pg-fast-restorer/69d5c494a79d195c1c39407f' \
  -H 'PG-AccessKey: OYHLHUoiQd3jTz7+eP1EUmcm' \
  -H 'PG-Timestamp: 1712345678' \
  -H 'PG-Sign: abc123...'
```
---


# 3. 模型列表
<a id="model-list"></a>
## 3.1. 快速超分
| modelID | 类型 | 描述 | 值 |
|--------|------|------|------|
| modelID | string | 是 | pg-fast-restorer |

### QueryString
无

### 请求body:
```json
{
    "image_url": "http://example.com/image.jpg",
    "output_url": "http://example.com/image.jpg",
    "output_method": "PUT",
    "scale": 2
}
```
### 请求参数
| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| image_url | string | 是 | 输入图片 URL |
| output_url | string | 是 | 结果图上传的预签名URL(云存储商的预签名URL) |
| output_method | string | 是 | 上传方法: PUT 或 POST |
| scale | int | 是 | 缩放比例，可选值：2、4 |



### 模型成功响应body:
```json
{
    "status": "success",
    "output_url": "http://example.com/image.jpg"
}
```
### 成功响应参数
| 参数 | 类型 | 说明 |
|------|------|------|
| status | string | 任务状态， "success" 表示已完成 |
| output_url | string | 请求参数中的output_url |

### 模型错误消息body:
```json
{
  "detail": "Error message"
}
```

### 错误响应参数
| 参数 | 类型 | 说明 |
|------|------|------|
| detail | string | 错误信息 |



## 3.2. 图灵云端修图

* 支持套用预设如追色等功能


| modelID | 类型 | 描述 | 值 |
|--------|------|------|------|
| modelID | string | 是 | turing-cloud-retouch |


### QueryString
无

### 请求body:
```json
{
  "images": [
    {
      "id": "69b370faf85ce4ebaec93106",
      "url": "https://ali-public-qa.oss-cn-hangzhou.aliyuncs.com/FtE6ZJ5RP8vUF_ucXgbYrdoliWw-"
    }
  ],
  "preset": {
    "share_code": "turing9ert88",
    "store_owner_mobile": "19900000000"
  },
  "id": "69b39a3e09239d3856a20718"
}
```

### 请求参数
| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| images | array | 是 | 图片列表，每个元素包含图片 ID 和 URL |
| images.id | string | 是 | 图片 ID |
| images.url | string | 是 | 图片 URL |
| preset | object | 是 | 预设参数，包含分享码和店铺手机号 |
| preset.share_code | string | 是 | 分享码 |
| preset.store_owner_mobile | string | 是 | 店铺手机号 |
| id | string | 是 | 图灵云端修图任务 ID |



### 模型响应body:
```json
{
    "code": 200,
    "message": "ok",
    "images": [
        {
            "id": "69b370faf85ce4ebaec93106",
            "status": "succeed",
            "message": "上传成功",
            "resources": {
                "retouch": {
                    "etag": "6ED27A011E7BA1271CDD1A4032409BF9",
                    "size": 4112425,
                    "isPrivate": false,
                    "mimeType": "image/jpeg",
                    "uri": "aliyun://icc-ai-model-public/turing-retouch/69b39a3e09239d3856a20718/6ed27a011e7ba1271cdd1a4032409bf9.jpg",
                    "url": "http://icc-ai-model-public.camera360.com/turing-retouch/69b39a3e09239d3856a20718/6ed27a011e7ba1271cdd1a4032409bf9.jpg",
                    "width": 1333,
                    "height": 2000
                }
            }
        }
    ]
}
```

### 成功响应参数
| 参数 | 类型 | 说明 |
|------|------|------|
| code | int | 200 表示成功 |
| message | string | "ok" 表示成功 |
| images | array | 图片列表，每个元素包含图片 ID、状态、消息和资源 |
| images.id | string | 图片 ID |
| images.status | string | 图片状态， "succeed" 表示成功 |
| images.message | string | 图片状态消息 |
| images.resources | object | 图片资源信息，包含 retouch 资源 |
| images.resources.retouch | object | 图片资源信息，包含 retouch 资源 |
| images.resources.retouch.etag | string | 图片资源的 ETag |
| images.resources.retouch.size | int | 图片资源的大小 |
| images.resources.retouch.isPrivate | boolean | 是否为私有资源 |
| images.resources.retouch.mimeType | string | 图片资源的 MIME 类型 |
| images.resources.retouch.uri | string | 图片资源的 URI |
| images.resources.retouch.url | string | 图片资源的 URL |
| images.resources.retouch.width | int | 图片资源的宽度 |
| images.resources.retouch.height | int | 图片资源的高度 |
