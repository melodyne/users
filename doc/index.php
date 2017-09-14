<!Doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>API - 云宿用户系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta content="width=device-width,minimum-scale=0.1,maximum-scale=8.0,user-scalable=yes" name="viewport" />
<link rel="stylesheet" href="style/api.css" type="text/css" media="screen" />
<link rel="stylesheet" href="style/jsonformat.css" type="text/css" media="screen" />
<script type="text/javascript" src="jsapi/moocore145.js"></script>
<script type="text/javascript" src="jsapi/json2.js"></script>
<script type="text/javascript" src="jsapi/userapi.js"></script>
<script type="text/javascript" src="jsapi/jstool.js"></script>
<script type="text/javascript" src="jsapi/jsonformat.js"></script>
<script type="text/javascript">
window.addEvent("resize",function(){
	bxsAPI.reSizeNavPanel();
});
window.addEvent("domready",function(){
	bxsAPI.execUpdates();
	bxsAPI.execServer();
	bxsAPI.exec();
	bxsAPI.navPanelExec();
});
</script>
</head>
<body>
<div class="wraper">
	<div id="page-tabs">
		<ul>
			<li class="selected">
				<div></div>
				<a href="index.php">云宿用户系统API接口<script type="text/javascript">bxsAPI.ver()</script></a>
			</li>
			<li>
				<div></div>
				<a href="dict.php">数据字典</a>
			</li>
			<li>
				<div></div>
				<a href="code.php">状态代码</a>
			</li>

			<li class="last">
				<div></div>
				<a href="plan.php">系统开发进度</a>
				<div class="last"></div>
			</li>
		</ul>
	</div>
</div>

<div id="apiboxwrap">
	<div id="apiboxwrapborder">
	
	</div>
</div>
<div id="example"></div>
</body>
</html>
