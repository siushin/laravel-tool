# laravel-tool - Laravel 常用工具集

基于 Laravel 的枚举、助手函数、Trait特征、工具类、模型转换等常用工具类及助手函数。

## 开始使用

```shell
composer require siushin/laravel-tool
```

## 开启扩展

- mbstring

## 目录结构

```text
src  源代码目录
├─Cases                模型转换目录
│  └─Json.php                         事件定义文件
├─Enums                枚举目录
│  ├─GenderTypeEnum.php               性别
│  ├─LogActionEnum.php                操作类型（日志）
│  ├─UploadFileTypeEnum.php           上传文件类型
│  ├─RequestSourceEnum.php            请求来源
│  └─SocialTypeEnum.php               社交类型
├─Funcs                助手函数目录
│  ├─LaraDateTime.php                 日期时间（基于Laravel）
│  ├─LaraRequest.php                  Request请求（基于Laravel）
│  └─LaraResponse.php                 Response响应（基于Laravel）
├─Traits               Trait特征目录
│  ├─ExcelReader.php                  Excel读取
│  ├─ExcelTool.php                    Excel工具
│  ├─ExcelWriter.php                  Excel写入
│  ├─LaraParamTool.php                参数（基于Laravel）
│  └─ModelTool.php                    模型常用方法
├─Utils                工具类目录
│  └─Tree.php                         生成Tree树结构
├─Installer.php        composer命令钩子
├─ServiceProvider.php  服务提供者
```

## 🧑🏻‍💻 关于作者

多年开发经验，具有丰富的前、后端软件开发经验~

微信：siushin

Github：<https://github.com/siushin>

个人博客：<http://www.siushin.com>

邮箱：<a href="mailto:siushin@163.com">siushin@163.com</a>

## 💡 反馈交流

在使用过程中有任何想法、合作交流，请加我微信 `siushin` （备注 <mark>github</mark> ）：

<img src="https://www.siushin.com/src/%E5%BE%AE%E4%BF%A1%E4%BA%8C%E7%BB%B4%E7%A0%81.jpg" alt="添加我微信备注「github」" style="width: 180px;" />

## ☕️ 打赏赞助

如果你觉得知识对您有帮助，可以请作者喝一杯咖啡 ☕️

<div class="coffee" style="display: flex;align-items: center;margin-top: 20px;">
<img src="https://www.siushin.com/src/%E5%BE%AE%E4%BF%A1%E6%94%B6%E6%AC%BE%E7%A0%81.jpg" alt="微信收款码" style="width: 180px;margin-right: 80px;" />
<img src="https://www.siushin.com/src/%E6%94%AF%E4%BB%98%E5%AE%9D%E6%94%B6%E6%AC%BE%E7%A0%81.jpg" alt="支付宝收款码" style="width: 180px;" />
</div>
