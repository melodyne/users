bxsAPI.navPanelExec = function(){
	var navpanelbox = $("navPanel");
	if(!navpanelbox){
		navpanelbox = new Element("div",{"class":"navpanelbox","id":"navPanel"}).inject(document.body);
		var navh5 = new Element("h5").inject(navpanelbox);
		var navsha = new Element("span").inject(navh5).set("html","隐藏");
		navsha.onclick = function(){
			if(!bxsAPI.navShow){
				$("apicatebox").style.display = "none";
				this.set("html","显示");
				bxsAPI.navShow = 1;
			}else{
				$("apicatebox").style.display = "block";
				this.set("html"," 隐藏");
				bxsAPI.navShow = 0;
			}
			bxsAPI.reSizeNavPanel();
		}
		var apicatebox = new Element("div",{"id":"apicatebox","class":"apicatebox"}).inject(navpanelbox);
		for(var c=0;c<bxsAPI.apicates.length;c++){
			var cate = bxsAPI.apicates[c];
			var apicate = new Element("div",{"class":"apicate"}).inject(apicatebox).set("html",bxsAPI.listno(c+1)+cate.name);
			
			var apisData = bxsAPI.apis.filter(function(a){return a.cid===cate.cid});
			var navul = new Element("ul").inject(apicatebox);
			for(var i=0;i<apisData.length;i++){
				var mobj = apisData[i];
				var li = new Element("li").inject(navul).set("html",bxsAPI.listno(c+1,i+1)+mobj.name);
				li.idno = mobj.idno;
				li.onclick = function(){
					location = "#"+this.idno;
				}
			}
		}
		bxsAPI.reSizeNavPanel(navpanelbox);
		navpanelbox.makeDraggable({
			container:document.body,
//			droppables:dropables,
			onEnter:function(el,dr){
			},
			onDrop:function(el,dr){
			}
		});
	}
}
bxsAPI.reSizeNavPanel = function(obj){
	if(!obj) obj = $("navPanel");
	if(!obj) return;
	if(!bxsAPI.navShow){
		obj.setStyle("height",($(window).getSize().y-110)+"px");
	}else{
		obj.setStyle("height","auto");
	}
}
bxsAPI.exec = function(){
	var apiboxwrapborder = $("apiboxwrapborder");
	for(var c=0;c<bxsAPI.apicates.length;c++){
		var cate = bxsAPI.apicates[c];
		var apicate = new Element("div",{"class":"apicate"}).inject(apiboxwrapborder).set("html",bxsAPI.listno(c+1)+cate.name);
		var apisData = bxsAPI.apis.filter(function(a){return a.cid===cate.cid});

		for(var i=0;i<apisData.length;i++){
			var mobj = apisData[i];
			mobj.idno = c+"_"+i;
			var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
			var anchor  = new Element("a",{"id":mobj.idno,"name":mobj.idno}).inject(apibox)
			
			var apih3 = new Element("div",{"class":"apih3"}).inject(apibox).set("html",bxsAPI.listno(c+1,i+1)+mobj.name);
			var apitbl = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);
			
			if(mobj.style && mobj.style==1){//处理只有key-value的情况
				for(key in mobj.datas){
					var trm = new Element("tr",{"class":"trm"}).inject(apitbl);
					var tdmn = new Element("td",{"class":"tdn"}).inject(trm).set("html",key);	
					var tdmc = new Element("td",{"class":"tdc"}).inject(trm).set("html",mobj.datas[key]);			
				}
			}else{

				var trm = new Element("tr",{"class":"trm"}).inject(apitbl);
				var tdmn = new Element("td",{"class":"tdn"}).inject(trm).set("html","地址");
				var tdmc = new Element("td",{"class":"tdc","colspan":2}).inject(trm).set("html",bxsAPI.server+mobj.url);
			
				var trm = new Element("tr",{"class":"trm"}).inject(apitbl);
				var tdmn = new Element("td",{"class":"tdn"}).inject(trm).set("html","方法");
				var tdmc = new Element("td",{"class":"tdc","colspan":2}).inject(trm).set("html",mobj.method);

				var auth=mobj.auth?mobj.auth:"无需登录权限(游客)";
				var trm = new Element("tr",{"class":"trm"}).inject(apitbl);
				var tdmn = new Element("td",{"class":"tdn"}).inject(trm).set("html","授权");
				var tdmc = new Element("td",{"class":"tdc","colspan":2}).inject(trm).set("html",auth);


				var urlParams=new Array();
				if(mobj.auth){
					var authInfo={field:bxsAPI.token.field,isrequire:true,desc:"登录令牌",sample:bxsAPI.token.value};
					urlParams.push(authInfo);
				}

				if(mobj.ispage){
					var cp=bxsAPI.page.curPage;
					var curPage={field:cp.field,isrequire:false,desc:"当前页，从"+cp.default+"开始,不填默认显示第1页。",sample:cp.default}
					urlParams.push(curPage);
					var ep=bxsAPI.page.eachPageSize;
					var pageSize={field:ep.field,isrequire:false,desc:"每页显示条数，不填默认显示"+ep.default+"条。",sample:ep.default}
					urlParams.push(pageSize);
				}

				if(mobj.urlparams){
					urlParams=urlParams.concat(mobj.urlparams);
				}

				if(urlParams.length>0){
					var trp = new Element("tr",{"class":"trm"}).inject(apitbl);
					var trpn = new Element("td",{"class":"tdn"}).inject(trp).set("html","urlp");
					var m = 0;
					for(m; m< urlParams.length;m++){
						var param =urlParams[m] ;
						var isrequire=param['isrequire']?"<redlight>[必填]</redlight>":"<over>[选填]</over>";
						if(m==0){
							var tdppn = new Element("td",{"class":"tdnn"}).inject(trp).set("html",param['field']);
							var tdppc = new Element("td",{"class":"tdcc"}).inject(trp).set("html",isrequire+param['desc']);
						}else{
							var trpp = new Element("tr",{"class":"trm"}).inject(apitbl);
							var tdppn = new Element("td",{"class":"tdnn"}).inject(trpp).set("html",param['field']);
							var tdppc = new Element("td",{"class":"tdcc"}).inject(trpp).set("html",isrequire+param['desc']);
						}
					}
					trpn.set("rowspan",m);
				}

				if(mobj.body){
					var trdesc = new Element("tr",{"class":"trm"}).inject(apitbl);
					var tddescn = new Element("td",{"class":"tdn"}).inject(trdesc).set("html","Body");
					var tddescc = new Element("td",{"class":"tdc","colspan":2}).inject(trdesc).set("html",mobj.body);
				}

				if(mobj.params){
					var trp = new Element("tr",{"class":"trm"}).inject(apitbl);
					var trpn = new Element("td",{"class":"tdn"}).inject(trp).set("html","参数");
					var m = 0;
					for(m; m< mobj.params.length;m++){
						var param =mobj.params[m] ;
						var isrequire=param['isrequire']?"<redlight>[必填]</redlight>":"<over>[选填]</over>";

						if(m==0){
							var tdppn = new Element("td",{"class":"tdnn"}).inject(trp).set("html",param['field']);
							var tdppc = new Element("td",{"class":"tdcc"}).inject(trp).set("html",isrequire+param['desc']);
						}else{
							var trpp = new Element("tr",{"class":"trm"}).inject(apitbl);
							var tdppn = new Element("td",{"class":"tdnn"}).inject(trpp).set("html",param['field']);
							var tdppc = new Element("td",{"class":"tdcc"}).inject(trpp).set("html",isrequire+param['desc']);
						}
					}
					trpn.set("rowspan",m);
				}

				if(mobj.desc){
					var trdesc = new Element("tr",{"class":"trm"}).inject(apitbl);
					var tddescn = new Element("td",{"class":"tdn"}).inject(trdesc).set("html","说明");
					var tddescc = new Element("td",{"class":"tdc","colspan":2}).inject(trdesc).set("html",mobj.desc);
				}
				
				//测试
				if(typeof mobj.istest=='undefined'){
					mobj.istest=true;//这个语句是说，如果不写istest的时候，默认是可以有测试按钮的
				}
				if(mobj.istest){
					var trtest = new Element("tr",{"class":"trm"}).inject(apitbl);
					var tdtestn = new Element("td",{"class":"tdn"}).inject(trtest).set("html","示例");
					var tdtestc = new Element("td",{"class":"tdc","colspan":2}).inject(trtest);
					var testUrl=bxsAPI.server+mobj.url;
					if(urlParams.length>0){
						testUrl+="?";
						for(var m=0; m< urlParams.length;m++) {
							var param = urlParams[m];
							if(m==urlParams.length-1){
								testUrl+=param['field']+"="+param['sample'];
							}else{
								testUrl+=param['field']+"="+param['sample']+"&";
							}
						}
					}
					var shtml="地址：<textarea class=\"testcodearea\" id=\"testurl"+mobj.idno+"\" rows='3'>"+testUrl+"</textarea>";
					if(mobj.params){
						var testParams={};
						for(var m=0; m< mobj.params.length;m++) {
							var param = mobj.params[m];
							testParams[param['field']]=param['sample'];
						}
						shtml += "参数：<textarea class=\"testcodearea\" id=\"testcode"+mobj.idno+"\" rows='5' >"+JSON.stringify(testParams,false,2)+"</textarea>";
					}

					shtml += "<input value=\"test\" type=\"button\" class=\"testbtn\" onclick=\"bxsAPI.testApi('"+mobj.idno+"','"+mobj.method+"',1)\"> ";
					//shtml += "<input value=\"comment\" type=\"button\" class=\"testbtn\" onclick=\"bxsAPI.testApi('"+mobj.idno+"','"+mobj.method+"',3)\"> ";
					shtml += "<a href=\"javascript:void(0)\" onclick=\"bxsAPI.testApi('"+mobj.idno+"','"+mobj.method+"',2)\">"+"browse"+"</a>";

					tdtestc.set("html",shtml);

				}
				//返回code说明
				// if(bxsAPI.returnCodes){
				// 	if(mobj.retuCode==null){
				// 		mobj.retuCode={}
				// 	}
                //
				// 	mobj.retuCode=Object.assign(mobj.retuCode,bxsAPI.returnCodes.isOK);
                //
				// 	if(mobj.auth){
				// 		mobj.retuCode=Object.assign(mobj.retuCode,bxsAPI.returnCodes.tokenErr);
				// 	}
                //
				// 	if(mobj.urlparams||mobj.params){
				// 		mobj.retuCode=Object.assign(mobj.retuCode,bxsAPI.returnCodes.paramsErr);
				// 	}
				// }

				if(mobj.retuCode){
					var trreturnp = new Element("tr",{"class":"trm"}).inject(apitbl);
					var trreturnpn = new Element("td",{"class":"tdn"}).inject(trreturnp).set("html","返回code");

					var chtm="";
					var ci=0;
					for(key in mobj.retuCode){
						if(ci==0){
							chtm=chtm+"<div class='tbitemno'><div class='iteml'>"+key+"</div><div class='itemr'>"+mobj.retuCode[key]+"</div></div>";
						}else{
							chtm=chtm+"<div class='tbitem'><div class='iteml'>"+key+"</div><div class='itemr'>"+mobj.retuCode[key]+"</div></div>";
						}
						ci++;
					}
					new Element("td",{"class":"tdnn","colspan":2,"style":'padding: 0'}).inject(trreturnp).set("html",chtm);
					trreturnpn.set("rowspan",1);
				}


				//返回data说明
				if(mobj.returns){
					var trreturnp = new Element("tr",{"class":"trm"}).inject(apitbl);
					var trreturnpn = new Element("td",{"class":"tdn"}).inject(trreturnp).set("html","返回关键参数");
					var pcount = 0;
					for(key in mobj.returns){
						if(pcount==0){
							var trreturnppn = new Element("td",{"class":"tdnn"}).inject(trreturnp).set("html",key);
							var trreturnpc = new Element("td",{"class":"tdcc"}).inject(trreturnp).set("html",mobj.returns[key]);
						}else{
							var trreturnpp = new Element("tr",{"class":"trm"}).inject(apitbl);
							var trreturnppn = new Element("td",{"class":"tdnn"}).inject(trreturnpp).set("html",key);
							var trreturnppc = new Element("td",{"class":"tdcc"}).inject(trreturnpp).set("html",mobj.returns[key]);
						}
						pcount++;
					}
					trreturnpn.set("rowspan",pcount);
				}
				
				//返回box
				var rebox  = new Element("div",{"id":"rebox"+mobj.idno,"class":"rebox"}).inject(apibox);
				var reh5 = new Element("h5",{"id":"reh5"+mobj.idno}).inject(rebox).set("html","<span onclick=\"bxsAPI.hideApiBox('"+mobj.idno+"')\">隐藏</span>");
				var rearea = new Element("div",{"class":"Canvas","id":"rearea"+mobj.idno}).inject(rebox);
			}
		}
	}
	
	bxsAPI.layout.init();
}

