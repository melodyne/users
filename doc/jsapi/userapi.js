/**
 * API接口定义 服务器，接口方法，接口参数及说明，测试数据，
 */
var bxsAPI = {};
bxsAPI.local = location.host=="users.com"? 0 : 1;
bxsAPI.apis = [];
bxsAPI.server = bxsAPI.local?"http://users.icloudinn.com":"http://users.com";
bxsAPI.uid = bxsAPI.local?"22497":"22497";
bxsAPI.un = "13552997366";
bxsAPI.token = {
	field:"access-token",
	value:"NWFhZTE3ZWFhNjYwNTkyZTVmODg4Y2I2Y2FlMDdkYzYxNTAwMDE1OTMw"
}
bxsAPI.page={
	curPage:{field:"page",default:1},//当前页
	eachPageSize:{field:"per-page",default:20}//每页显示条数
};
bxsAPI.ziruo = "false";
bxsAPI.readme = "云宿用户系统，本系统采用ThinkPHP 5.0.0框架编写，现在已经升级到ThinkPHP 5.0.9。<br> " +
		"本系统实现了SSO单点登录，其他子系统，到该用户系统中进行身份授权认证。<br>" +
	    "支持邮箱、手机短信、QQ、微信公众号、微信小程序登录注册。<br>"+
		"接口授权认证，access-token 当作API URL请求参数发送，也可以放请求头里，access-token有效时长为两个小时。例如<redlight> https://example.com/users?access-token=xxxxxxxx。</redlight><br>"+
	    "为了用户系统的信息安全，并没有加跨域处理。密码采用Key+MD5加密。<br>"+
		"用户认证系统：<a target='_blank' href='http://users.icloudinn.com'>http://users.icloudinn.com</a>" ;

// API最新版本号，更新需要调整版本号，并更新下面的记录
bxsAPI.version = "v1.0";
bxsAPI.updated = [ {
	ver : "v1.0",
	dt : "2016-11-01",
	con : [ "全部接口", ]
}, {
	ver : "v2.0",
	dt : "待定.....",
	con : [ "<a href='#/product_consultalist'></a>实现了小程序授权登录</br>", ]
}, ];
/**
 * 接口模块定义 主要用于分类接口，看起来更清楚
 */

bxsAPI.apicates = [// api模块
	{
		cid : 1,
		name : "公共"
	},
	{
		cid : 2,
		name : "授权认证"
	},
	{
		cid : 3,
		name : "用户中心"
	},
	{
		cid : 4,
		name : "权限管理"
	},
	{
		cid : 5,
		name : "子系统"
	},
	{
		cid : 6,
		name : "统计分析"
	},
	{
		cid : 7,
		name : "账户管理"
	},
];
bxsAPI.apis.push({
	cid : 1,
	name : "验证码短信",
	url: "/Api/User/sendsms",
	method : "POST",
	params : [
		{field:"phone",phone:true,desc:"电话",sample:"13006022705"},
		{field:"action",isrequire:true,desc:"操作 【0：短信登录;1：手机注册；2：修改密码；3：绑定手机；4：更换绑定手机】",sample:"0"},
	],
	desc : "发送短信验证码！",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});
bxsAPI.apis.push({
	cid : 1,
	name : "短信通知",
	url: "/Api/User/smsNotify",
	method : "POST",
	params : [
		{field:"phone",phone:true,desc:"电话",sample:"13006022705"},
		{field:"msg",isrequire:true,desc:"通知内容",sample:"亲爱的民宿店家，你有位客人发起了退房申请，请在两小时内，检查房间用品是否有损坏，如果两小时后还没在后台确认，系统将自动全额退还客人押金！"},
	],
	desc : "发送短信通知！",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});
bxsAPI.apis.push({
	cid : 1,
	name : "微信模版信息",
	url: "/api/wechat/templateMsg",
	method : "POST",
	params : [
		{field:"appid",phone:true,desc:"微信APPID",sample:"2342343543"},
		{field:"touser",phone:true,desc:"接收者（用户）的 openid",sample:"2342343543"},
		{field:"template_id",phone:true,desc:"所需下发的模板消息的id",sample:"2342343543"},
		{field:"form_id",phone:true,desc:"表单提交场景下，为 submit 事件带上的 formId；支付场景下，为本次支付的 prepay_id",sample:"2342343543"},
		{field:"data",phone:true,desc:"模板内容，不填则下发空模板",sample:"2342343543"},
	],
	desc : "微信发送模版信息：详细请求参数，请查阅<a href='https://mp.weixin.qq.com/debug/wxadoc/dev/api/notice.html#发送模板消息'>官方文档</a>",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});
