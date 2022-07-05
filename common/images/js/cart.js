  function delCart(){
      utils.delParam("WdjaCart");
      utils.delParam("WdjaBuy");
      window.location.reload();
  }
  
  function clearCart(){
        var WdjaCart = utils.getParam("WdjaCart");
        var WdjaBuy = utils.getParam("WdjaBuy");
        if(WdjaBuy){
            var ids = WdjaBuy.split(",");
            var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
            var productlist = jsonstr.productlist;
            for(var i in productlist){
                if(ids.includes(productlist[i].id)) cart.deleteproduct(productlist[i].id);
            }
            utils.delParam("WdjaBuy");
            var WdjaCart = utils.getParam("WdjaCart");
            var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
            if(parseInt(jsonstr.totalNumber) == 0) {
                utils.delParam("WdjaCart");
                var Cart = document.getElementById('cart');
                Cart.getElementsByTagName('i')[0].innerHTML = '';
            }
        }
    }
  
  
  function updateCart(id,num){
      id = id.slice(4);
      if(num == '' || num == null) num = 1;
      if(num == 0) cart.deleteproduct(id);
      cart.updateproductnum(id,num);
      window.location.reload();
  }
  
  function addCart(id,topic,num,price,snum){
  var Buybtn = document.getElementById('buynum');
  if(Buybtn){
      var Buynum = get_num(Buybtn.value);
      if(Buynum != 0) num = Buynum;
  }
  if(parseInt(num) > parseInt(snum)){alert(topic + '超过库存'); return;}
  var product =  
    {
        'id': id,'name': topic,'num':num,'price':price,'snum':snum
    };
    cart.addproduct(product);
  }

    product={
        id:0,name:"",num:0,price:0.00,snum:0,};
    orderdetail={
        username:"",phone:"",address:"",zipcode:"",totalNumber:0,totalAmount:0.00
    }
    cart = {
        //向购物车中添加商品
        addproduct:function(product){
            var WdjaCart = utils.getParam("WdjaCart");
            if(WdjaCart==null||WdjaCart==""){
                //第一次加入商品
                var jsonstr = {"productlist":[{"id":product.id,"name":product.name,"num":product.num,"price":product.price,"snum":product.snum}],"totalNumber":product.num,"totalAmount":(product.price*product.num)};
                utils.setParam("WdjaCart","'"+JSON.stringify(jsonstr));
                var Cart = document.getElementById('cart');
                Cart.getElementsByTagName('i')[0].innerHTML = jsonstr.totalNumber;
            }else{
                var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
                var productlist = jsonstr.productlist;
                var result=false;
                //查找购物车中是否有该商品
                for(var i in productlist){
                    if(productlist[i].id == product.id){
                        //购物车中有该商品
                        productlist[i].price=product.price;
                        productlist[i].num=parseInt(productlist[i].num)+parseInt(product.num);
                        if(parseInt(productlist[i].num) > parseInt(product.snum)){alert(productlist[i].name + '超过库存'); return;}
                        result = true;
                    }
                }
                if(!result){
                    //没有该商品就直接加进去
                    productlist.push({"id":product.id,"name":product.name,"num":product.num,"price":product.price,"snum":product.snum});
                }
                //重新计算总价
                jsonstr.totalNumber=parseInt(jsonstr.totalNumber)+parseInt(product.num);
                jsonstr.totalAmount=parseFloat(jsonstr.totalAmount)+(parseInt(product.num)*parseFloat(product.price));
                orderdetail.totalNumber = jsonstr.totalNumber;
                orderdetail.totalAmount = jsonstr.totalAmount;
                //保存购物车
                utils.setParam("WdjaCart","'"+JSON.stringify(jsonstr));
                var Cart = document.getElementById('cart');
                Cart.getElementsByTagName('i')[0].innerHTML = jsonstr.totalNumber;
            }
        },//修改商品库存
        updateproductnum:function(id,num){
            var WdjaCart = utils.getParam("WdjaCart");
            var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
            var productlist = jsonstr.productlist;
            for(var i in productlist){
                if(productlist[i].id == id){
                    if(parseInt(num) > parseInt(productlist[i].snum)){alert(productlist[i].name + '超过库存'); return;}
                    jsonstr.totalNumber = parseInt(jsonstr.totalNumber)+(parseInt(num)-parseInt(productlist[i].num));
                    jsonstr.totalAmount = (parseFloat(jsonstr.totalAmount)+parseFloat((parseInt(num)*parseFloat(productlist[i].price))-parseInt(productlist[i].num)*parseFloat(productlist[i].price))).toFixed(2);
                    productlist[i].num = parseInt(num);
                    orderdetail.totalNumber = jsonstr.totalNumber;
                    orderdetail.totalAmount = parseFloat(jsonstr.totalAmount).toFixed(2);
                    utils.setParam("WdjaCart","'"+JSON.stringify(jsonstr));
                    var Cart = document.getElementById('cart');
                    Cart.getElementsByTagName('i')[0].innerHTML = jsonstr.totalNumber;
                    return;
                }
            }
        },//获取购物车中的所有商品
        getproductlist:function(){
            var WdjaCart = utils.getParam("WdjaCart");
            var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
            var productlist = jsonstr.productlist;
            orderdetail.totalNumber = jsonstr.totalNumber;
            orderdetail.totalAmount = parseFloat(jsonstr.totalAmount).toFixed(2);
            return productlist;
        },//判断购物车中是否存在商品
        existproduct:function(id){
            var WdjaCart = utils.getParam("WdjaCart");
            var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
            var productlist = jsonstr.productlist;
            var result=false;
            for(var i in productlist){
                if(productlist[i].id==product.id){
                    result = true;
                }
            }
            return result;
        },//删除购物车中商品
        deleteproduct:function(id){
            var WdjaCart = utils.getParam("WdjaCart");
            var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
            var productlist = jsonstr.productlist;
            var list=[];
            for(var i in productlist){
                if(productlist[i].id==id){
                    jsonstr.totalNumber=parseInt(jsonstr.totalNumber)-parseInt(productlist[i].num);
                    jsonstr.totalAmount=parseFloat(jsonstr.totalAmount)-parseInt(productlist[i].num)*parseFloat(productlist[i].price);
                }else{
                    list.push(productlist[i]);
                }
            }
            jsonstr.productlist = list;
            orderdetail.totalNumber = jsonstr.totalNumber;
            orderdetail.totalAmount = parseFloat(jsonstr.totalAmount).toFixed(2);
            utils.setParam("WdjaCart","'"+JSON.stringify(jsonstr));
            var Cart = document.getElementById('cart');
            Cart.getElementsByTagName('i')[0].innerHTML = jsonstr.totalNumber;
        },//获取购物车中商品数量
        getproductnum:function(){
            var WdjaCart = utils.getParam("WdjaCart");
            var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
            return jsonstr.totalNumber;
        }
    };