//接口测试方法
bxsAPI.fjsOpts = {};
bxsAPI.testApi = function(idno,method,flag,subno){
	bxsAPI.starting = new Date().getTime();//请求开始时间
	if(idno && method){
		var url=$("testurl"+idno).value;
		alert("测试地址：\n"+url);
		if($("testcode"+idno)){
			var data=$("testcode"+idno).value;
			try {
				alert("测试参数：\n"+data);
				data=JSON.parse(data);
			}catch (err){
				alert("参数输入框中的JSON格式错误，请检查后再试！");
				return;
			}

		}else{
			var data=null;
		}


		if(flag==2){
			window.open(reqData);
		}else{

			var actionReq = new Request({
				method: method,
				encoding:'utf-8',
				url:url,
				headers: {'Accept': 'application/json'},
				data:data,
				onSuccess:function(res){
					console.log(res);
					var rebox = $("rebox"+idno);
					var rearea = $("rearea"+idno);
					if(rebox){
						rebox.style.display = "block";
						//rearea.value = js_beautify(res, bxsAPI.fjsOpts);
						Process(res,idno);
					}
					bxsAPI.requestTiming(idno);
				},
				onFailure:function(req){
					alert("出错了，检查一下网络？"+JSON.parse(req));
				}

			}).send();


		}
	}else{
		alert("参数为空，检查一下");
	}
}


