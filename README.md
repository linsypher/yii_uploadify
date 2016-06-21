## yii+uploadify
>
 	- 使用场景：yii中加入文件上传进度功能
	- 依赖库或者插件: 需要引入uploadify, uploadify依赖jQuery

### 1.版本说明:
- uploadify
- - 版本：3.2.1
- - [下载](http://www.uploadify.com/download/)
- - [document](http://www.uploadify.com/documentation/)
- yii
- - 版本：1.1.13
- - [下载](http://www.yiiframework.com/download/)
- jQuery
- - 版本 2.0+
- - [下载](http://jquery.com/download/)

### 2.下载并安装yii
```
$ framework/yiic webapp yii_uploadify #yii_uploadify为项目名称
```

### 3.引入uploadify
#### 3.1.uploadify源码中有用js、css、图片文件和swf文件放置到yii结构中
```
文件结构：
-yii_uploadify/
--uploadify/
---css/
   uploadify.css
---img/
   uploadify-cancel.png
   uploadify.swf
---js/
   jquery.uploadify.js
   jquery.uploadify.min.js
   jquery2.js
```

#### 3.2.增加新控制器uploadify
```
文件结构：
-protected/
--controllers/
  UploadifyController.php
```
- 把uploadify源码包中的 **check-exists.php** 和 **uploadify.php** 处理function，分别适配到yii的新控制**UploadifyController** 的**actionCheckExist** 和 **actionUploadify**中。

- 添加action：**actionView** ，上传按钮的展示页面。

#### 3.3.准备新视图文件
>
	这个视图文件中的js部分是uploadify插件的配置，可以查看uploadify的document进行个性化功能配置。

```
文件结构：
-protected/
--views/
---uploadify/
   index.php
```
- 把uploadify源码包中的 **index.php**，转移到这个新视图文件中，并修改js和css文件的路径

### 常见问题:
#### 上传文件重复提示后取消覆盖会出现js错误:
>
	jquery.uploadify.min.js 中的onUploadStart方法中出现this作用域变化的问题，可以尝试这样解决：
```
onUploadStart: function(d) {
            var e = this.settings;
            var that = this;    //fix flag
            var f = new Date();
            this.timer = f.getTime();
            this.bytesLoaded = 0;
            if (this.queueData.uploadQueue.length == 0) {
                this.queueData.uploadSize = d.size;
            }
            if (e.checkExisting) {
                c.ajax({
                    type: "POST",
                    async: false,
                    url: e.checkExisting,
                    data: {
                        filename: d.name
                    },
                    success: function(h) {
                        if (h == 1) {
                            var g = confirm('A file with the name "' + d.name + '" already exists on the server.\nWould you like to replace the existing file?');
                            if (!g) {    //fix flag， this->that，this作用域已经改变
                                that.cancelUpload(d.id);
                                c("#" + d.id).remove();
                                if (that.queueData.uploadQueue.length > 0 && that.queueData.queueLength > 0) {
                                    if (that.queueData.uploadQueue[0] == "*") {
                                        that.startUpload();
                                    } else {
                                        that.startUpload(that.queueData.uploadQueue.shift());
                                    }
                                }
                            }
                        }
                    }
                });
            }
            if (e.onUploadStart) {
                e.onUploadStart.call(this, d);
            }
        },
```

---
>
	欢迎交流：
	qq:		895620138
	email:	linsypher@126.com
