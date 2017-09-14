/**
 *
 */
var bxsAPI = {};
bxsAPI.progress=[];
bxsAPI.standard="云商直播--商户端、微信观众端、管理后台";
bxsAPI.group=[
    {groupName:"产品设计",detail:"汪君相、梁绣"},
    {groupName:"UI设计",detail:"李瑞龙"},
    {groupName:"服务端开发",detail:"陈万洲"},
    {groupName:"前端开发",detail:"汪君相、郑皓天"},
]
bxsAPI.mainFunctions=[
	"直播",
	"商品购买",
	"视频库",
]

bxsAPI.progress.push({
	week : "一",
	date:"3.6-3.10",
	model: [
		{
			modelName:"直播数据分析",
			functions:[
				"直播间观众",
				"直播概况",
				"直播实时在线人数分析",
				"观众地域分析",
			]
		}
	]
});

bxsAPI.progress.push({
	week : "二",
	date:"3.13-3.17",
	model: [
		{
			modelName:"直播计费",
			functions:[
				"添加优惠套餐",
				"套餐购买",
				"商户账户升级",
				"费用自动扣除",
			]
		}
	]
});

bxsAPI.progress.push({
	week : "三",
	date:"3.20-3.24",
	model: [
		{
			modelName:"视频库",
			functions:[
				"视频上传、管理",
				"视频转码",
				"把点播设为直播",
			]
		},

	]
});

bxsAPI.progress.push({
	week : "四",
	date:"3.27-4.1",
	model: [
		{
			modelName:"视频库",
			functions:[
				"权限开关",
				"循环播放"
			]
		},
		{
			modelName:"首页",
			functions:[
				"商户直播概览"
			]
		},
		{
			modelName:"授权观看",
			functions:[
				"限制人数",
				"密码观看",
				"支付观看",
				"直播收益",
			]
		},

	]
});

bxsAPI.progress.push({
	week : "五",
	date:"4.5-4.7",
	model: [
		{
			modelName:"分享",
			functions:[
				"直播间分享",
			]
		},
		{
			modelName:"优化",
			functions:[
				"各种优化",
			]
		},

	]
});

bxsAPI.progress.push({
	week : "六",
	date:"4.10-4.14",
	model: [
		{
			modelName:"优化",
			functions:[
				"继续优化",
			]
		},

	]
});

bxsAPI.progress.push({
	week : "七",
	date:"4.17-4.21",
	model: [
		{
			modelName:"优化",
			functions:[
				"继续优化",
			]
		},

	]
});

bxsAPI.progress.push({
	week : "八",
	date:"4.12-4.28",
	model: [
		{
			modelName:"优化",
			functions:[
				"继续优化",
			]
		},
		{
			modelName:"项目迁移",
			functions:[
				"服务器迁移",
			]
		},

	]
});

bxsAPI.progress.push({
	week : "九",
	date:"4.2-4.5",
	model: [
		{
			modelName:"优化",
			functions:[
				"继续优化",
			]
		},
	]
});
bxsAPI.progress.push({
	week : "...",
	date:"7.21-7.21",
	model: [
		{
			modelName:"微信小程序登录",
			functions:[
				"微信登录，用微信unionId作为微信用户唯一标识",
				"废弃了微信openId的维护"
			]
		},
	]
});

bxsAPI.progress.push({
	week : "...",
	date:"8.2",
	model: [
		{
			modelName:"登录注册",
			functions:[
				"新增了移动应用微信授权登录",
				"第三方应用appid后台管理",
			]
		},
	]
});