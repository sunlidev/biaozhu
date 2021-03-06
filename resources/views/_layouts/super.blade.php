@extends('_layouts.app')

@section('contentApp')
<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		<div class="navbar-default sidebar" role="navigation">
		    <div class="sidebar-nav navbar-collapse">
		      	<ul class="nav" id="side-menu">
		        	<li class="sidebar-search">
		            	<div class="input-group custom-search-form">
		                  	<input type="text" class="form-control" placeholder="Search...">
		                  	<span class="input-group-btn">
		                      	<button class="btn btn-default" type="button">
		                          	<i class="fa fa-search"></i>
		                      	</button>
		                  	</span>
		              	</div>
		          	</li>
		          	<li>
	              		<a href="/super/proj"><i class="fa fa-database fa-fw"></i> 项目管理</a>
		          	</li>
		          	<li>
	              		<a href="/super/admin"><i class="fa fa-graduation-cap fa-fw"></i> 管理员管理</a>
		          	</li>
		          	<li>
	              		<a href="/super/flash"><i class="fa fa-flash fa-fw"></i> 面板管理</a>
		          	</li>
		          	<li>
	              		<a href="#"><i class="fa fa-edit fa-fw"></i> 规范制定<span class="fa arrow"></span></a>
	              		<ul class="nav nav-second-level">
		                  	<li>
	                      		<a href="/super/norm/first"><i class="fa fa-th-large fa-fw"></i>一级规范</a>
		                  	</li>
		                  	<li>
	                      		<a href="/super/norm/second"><i class="fa fa-th-list fa-fw"></i>二级规范</a>
		                  	</li>
  		                  	<li>
  	                      		<a href="/super/norm/third"><i class="fa fa-th fa-fw"></i>具体名称</a>
  		                  	</li>
		              	</ul>
		          	</li>
		          	<li>
	            	  	<a href="#"><i class="fa fa-sitemap fa-fw"></i> 资源管理<span class="fa arrow"></span></a>
		              	<ul class="nav nav-second-level">
		                  	<li>
		                  		<a href="/super/resource"><i class="fa fa-eye fa-fw"></i>查看</a>
		                  	</li>
		                  	<li>
                      			<a href="/super/resource/batch"><i class="fa fa-cloud-upload fa-fw"></i>批量上传</a>
		                  	</li>
		              	</ul>	
		          	</li>
		          	<li>
		              	<a href="#"><i class="fa fa-table fa-fw"></i> 任务管理<span class="fa arrow"></span></a>
		              	<ul class="nav nav-second-level">
		                  	<li>
                      			<a href="/super/assign/1"><i class="fa fa-file-text fa-fw"></i>文本数据表</a>
		                  	</li>
		                  	<li>
	                      		<a href="/super/assign/2"><i class="fa fa-file-image-o fa-fw"></i>图片数据表</a>
		                  	</li>
		              	</ul>
		          	</li>
		          	<!--li>
		              	<a href="#"><i class="fa fa-pie-chart-o fa-fw"></i> 可视化统计<span class="fa arrow"></span></a>
		              	<ul class="nav nav-second-level">
		                  	<li>
                      			<a href="flot.html">资源</a>
		                  	</li>
		                  	<li>
	                      		<a href="morris.html">任务</a>
		                  	</li>
		                  	<li>
	                      		<a href="morris.html">人员</a>
		                  	</li>
		              	</ul>
		          	</li-->
		      	</ul>
		    </div>
		</div>
	</nav>

	@yield('contentSuper')

</div>
@endsection