/**
 * Created by Administrator on 2017/4/12.
 */
/**
 *
 */
var bxsAPI = {};

bxsAPI.local = location.host=="saas.com"? 0 : 1;
bxsAPI.apis = [];
bxsAPI.server = bxsAPI.local?"http://saas.icloudinn.com":"http://saas.com";


bxsAPI.fieldDict={
    dd:"产品设计",
    detail:"汪君相、梁绣"
}

/**
 * 数据字典
 */
bxsAPI.execFieldDict = function(data){

    for (var i=0;i<data.length;i++){
        var table = data[i]['table'];
        var apiboxwrapborder = $("apiboxwrapborder");

        var apibox = new Element("div",{"class":"apibox"}).inject(apiboxwrapborder);
        new Element("div",{"class":"apih3"}).inject(apibox).set("html",table['Name']+"<over style=\"font-weight: normal;margin: 10px\">"+table['Comment']+"</over><span class=\"rows\">"+table['Rows']+"条记录</span>");
        var apitb = new Element("table",{"class":"apitbl","border":"0","width":"100%","cellpadding":"0","cellspacing":"0"}).inject(apibox);

        var dictionary = data[i]['dictionary'];
        var tr = new Element("tr",{"class":"trm"}).inject(apitb);
        new Element("td",{"class": "tb_center","width":"150px"}).inject(tr).set("html","字段名");
        new Element("td",{"class":"tb_center","width":"100xp"}).inject(tr).set("html","数据类型");
        new Element("td",{"class":"tb_center","width":"80px"}).inject(tr).set("html","默认值");
        new Element("td",{"class":"tb_center","width":"50px"}).inject(tr).set("html","主外键");
        new Element("td",{"class":"tb_center"}).inject(tr).set("html","说明");

        for (var j=0;j<dictionary.length;j++){
            var dict = dictionary[j];
            var tr = new Element("tr",{"class":"trm"}).inject(apitb);
            new Element("td",{"class": "tdnn"}).inject(tr).set("html",dict['COLUMN_NAME']);
            new Element("td",{"class":"tdc"}).inject(tr).set("html",dict['COLUMN_TYPE']);
            new Element("td",{"class":"center"}).inject(tr).set("html",dict['COLUMN_DEFAULT']);
            new Element("td",{"class":"center"}).inject(tr).set("html",dict['COLUMN_KEY']);
            new Element("td",{"class":"tdc"}).inject(tr).set("html",dict['COLUMN_COMMENT']);
        }
    }

}

new Request({
    method: 'get',
    encoding:'utf-8',
    url:bxsAPI.server+'/api/v1/deal/data-dictionary',
    headers: {'Accept': 'application/json'},
    onSuccess:function(res){
        var rt = JSON.parse(res);
        if(rt['code']!=100){
            alert(rt['msg']);
        }else {
            bxsAPI.execFieldDict(rt['data']);
        }
    },
    onFailure:function(req){
        alert("出错了，检查一下网络？"+JSON.parse(req));
    }

}).send();