bxsAPI.requestTiming = function(idno){
	bxsAPI.ending = new Date().getTime();//请求结束时间
	if(bxsAPI.starting && bxsAPI.ending){
		var reqtimingspan = $("reqtimingspan"+idno);
		if(reqtimingspan){
			reqtimingspan.innerHTML = (bxsAPI.ending-bxsAPI.starting)+"ms";
		}
	}
}

bxsAPI.hideApiBox = function(idno){
	var rebox = $("rebox"+idno);
	if(rebox) rebox.style.display = "none";
}


/**
 * 版本更新
 */
bxsAPI.execUpdates = function(){
	var apiboxwrapborder = $("apiboxwrapborder");
		
	var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
	var apih3 = new Element("div",{"class":"apih3"}).inject(apibox).set("html","<span style='color:blue'>更新记录</span>");
	var apitbl = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);
		
	for(var i=0;i<bxsAPI.updated.length;i++){
		var upobj = bxsAPI.updated[i];
		var trm = new Element("tr",{"class":"trm"}).inject(apitbl);
		var tdmn = new Element("td",{"class":"tdn"}).inject(trm).set("html",upobj.ver);
		var tdmc = new Element("td",{"class":"tdc_dt"}).inject(trm).set("html",upobj.dt);
		var tdmcon = new Element("td",{"class":"tdc"}).inject(trm);
		for(var k=0;k<upobj.con.length;k++){
			 new Element("div",{"class":"tdcondiv"}).inject(tdmcon).set("html",upobj.con[k]);
		}
	}
}
/**
 * 服务器信息
 */