//------------------------------end-------------cid1----------------------------------------------
bxsAPI.apis.push({
	cid : 2,
	name : "单点身份授权认证（服务端用）",
	url: "/api/user/identityAuth",
	auth:"用户",
	method : "POST",
	params : [
		{field:"app_id",isrequire:true,desc:"子系统APPID",sample:"2050434965"},
		{field:"secret",isrequire:true,desc:"子系统secret",sample:"22Gj5zadU9qgJqcKVPbMnrEQcRsyKe"},
	],
	desc : "子系统请求该接口进行登录认证，如果认证已经登录，会返回用户信息，" +
	"此时子系统将用户信息保存到缓存，下一次接口请求检查子系统登录状态，直接获取缓存中的用户信息。APPID在这里申请：http://users.icloudinn.com/admin/apidoc/apply",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});

bxsAPI.apis.push({
	cid : 2,
	name : "账号注册",
	url: "/api/user/register",
	method : "POST",
	body:"<require>[form-data]</require>TEXT",
	params : [
		{field:"account",isrequire:true,desc:"账号/手机/邮箱",sample:"13006022705"},
		{field:"pwd",isrequire:true,desc:"密码",sample:"12345465"},
	],
	desc : "目前只支持邮箱账号注册！",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});
bxsAPI.apis.push({
	cid : 2,
	name : "账号密码登录",
	url: "/api/user/login",
	method : "POST",
	body:"<require>[form-data]</require>TEXT",
	params : [
		{field:"account",isrequire:true,desc:"账号/手机/邮箱",sample:"13006022705"},
		{field:"pwd",isrequire:true,desc:"密码",sample:"12345465"},
	],
	desc : "账号密码登录",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});

bxsAPI.apis.push({
	cid : 2,
	name : "手机短信验证码授权登录",
	url: "/Api/User/smsLogin",
	method : "POST",
	params : [
		{field:"phone",phone:true,desc:"手机号",sample:"13006022705"},
		{field:"sms_code",isrequire:true,desc:"短信验证码",sample:"8888"},
	],
	desc : "手机登录，不存电话时系统注册该手机号并登录",
	retuCode : {
		201 : "手机号未注册",
	}
});
bxsAPI.apis.push({
	cid : 2,
	name : "公众号、移动应用微信授权登录",
	url: "/Api/User/wxLogin",
	method : "POST",
	body:"<require>[form-data]</require>TEXT",
	params : [
		{field:"appid",isrequire:true,desc:"应用APPID",sample:"13006022705"},
		{field:"code",isrequire:true,desc:"微信授权code",sample:"12345465"},
	],
	desc : "公众号、移动应用的微信授权登录。",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});

bxsAPI.apis.push({
	cid : 2,
	name : "小程序微信授权登录",
	url: "/Api/User/wxAppLogin",
	method : "POST",
	params : [
		{field:"appid",isrequire:true,desc:"微信小程序APPID",sample:"wx251839cd58545ab3"},
		{field:"code",isrequire:true,desc:"小程序授权code",sample:"003tpSq50E6EEI1WVOr50REWq50tpSqZ"},
		{field:"encrypted_data",isrequire:true,desc:"用户敏感信息加密数据",sample:""},
		{field:"iv",isrequire:true,desc:"加密算法的初始向量",sample:"QTD2Qm9gE1+BWAfk/tepbQ=="},
	],
	desc : "微信小程序授权登录，相关账号，去小程序后台获取。",
	returns : {
		yunsu_id : "用户ID",
		access_token : "令牌",
		account : "用户账号",
	}
});

//------------------------------end-------------cid2----------------------------------------------
bxsAPI.apis.push({
	cid : 3,
	name : "用户个人信息",
	url: "/Api/User/12142513",
	method : "GET",
	desc : "通过云宿ID获取用户信息",
	returns:{
		point:"积分",
		yun_coin:"云币\\云宿币\\奔币",
	}
});

bxsAPI.apis.push({
	cid : 3,
	name : "个人信息完善修改",
	url: "/Api/User/update",
	method : "POST",
	auth:"用户",
	body:"<require>[form-data]</require>TEXT",
	params : [
		{field:"phone",isrequire:false,desc:"绑定手机",sample:"13006022705"},
		{field:"sex",isrequire:false,desc:"性别 0：未知（默认）；1：男 ；2：女",sample:"1"},
		{field:"head_img_url",isrequire:false,desc:"头像地址",sample:""},
		{field:"country",isrequire:false,desc:"国家",sample:"中国"},
		{field:"province",isrequire:false,desc:"省份",sample:"海南"},
		{field:"city",isrequire:false,desc:"城市",sample:"海口"},
	],
	desc : "该接口可以绑定手机号码。这里列出主要字段，详细字段请查阅数据字典。请勿操作敏感字段。",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});

