/**
 * 状态码
 */
var bxsAPI = {};
bxsAPI.returnCode=[];
bxsAPI.standard="API 规范";
bxsAPI.readme={
	code : "状态吗",
	mdg : "提示信息",
	data: "返回数据"
};


bxsAPI.returnCode.push({
	category : "公共",
	list: [
		{code:0,desc:"error"},
		{code:100,desc:"成功"},
		{code:101,desc:"此接口需要登录权限"},
		{code:102,desc:"token不合法"},
		{code:103,desc:"token已过期"},
		{code:110,desc:"第三方服务器返回错误信息"},
	]
});
bxsAPI.returnCode.push({
	category : "参数" ,
	list: [
		{code:200,desc:"参数缺失"},
		{code:201,desc:"参数类型不合法，必须为为数字"},
		{code:202,desc:"电话号码不合法"},
		{code:203,desc:"邮箱不合法"},

	]
});
bxsAPI.returnCode.push({
	category : "用户中心" ,
	list: [
		{code:300,desc:"该用户不存在，无法获取该用户信息"},
		{code:301,desc:"该用户已存在，无法注册"},
		{code:302,desc:"登录时密码错误"},

	]
});
bxsAPI.returnCode.push({
	category : "直播间" ,
	list: [
		{code:400,desc:"该直播间不存在"},
		{code:401,desc:"房间异常"},
		{code:402,desc:"该房间未授权"},

	]
});
