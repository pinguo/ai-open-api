# 1. Open API签名算法
## 1.1. SDK
* [Golang](https://github.com/pinguo/open-api-sdk-go)
```
go get github.com/pinguo/open-api-sdk-go
```
* [PHP](https://github.com/pinguo/ai-open-api/tree/main/php-sdk)

## 1.2. 算法说明
* [算法说明](https://github.com/pinguo/open-api-sdk-go/blob/main/README.md)


# 2. 接口文档

## 概述

**接口地址**： https://ai-open-api.pinguo.com

## 认证方式

所有接口均需要签名认证，使用 Open API 签名机制。

### 公共请求头

| Header | 类型 | 必填 | 说明 |
|--------|------|------|------|
| PG-AccessKey | string | 是 | 应用访问密钥 |
| PG-Timestamp | string | 是 | 请求时间戳（Unix 秒） |
| PG-Sign | string | 是 | 请求签名 |
| Content-Type | string | 是 | application/json |

## 2.1. 创建任务

### 接口信息

- **路径**: `POST /v1/task/create/{modelID}/{path}`
- **说明**: 创建异步任务

### 路径参数

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| modelID | string | 是 | 模型 ID，例如：pg-fast-restorer |
| path | string | 是 | 模型服务路径，例如：v1/enhance |

### 请求体

请求体内容根据具体模型服务要求而定，以 JSON 格式传递。

**示例（快速超分）**:
```json
{
    "image_url": "http://example.com/image.jpg",
    "output_url": "http://example.com/image.jpg",
    "output_method": "PUT",
    "scale": 2,
    "denoise": 0.4,
    "sharpen": 0.5,
    "compression": 0.3,
    "overlap_ratio": 0.1,
    "model_type": "hifi"
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
| 400 | 参数错误（modelID 或 path 缺失） |
| 432 | 签名验证失败 |
| 500 | 模型请求失败 |

### 请求示例

```bash
curl -X POST 'http://localhost:8000/v1/task/create/pg-fast-restorer/v1/enhance' \
  -H 'Content-Type: application/json' \
  -H 'PG-AccessKey: OYHLHUoiQd3jTz7+eP1EUmcm' \
  -H 'PG-Timestamp: 1712345678' \
  -H 'PG-Sign: abc123...' \
  -d '{
    "image_url": "http://example.com/image.jpg",
    "output_url": "http://example.com/image.jpg",
    "output_method": "PUT",
    "scale": 2,
    "denoise": 0.4,
    "sharpen": 0.5,
    "compression": 0.3,
    "overlap_ratio": 0.1,
    "model_type": "hifi"
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
| modelID | string | 是 | 模型 ID，例如：pg-aisdk-go |
| taskID | string | 是 | 任务 ID（由创建接口返回） |

### 响应

**成功响应 (200)**:
```json
{
    "taskID": "69d61e5ea79d195c1c3962c2",
    "status": "done",
    "data": "模型本身的响应数据"
}
```

响应内容根据具体模型服务而定。

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
| 400 | 参数错误（modelID 或 taskID 缺失） |
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
## 3.1. 快速超分
| modelID | 类型 | 描述 | 值 |
|--------|------|------|------|
| modelID | string | 是 | pg-fast-restorer |
| path | string | 是 | /v1/enhance |

### QueryString
无

### 请求body:
```json
{
    "image_url": "http://example.com/image.jpg",
    "output_url": "http://example.com/image.jpg",
    "output_method": "PUT",
    "scale": 2,
    "denoise": 0.4,
    "sharpen": 0.5,
    "compression": 0.3,
    "overlap_ratio": 0.1,
    "model_type": "hifi"
}
```

### 模型响应body:
```json
{
    "status": "success",
    "output_url": "http://example.com/image.jpg"
}
```