bxsAPI.apis.push({
	cid : 3,
	name : "绑定手机",
	url: "/Api/User/bindingPhone",
	method : "POST",
	auth:"用户",
	body:"<require>[form-data]</require>TEXT",
	params : [
		{field:"phone",phone:true,desc:"手机号",sample:"13006022705"},
		{field:"sms_code",isrequire:true,desc:"短信验证码",sample:"8888"},
	],
	desc : "已登录、绑定手机号",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});

bxsAPI.apis.push({
	cid : 3,
	name : "关注好友\\取消关注",
	url: "/api/concern/12142507",
	method : "GET",
	auth:"用户",
	desc : "当对方没有关注时，执行该接口，就会关注对方，如果已经关注，执行该接口时，就取消关注！",
	retuCode:{
		131:"不能关注自己哦！"
	}
});
bxsAPI.apis.push({
	cid : 3,
	name : "我关注的\\关注我的\\相互关注的",
	url: "/api/concern",
	method : "GET",
	urlparams:[
		{field:"type",isrequire:false,desc:"0我关注的（默认）；1关注我的 2相互关注的",sample:"0"},
	],
	auth:"用户",
	desc : "关注好友列表！",
});
//------------------------------end-------------cid3用户中心--------------------------------------------
//------------------------------end-------------cid4----------------------------------------------
//------------------------------end-------------cid5----------------------------------------------
bxsAPI.apis.push({
	cid : 6,
	name : "用户列表",
	url: "/Api/User",
	method : "GET",
	desc : "全部用户列表",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});
//------------------------------end-------------cid6----------------------------------------------

bxsAPI.apis.push({
	cid : 7,
	name : "奖励获得\\消费扣除积分",
	url: "/Api/User/point",


	method : "POST",
	auth:"用户",
	body:"<require>[form-data]</require>TEXT",
	params : [
		{field:"num",isrequire:true,desc:"积分数量",sample:"10"},
		{field:"action",isrequire:true,desc:"add:增加积分；des:扣除积分",sample:"add"},
	],
	desc : "用于用户的积分消费扣除或奖励获得！",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});
bxsAPI.apis.push({
	anchor : "/Server/room/registRoomInfo",
	name : "提现记录列表",
	method : "/Server/room/registRoomInfo",
	cid : 7,
	params : {
		title : "<require>[必选]</require>房间标题",
		coverImgUrl : "<require>[必选]</require>封面图片",
		roomCode : "<require>[必选]</require>房间号码",
		chatRoomId : "<require>[必选]</require>聊天房间号",
		avRoomId : "<require>[必选]</require>视频房间号",
		userId : "<require>[必选]</require>主播id",
		fileName : "<require>[必选]</require>视频名字",
		address : "<require>[必选]</require>地址",
		themeId : "<require>[必选]</require>主题id",
	},
	desc : "登记房间信息",
	test : {"money":10,"type":1,"cash_account":"2324534534"},
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});

bxsAPI.apis.push({
	cid : 7,
	anchor : "/Server/room/registRoomInf",
	name : "商户提现",
	url: "/Server/room/registRoomInf",
	method : "POST",
	auth:"商户",
	body:"<require>[raw]</require>JSON(application/json)",
	urlparams:[
		{field:"title",isrequire:false,desc:"封面图片",sample:"你好了"},
		{field:"title",isrequire:false,desc:"封面图片",sample:"你好了"},
		{field:"title",isrequire:false,desc:"封面图片",sample:"你好了"},
	],
	params : [
		{field:"title",isrequire:false,desc:"封面图片",sample:"你好了"},
		{field:"title",isrequire:false,desc:"封面图片",sample:"你好了"},
	],
	desc : "登记房间信息",
	test : "&title=朵朵" +
	"&coverImgUrl=http://yycollege.yueyee.cc/file_upload/advert/201607/20160708/20160708141602500.jpg" +
	"&roomCode=1232323243&chatRoomId=1232323243" +
	"&avRoomId=1232323243" +
	"&userId=4028fc9b55f28dd10155f2933b6e0001" +
	"&fileName=2016_07_25_10_53_33_70916" +
	"&address=海南省美兰市" +
	"&themeId=1",
	returns : {
		code : "状态值",
		msg : "提示",
		data : "返回的数据 数据列表",
	}
});
//------------------------------end-------------cid7----------------------------------------------
//------------------------------end-------------cid8----------------------------------------------
//------------------------------end-------------cid9----------------------------------------------
//------------------------------end-------------cid10---------------------------------------------