bxsAPI.execServer = function(){
	var apiboxwrapborder = $("apiboxwrapborder");
		
	var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
	var apih3 = new Element("div",{"class":"apih3"}).inject(apibox).set("html","<span style='color:#666'>服务器信息</span>");
	var apitbl = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);
		
	var trm = new Element("tr",{"class":"trm"}).inject(apitbl);
	new Element("td",{"class":"tdn"}).inject(trm).set("html","服务器");
	new Element("td",{"class":"tdc"}).inject(trm).set("html",bxsAPI.server);
	
	trm = new Element("tr",{"class":"trm"}).inject(apitbl);
	new Element("td",{"class":"tdn"}).inject(trm).set("html","测试账号");
	new Element("td",{"class":"tdc"}).inject(trm).set("html","uid="+bxsAPI.uid+",un="+bxsAPI.un);
	
	if(bxsAPI.readme){
		trm = new Element("tr",{"class":"trm"}).inject(apitbl);
		new Element("td",{"class":"tdn"}).inject(trm).set("html","自述");
		new Element("td",{"class":"tdc"}).inject(trm).set("html",bxsAPI.readme);
	}
	
}

/**
 * 返回数据说明
 */
bxsAPI.execBack = function(){
	var apiboxwrapborder = $("apiboxwrapborder");

	var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
	var apih3 = new Element("div",{"class":"apih3"}).inject(apibox).set("html","<span style='color:#666'>返回数据说明</span>");
	var apitbl = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);

	if(bxsAPI.readme){
		trm = new Element("tr",{"class":"trm"}).inject(apitbl);
		new Element("td",{"class":"tdn"}).inject(trm).set("html","格式");
		new Element("td",{"class":"tdc","colspan":2}).inject(trm).set("html",bxsAPI.standard);

		var trreturnp = new Element("tr",{"class":"trm"}).inject(apitbl);
		var trreturnpn = new Element("td",{"class":"tdn"}).inject(trreturnp).set("html","返回");
		var pcount = 0;
		for(key in bxsAPI.readme){
			if(pcount==0){
				var trreturnppn = new Element("td",{"class":"tdnn"}).inject(trreturnp).set("html",key);
				var trreturnpc = new Element("td",{"class":"tdcc"}).inject(trreturnp).set("html",bxsAPI.readme[key]);
			}else{
				var trreturnpp = new Element("tr",{"class":"trm"}).inject(apitbl);
				var trreturnppn = new Element("td",{"class":"tdnn"}).inject(trreturnpp).set("html",key);
				var trreturnppc = new Element("td",{"class":"tdcc"}).inject(trreturnpp).set("html",bxsAPI.readme[key]);
			}
			pcount++;
		}
		trreturnpn.set("rowspan",pcount);
	}

}

