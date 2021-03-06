# 中介语语料库协同标注平台

## 操作说明
+ 从[Github代码托管处](https://github.com/sunlidev/biaozhu/archive/master.zip)下载源码
+ 在根目录下仿照`.env.example`添加`.env`文件
+ 导入数据库(laravel5_2016-05-11.sql)
+ 进入根目录下运行`php artisan serve`命令
+ 登录`localhost:8000`

## 代码说明
![](https://raw.githubusercontent.com/sunlidev/biaozhu/master/biaozhu_model.png)
+ 网站的控制逻辑在`app/Http/Controllers`下面进行编写，下面的`Admin`,`Labeler`,`Super`对应网站的三个角色，`Auth`负责控制权限。
+ 网站的模型定义在`app`文件夹下，每一份php文件包含了数据库中的所有字段。
+ 另外在`app`下还有一个`helpers.php`文件，这份文件通过`composer.json`自动加载，为页面提供服务函数。
+ `public`文件夹可以被路径找到，里面包括了`css`，`flash`，`js`，`images`等静态资源，除此之外还包括管理员进行上传资源后所存放的语料资源，文件夹的名称为`uploads`。
+ 网站的视图层在`resources/views`下面进行编写，同样包括`admin`,`labeler`,`super`这三个文件夹，另外`partials`文件夹下面是模态框视图，`_layouts`文件夹下面是网站的布局，由于可以分为默认布局，管理员布局，超级管理员布局，页面可以嵌套相加。

## TODOLIST
### 管理员：
+ 上传图片资源以两张图片一起上传
+ 任务审核弹出模态框，框内是标注过的flash面板
+ 任务可删除
+ 任务可批量分配
+ 任务可合并
+ 任务可批量删除
+ 可以修改标注者信息
+ 可以删除标注者
+ 可以查看标注者的任务完成情况
+ 上传文本，应确保最后都转换成UTF-8的格式

### 标注者：
+ 工作界面以及浏览界面应该说明具体任务

### 超级管理员：
+ 删除管理员，相应标注者以及标注任务也应删除
+ 删除项目，相应管理员也应删除