var WdjaCart = utils.getParam("WdjaCart");
var Cart = document.getElementById('cart');
var table = document.getElementById("cart_list");
var table2 = document.getElementById("buy_list");
var Cnum = document.getElementById("cart_num");
var Cprice = document.getElementById("cart_price");
var Cprices = document.getElementById("cart_prices");
if(WdjaCart!=null && WdjaCart!=""){
    var jsonstr = JSON.parse(WdjaCart.substr(1,WdjaCart.length));
    Cart.getElementsByTagName('i')[0].innerHTML = jsonstr.totalNumber;
    if(table){
        var productlist = jsonstr.productlist;
        var totalNumber = 0;
        var totalAmount = 0.00;
        for(var i in productlist){
            var currentRows = table.rows.length; 
            var insertTr = table.insertRow(currentRows);
            var insertTd = insertTr.insertCell(0);
            insertTd.style.textAlign="left";
            insertTd.innerHTML = '<td height="25"><input type="checkbox" name="sel_id" value="'+parseInt(productlist[i].id)+'" class="checkbox" checked="checked"/></td>';
            insertTd = insertTr.insertCell(1);
            insertTd.style.textAlign="left";
            insertTd.innerHTML = productlist[i].name;
            insertTd = insertTr.insertCell(2);
            insertTd.style.textAlign="center";
            insertTd.innerHTML = '<td><input style="width:60px;padding:0;text-align:center;" type="number" min="1" max="1000" step="1" id="num_'+parseInt(productlist[i].id)+'" name="num_'+parseInt(productlist[i].id)+'" value="'+parseInt(productlist[i].num)+'" size="3" class="i1" onchange="updateCart(this.id, this.value)" /></td>';
            insertTd = insertTr.insertCell(3);
            insertTd.style.textAlign="center";
            insertTd.innerHTML = parseFloat(productlist[i].price);
                totalAmount = totalAmount + (parseInt(productlist[i].num)*parseFloat(productlist[i].price));
                totalNumber = totalNumber + parseInt(productlist[i].num);
        }
        if(Cnum) Cnum.getElementsByTagName('span')[0].innerHTML = totalNumber;
        if(Cprice) Cprice.getElementsByTagName('span')[0].innerHTML = parseFloat(totalAmount).toFixed(2);
    }
    if(table2){
        var cart_ids = document.getElementById("cart_ids");
        var ids = utils.getParam('WdjaBuy').split(",");
        var productlist = jsonstr.productlist;
        var totalNumber = 0;
        var totalAmount = 0.00;
        var get_ids = '';
        for(var i in productlist){
            if(ids.includes(productlist[i].id)){
                var currentRows = table2.rows.length; 
                var insertTr = table2.insertRow(currentRows);
                var insertTd = insertTr.insertCell(0);
                insertTd.style.textAlign="left";
                insertTd.innerHTML = productlist[i].name;
                insertTd = insertTr.insertCell(1);
                insertTd.style.textAlign="center";
                insertTd.innerHTML = parseInt(productlist[i].num);
                insertTd = insertTr.insertCell(2);
                insertTd.style.textAlign="center";
                insertTd.innerHTML = parseFloat(productlist[i].price);
                totalAmount = totalAmount + (parseInt(productlist[i].num)*parseFloat(productlist[i].price));
                totalNumber = totalNumber + parseInt(productlist[i].num);
                get_ids = get_ids + productlist[i].id + ':' + parseInt(productlist[i].num) + ':' + parseInt(productlist[i].price) + ','
            }
        }
        cart_ids.value = get_ids;
        if(Cnum) Cnum.getElementsByTagName('span')[0].innerHTML = totalNumber;
        if(Cprice) Cprice.getElementsByTagName('span')[0].innerHTML = parseFloat(totalAmount).toFixed(2);
        if(Cprices) Cprices.value = parseFloat(totalAmount).toFixed(2);
    }
}