/**
 * 返回代码
 */
bxsAPI.execReturnCode = function() {
	var apiboxwrapborder = $("apiboxwrapborder");

	var apibox = new Element("div", {"class": "apibox"}).inject(apiboxwrapborder);
	var apih3 = new Element("div", {"class": "apih3"}).inject(apibox).set("html", "返回代码");
	var apitbl = new Element("table", {"class": "apitbl","border": "0","width": "100%","cellpadding": "0","cellspacing": "0"}).inject(apibox);

	var size=bxsAPI.returnCode.length;
	if (size > 0) {
		for (var m = 0; m <size; m++) {
			var category=bxsAPI.returnCode[m].category;
			var list=bxsAPI.returnCode[m].list;
			var trp = new Element("tr", {"class": "trm"}).inject(apitbl);
			var trpn = new Element("td", {"class": "tdn"}).inject(trp).set("html", category);
			for (var n=0; n < list.length; n++) {
				var param = list[n];

				if (n == 0) {
					var tdppn = new Element("td", {"class": "tdnn"}).inject(trp).set("html", param['code']);
					var tdppc = new Element("td", {"class": "tdcc"}).inject(trp).set("html", param['desc']);
				} else {
					var trpp = new Element("tr", {"class": "trm"}).inject(apitbl);
					var tdppn = new Element("td", {"class": "tdnn"}).inject(trpp).set("html", param['code']);
					var tdppc = new Element("td", {"class": "tdcc"}).inject(trpp).set("html", param['desc']);
				}
			}
			trpn.set("rowspan", n);
		}
	}
}
/**
 * 系统信息
 */
