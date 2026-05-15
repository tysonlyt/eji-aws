# Unique Client Page 安装指南

## 系统要求

- WordPress 5.4 或更高版本
- PHP 7.2 或更高版本
- MySQL 5.6 或更高版本
- WooCommerce 4.0 或更高版本（必需）

## 安装方法

### 方法一：通过WordPress后台安装

1. 登录WordPress后台
2. 转到 插件 > 安装插件
3. 点击「上传插件」按钮
4. 选择下载的 `unique-client-page.zip` 文件
5. 点击「立即安装」
6. 安装完成后点击「启用插件」

### 方法二：通过FTP安装

1. 解压下载的 `unique-client-page.zip` 文件
2. 通过FTP客户端连接到您的网站
3. 导航到 `/wp-content/plugins/` 目录
4. 上传 `unique-client-page` 文件夹
5. 登录WordPress后台
6. 转到 插件 页面
7. 找到 "Unique Client Page" 并点击「启用」

## 使用方法

1. 在WordPress后台，进入 设置 > Unique Client Page 进行基本设置
2. 在页面或文章中使用短代码 `[unique_client_products]` 来显示产品列表
3. 可以使用以下参数来自定义显示：
   - `category` - 按类别筛选产品
   - `limit` - 每页显示的产品数量
   - `columns` - 网格列数
   - `orderby` - 排序字段
   - `order` - 排序方式 (ASC/DESC)

## 示例

显示特定类别的产品：
```
[unique_client_products category="shoes" limit="12" columns="4"]
```

## 更新

当有新版本发布时，您可以通过WordPress后台的更新提示进行更新，或者手动上传新版本覆盖旧文件。

## 获取帮助

如果您在使用过程中遇到任何问题，请访问我们的支持论坛或发送邮件至 support@everyideas.com