bxsAPI.execSystemInfo = function(){
	var apiboxwrapborder = $("apiboxwrapborder");

	var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
	var apih3 = new Element("div",{"class":"apih3"}).inject(apibox).set("html","<span style='color:black'>系统开发</span>");
	var apitbl = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);

	if(bxsAPI.standard){
		trm = new Element("tr",{"class":"trm"}).inject(apitbl);
		new Element("td",{"class":"tdn"}).inject(trm).set("html","项目名称");
		new Element("td",{"class":"tdc","colspan":2}).inject(trm).set("html",bxsAPI.standard);
	}

	if(bxsAPI.group.length>0){
		var trp = new Element("tr",{"class":"trm"}).inject(apitbl);
		var trpn = new Element("td",{"class":"tdn"}).inject(trp).set("html","项目团队");
		for(var m =0; m< bxsAPI.group.length;m++){

			var group =bxsAPI.group[m] ;
			if(m==0){
				var tdppn = new Element("td",{"class":"tdnn"}).inject(trp).set("html",group['groupName']);
				var tdppc = new Element("td",{"class":"tdcc"}).inject(trp).set("html",group['detail']);
			}else{
				var trpp = new Element("tr",{"class":"trm"}).inject(apitbl);
				var tdppn = new Element("td",{"class":"tdnn"}).inject(trpp).set("html",group['groupName']);
				var tdppc = new Element("td",{"class":"tdcc"}).inject(trpp).set("html",group['detail']);
			}
		}
		trpn.set("rowspan",m);
	}

	if(bxsAPI.mainFunctions){

		var trreturnp = new Element("tr",{"class":"trm"}).inject(apitbl);
		var trreturnpn = new Element("td",{"class":"tdn"}).inject(trreturnp).set("html","主要功能");

		for(var key=0;key<bxsAPI.mainFunctions.length;key++){
			if(key==0){
				new Element("td",{"class":"tdcc","colspan":2}).inject(trreturnp).set("html",bxsAPI.mainFunctions[key]);
			}else{
				var trreturnpp = new Element("tr",{"class":"trm"}).inject(apitbl);
				new Element("td",{"class":"tdcc","colspan":2}).inject(trreturnpp).set("html",bxsAPI.mainFunctions[key]);
			}
		}
		trreturnpn.set("rowspan",key);
	}

}

/**
 * 开发进度
 */
bxsAPI.execProgress = function(){
	var apiboxwrapborder = $("apiboxwrapborder");

	var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
	var apih3 = new Element("div",{"class":"apih3"}).inject(apibox).set("html","<span style='color:blue'>进度详情</span>");
	var apitbl = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);

	if(bxsAPI.progress){

		for(var k=0;k< bxsAPI.progress.length;k++){

			var week = "<span style='color: #00AA00;line-height: 25px'>第"+bxsAPI.progress[k]['week']+"周</span><br>"+bxsAPI.progress[k]['date'];
			var trreturnp = new Element("tr",{"class":"trm"}).inject(apitbl);
			var trreturnpn = new Element("td",{"class":"tdn"}).inject(trreturnp).set("html",week);

			var model=bxsAPI.progress[k]['model'];
			for(var kk = 0;kk < model.length;kk++){

				var functHtml = "";
				var functions = model[kk]['functions'];
				for(var kkk = 0;kkk < functions.length;kkk++){
					functHtml = functHtml +"<label style='margin-left: 10px;margin-right: 5px'>"+ (kkk+1) + ".</label>" + functions[kkk] + "<br>";
				}
				if(kk==0){
					var trreturnppn = new Element("td",{"class":"tdnn"}).inject(trreturnp).set("html",model[kk]['modelName']);
					var trreturnpc = new Element("td",{"class":"tdcc"}).inject(trreturnp).set("html",functHtml);
				}else{
					var trreturnpp = new Element("tr",{"class":"trm"}).inject(apitbl);
					var trreturnppn = new Element("td",{"class":"tdnn"}).inject(trreturnpp).set("html",model[kk]['modelName']);
					var trreturnppc = new Element("td",{"class":"tdcc"}).inject(trreturnpp).set("html",functHtml);
				}
			}
			trreturnpn.set("rowspan",kk);
		}

	}

}
/**
 * 第三方信息
 */
bxsAPI.execThirdInfo = function(){
	var apiboxwrapborder = $("apiboxwrapborder");
		
	var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
	var apih3 = new Element("div",{"class":"apih3"}).inject(apibox).set("html","第三方信息");
	var apitbl = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);
	for(key in bxsAPI.thirdInfo){
		var thirdObj = bxsAPI.thirdInfo[key];
		var trm = new Element("tr",{"class":"trm"}).inject(apitbl);
		var tdmn = new Element("td",{"class":"tdn_dict"}).inject(trm).set("html",thirdObj.ti);
		var tdmc = new Element("td",{"class":"tdc"}).inject(trm);
		for(var i=0;i<thirdObj.items.length;i++){
			var itemObj = thirdObj.items[i];
			var dl = new Element("dl",{"class":"thirddl"}).inject(tdmc);
			var dt = new Element("dt").inject(dl).set("html",itemObj.ti);
			for(k=0;k<itemObj.keys.length;k++){
				var itemStr = itemObj.keys[k];
				var dd = new Element("dd").inject(dl).set("html",itemStr)
			}
		}
	}
}
bxsAPI.listno = function(L1,L2,L3){
	var s = "<span style='margin-right:5px;'>"+L1;
	if(L2) s += "."+L2;
	if(L3) s += "."+L3;
	return s+"</span>";
}
bxsAPI.layout = {
	init:function(){
		var layoutbox = $("layoutbox");
		if(!layoutbox){
			layoutbox = new Element("div",{"class":"layoutbox"}).inject(document.body);
			var arr = ["TOP","Default","Auto_Screen","Full"];
			for(var i=0;i<arr.length;i++){
				var aa = new Element("a",{"href":"###"}).inject(layoutbox).set("html",arr[i]);
				aa.val = arr[i];
				aa.onclick = function(){
					if(!this.val) return;
					var apiboxwrap = $("apiboxwrap");
					if(!apiboxwrap) return;
					if(this.val=="Default"){
						apiboxwrap.style.width = "830px";
						apiboxwrap.style.margin = "0 auto";
					}else if(this.val=="Auto_Screen"){
						apiboxwrap.style.width = "auto";
						apiboxwrap.style.margin = "0 230px 0 0";
					}else if(this.val=="Full"){
						apiboxwrap.style.width = "auto";
						apiboxwrap.style.margin = "0 0 0 0";
					}else if(this.val="TOP"){//回到顶部
						document.documentElement.scrollTop = document.body.scrollTop =0;
					}
				}
			}
		}
	}
}
bxsAPI.ver = function(){
	document.write("<span class='spanversion'>_"+bxsAPI.version+"</span>")
}
document.write("<script type=\"text/javascript\" src=\"jsbeautify/beautify.js\"></script>");
