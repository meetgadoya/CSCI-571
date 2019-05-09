
var myApp=angular.module('myApp', ["ngAnimate","ngMaterial","ngMessages","angular-svg-round-progressbar"]);
myApp.controller('FormController',['$scope','$http',function($scope,$http){
    // $scope.user.radio_loc='current';
    $scope.zip=undefined;
    $http.get("http://ip-api.com/json")
    .then(function(response) {
        $scope.zip=response.data.zip; 
    
    });
    
    // $scope.user.keyword="";
    $scope.showWishList=false;
    $scope.similardrop1="default";
    $scope.similardrop2="asc";
    $scope.limit=5;
    $scope.searchProductsList=[];
    $scope.showProds=false;
    $scope.showItemDetails=false;
    $scope.ItemDetails=[];
    $scope.afterpage=new Array(6).fill(0);
    $scope.currpageprod={};
    $scope.pageNumber = 0;
    $scope.SimilarItems=[];
    $scope.sortdisable=true;
    $scope.order=null;
    $scope.orderSort="asc";
    $scope.orderType="";
    $scope.showprogress=false;
    $scope.googleImg=[];
    $scope.curritem=undefined;
    $scope.bin=0;
    $scope.colourChange={};
    $scope.favList=[];
    $scope.totalprice=0;
    $scope.activeTab="result";
    $scope.fiveTabs="product";
    $scope.curritem_wish=undefined;
    $scope.curritem_result=undefined;
    $scope.showProdsWish=true;



    var i=0;
    Number.prototype.round = function(p) {
        p = p || 10;
        return parseFloat( this.toFixed(p) );
    };
    if (!localStorage.getItem('templocal') || JSON.parse(localStorage.getItem('templocal')).length === 0) {
        localStorage.setItem('templocal', JSON.stringify([]));
    } else if (JSON.parse(localStorage.getItem('templocal')).length > 0) {
        $scope.favList = JSON.parse(localStorage.getItem('templocal'));
    }

    if(!localStorage.getItem('price')) {
        localStorage.setItem('price', 0.0);
    } else {
        $scope.totalprice = parseFloat(localStorage.getItem('price')).round(2);
    }
    

    $scope.current_click=function()
    {
        $scope.user.zip_text="";
        $scope.searchText="";
    };
    $scope.isValid=function(){
        if($scope.user.keyword==undefined || $scope.user.keyword.trim()==""){
            return true;
        }
        if($scope.user.radio_loc=="current" && $scope.zip==undefined)
        {
            
            return true;
        }
        else if(($scope.user.radio_loc=="not_current" && $scope.searchText==undefined)||($scope.user.radio_loc=="not_current" && $scope.searchText.length!==5))
        {
            return true;
        }
        
        return false;
    };

    $scope.clearAll=function(){
        $scope.showProdsWish=true;

        $scope.user.keyword=undefined;
        $scope.similardrop1="default";
        $scope.similardrop2="asc";
        $scope.limit=5;
        $scope.searchProductsList=[];
        $scope.showItemDetails=false;
        $scope.ItemDetails=[];
        $scope.afterpage=new Array(6).fill(0);
        $scope.currpageprod={};
        $scope.pageNumber = 0;
        $scope.SimilarItems=[];
        $scope.sortdisable=true;
        $scope.order=null;
        $scope.orderSort="asc";
        $scope.orderType="";
        $scope.showprogress=false;
        $scope.googleImg=[];
        $scope.curritem=undefined;
        $scope.colourChange={};
        $scope.totalprice=0;
        $scope.showProds=false;
        $scope.showItemDetails=false;
        $scope.currpageprod={};
        $scope.activeTab="result";
        $scope.fiveTabs="product";
        $scope.showItemDetails=false;
        $scope.showWishList=false;
        $scope.user.radio_loc="current";
        $scope.bin=0;
        $scope.search_form.keyword.$setUntouched();
    
        
       

    
    };

    $scope.submit=function()
    {       
        $scope.showprogress=true;

        $scope.searchProductsList=[];
        $scope.currpageprod={};
        $scope.curritem=undefined;
        $scope.curritem_result=undefined;

        $scope.ItemDetails=[];
// to disappear the No records message
        $scope.showProdsWish=false;
        $scope.showProds=false;
        $scope.showItemDetails=false;
        $scope.showWishList=false;


        $scope.url="http://csci-hw-8-nodejs.appspot.com/api?keyword="+$scope.user.keyword+"&new="+$scope.user.new+"&used="+$scope.user.used+"&unspecified="+$scope.user.unspecified+"&free="+$scope.user.free+"&local="+$scope.user.local;
        
        if($scope.user.category!=undefined && $scope.user.category!="")
        {
            $scope.url+="&category="+$scope.user.category;
        }

        if($scope.user.radio_loc=="current")
        {
            $scope.url+="&zipcode="+$scope.zip;
        }
        else if($scope.user.radio_loc=="not_current")
        {
            $scope.url+="&zipcode="+$scope.searchText;
        }
        if($scope.user.distance!=undefined && $scope.user.distance!="")
        {
            $scope.url+="&maxdistance="+$scope.user.distance;
            
        }
        else{
            $scope.url+="&maxdistance=10";
        }

        $http.get($scope.url).then(function(response){
            
            try{

                
                $scope.searchProductsList = response.data.findItemsAdvancedResponse[0].searchResult[0].item;

                if($scope.searchProductsList==undefined || $scope.searchProductsList.length==0)
                {
                    $scope.showprogress=false;
                    $scope.showProdsWish=true;
                    $scope.showProds=true;
                    $scope.searchProductsList=[];
                    return;    
                }
            }
            catch(error){
                
            }
            for (var i = 0; i < $scope.searchProductsList.length; i++) {
                $scope.colourChange[$scope.searchProductsList[i].itemId[0]] = false;
            }


            if ($scope.favList.length > 0) {
                for (var k = 0; k < $scope.searchProductsList.length; k++) {
                    for (var j = 0; j < $scope.favList.length; j++) {
                        if ($scope.searchProductsList[k].itemId[0] === $scope.favList[j].itemId[0]) {
                            $scope.colourChange[$scope.searchProductsList[k].itemId[0]] = true;
                        }

                    }

                }

            }
            $scope.pageNumber = 0;
            $scope.afterpage[$scope.pageNumber]=1;
            if($scope.searchProductsList!=undefined && $scope.searchProductsList.length!=0)
            {
                
                if($scope.searchProductsList.length > 10)
                {
                    for(i=1; i<($scope.searchProductsList.length)/10; i++)
                    {
                        $scope.afterpage[i] = 1;
                    }
                }
                var Products_len=$scope.searchProductsList.length;
                var i=0;
                $scope.searchId=$scope.pageNumber*10;
                while((Products_len-$scope.searchId)>0 && i<10){
                    $scope.currpageprod[($scope.searchId+i+1)]=$scope.searchProductsList[($scope.searchId+i)];
                    Products_len--;
                    i++;
                }
            }
            
            $scope.activeTab="result";
            $scope.bin=0;
            $scope.showProds=true;
            $scope.showItemDetails=false;
            $scope.showWishList=false;
            $scope.showProdsWish=true;

            
            $scope.showprogress=false;
            
        }),

        function(error){
            console.log("Error in getting search results",error);
        }
    };

   
    $scope.checkList = function(itemId) {
        return $scope.colourChange[itemId];
    };
    $scope.addToFav = function(index, image, title, price, shipping, seller, itemId,shipping_info,selling_status) {
        
            var list = JSON.parse(localStorage.getItem("templocal"));
            var total=parseFloat(localStorage.getItem("price")).round(2);
            var tempfav = {
                "index": i,
                "image": image,
                "title": title,
                "price": price,
                "shipping": shipping,
                "seller":seller,
                "itemId": itemId,
                "shippingInfo":shipping_info,
                "sellingStatus":selling_status,
            };

            $scope.favObject = tempfav;
            list.push($scope.favObject);
            localStorage.setItem("templocal", JSON.stringify(list));
            $scope.favList = list;
            $scope.colourChange[itemId[0]]=true;
            i=i+1;
            total+=parseFloat(price).round(2);
            $scope.totalprice=total;
            localStorage.setItem("price",total);
            // localStorage.setItem("price",$scope.totalprice);
        };
    $scope.removeFav=function(price,itemId)
        {
            localStorage.setItem("templocal", JSON.stringify([]));
            var list = $scope.favList.filter(function(value) {
                return value.itemId[0] !== itemId[0];
            });

            localStorage.setItem("templocal", JSON.stringify(list));
            $scope.favList = list;
            $scope.colourChange[itemId[0]]=false;
            $scope.totalprice-=parseFloat(price).round(2);
            localStorage.setItem("price",$scope.totalprice);
            
    };
    $scope.showDetailsfromId=function(itemId,data)
    {
        $scope.showprogress=true;
        $scope.showProdsWish=false;
        $scope.showProds=false;
        $scope.showWishList=false;
        $scope.showItemDetails=true;
        $scope.curritem=data;
        $scope.curritem_wish=data;
        $scope.fiveTabs="product";
        $scope.url2="http://csci-hw-8-nodejs.appspot.com/api2?itemId="+itemId[0];
        $http.get($scope.url2).then(function(response){
            $scope.showItemDetails=true;
            $scope.ItemDetails=response.data.Item;
        }),
        function(error){
            console.log("Error in getting item details",error);

        };
        $scope.url3="http://csci-hw-8-nodejs.appspot.com/api3?itemId="+itemId[0];
        $http.get($scope.url3).then(function(response){
            $scope.SimilarItems=response.data.getSimilarItemsResponse.itemRecommendations.item;
            for(var i=0;i<$scope.SimilarItems.length;i++)
            {
                $scope.SimilarItems[i].buyItNowPrice.__value__=parseFloat($scope.SimilarItems[i].buyItNowPrice.__value__);
                $scope.SimilarItems[i].shippingCost.__value__=parseFloat($scope.SimilarItems[i].shippingCost.__value__);
                $scope.SimilarItems[i].timeLeft=parseFloat($scope.SimilarItems[i].timeLeft.substring($scope.SimilarItems[i].timeLeft.indexOf('P')+1,$scope.SimilarItems[i].timeLeft.indexOf('D')));
                
            }
            
        }),
        function(error){
            console.log("Error in getting similar items",error);

        }


        $scope.googleurl="http://csci-hw-8-nodejs.appspot.com/google?title="+encodeURI(data.title[0]);

        $http.get($scope.googleurl).then(function(response){
            $scope.googleImg=response.data.items;
        }),
        function(error){
            console.log("Error in finding google images");
        };

        $scope.showprogress=false;
    };
    // 
    $scope.showDetailswithID=function(itemId,title){
        $scope.showprogress=true;
        
        $scope.ItemDetails=[];
        $scope.SimilarItems=[];
        $scope.googleImg=undefined;
        $scope.fiveTabs="product";
        $scope.url2="http://csci-hw-8-nodejs.appspot.com/api2?itemId="+itemId[0];
        $http.get($scope.url2).then(function(response){
            $scope.showItemDetails=true;
            $scope.ItemDetails=response.data.Item;
        }),
        function(error){
            console.log("Error in getting item details",error);

        };
        $scope.url3="http://csci-hw-8-nodejs.appspot.com/api3?itemId="+itemId[0];
        $http.get($scope.url3).then(function(response){
            $scope.SimilarItems=response.data.getSimilarItemsResponse.itemRecommendations.item;
            for(var i=0;i<$scope.SimilarItems.length;i++)
            {
                $scope.SimilarItems[i].buyItNowPrice.__value__=parseFloat($scope.SimilarItems[i].buyItNowPrice.__value__);
                $scope.SimilarItems[i].shippingCost.__value__=parseFloat($scope.SimilarItems[i].shippingCost.__value__);
                $scope.SimilarItems[i].timeLeft=parseFloat($scope.SimilarItems[i].timeLeft.substring($scope.SimilarItems[i].timeLeft.indexOf('P')+1,$scope.SimilarItems[i].timeLeft.indexOf('D')));
                
            }
            
        }),
        function(error){
            console.log("Error in getting similar items",error);

        }


        $scope.googleurl="http://csci-hw-8-nodejs.appspot.com/google?title="+encodeURI(title);

        $http.get($scope.googleurl).then(function(response){
            $scope.googleImg=response.data.items;
        }),
        function(error){
            console.log("Error in finding google images");
        };
        $scope.showProdsWish=false;
        $scope.showProds=false;
        $scope.showWishList=false;
        $scope.showItemDetails=true;
        $scope.showprogress=false;
    };


    $scope.showDetails=function(item){
        $scope.showprogress=true;
        $scope.showProdsWish=false;
        $scope.showProds=false;
        $scope.showWishList=false;
        $scope.showItemDetails=true;
        $scope.curritem=item;
        $scope.curritem_result=item;
        $scope.fiveTabs="product";
        $scope.url2="http://csci-hw-8-nodejs.appspot.com/api2?itemId="+item.itemId[0];
        $http.get($scope.url2).then(function(response){
            $scope.showItemDetails=true;
            $scope.ItemDetails=response.data.Item;
        }),
        function(error){
            console.log("Error in getting item details",error);

        };
        $scope.url3="http://csci-hw-8-nodejs.appspot.com/api3?itemId="+item.itemId[0];
        $http.get($scope.url3).then(function(response){
            $scope.SimilarItems=response.data.getSimilarItemsResponse.itemRecommendations.item;
            for(var i=0;i<$scope.SimilarItems.length;i++)
            {
                $scope.SimilarItems[i].buyItNowPrice.__value__=parseFloat($scope.SimilarItems[i].buyItNowPrice.__value__);
                $scope.SimilarItems[i].shippingCost.__value__=parseFloat($scope.SimilarItems[i].shippingCost.__value__);
                $scope.SimilarItems[i].timeLeft=parseFloat($scope.SimilarItems[i].timeLeft.substring($scope.SimilarItems[i].timeLeft.indexOf('P')+1,$scope.SimilarItems[i].timeLeft.indexOf('D')));
                
            }
            
        }),
        function(error){
            console.log("Error in getting similar items",error);

        }


        $scope.googleurl="http://csci-hw-8-nodejs.appspot.com/google?title="+encodeURI($scope.curritem.title[0]);

        $http.get($scope.googleurl).then(function(response){
            $scope.googleImg=response.data.items;
        }),
        function(error){
            console.log("Error in finding google images");
        };

        $scope.showprogress=false;
    };
    $scope.after = function () {
        if($scope.searchProductsList.length > 10*($scope.pageNumber + 1)) {
            $scope.currpageprod = {};
            $scope.pageNumber += 1;
            $scope.searchId = $scope.pageNumber*10;
            var Products_len=$scope.searchProductsList.length;
            var i=0;

            while((Products_len-$scope.searchId)>0 && i<10){
                $scope.currpageprod[($scope.searchId+i+1)]=$scope.searchProductsList[($scope.searchId+i)];
                Products_len--;
                i++;
            }
        }
    };

    $scope.before = function () {
        $scope.currpageprod = {};
        $scope.pageNumber -= 1;
        $scope.searchId = $scope.pageNumber*10;
        var Products_len=$scope.searchProductsList.length;
        var i=0;
        while((Products_len-$scope.searchId)>0 && i<10){
            $scope.currpageprod[($scope.searchId+i+1)]=$scope.searchProductsList[($scope.searchId+i)];
            Products_len--;
            i++;
        }
    };

    $scope.showPage = function (num) {
        $scope.currpageprod = {};
        $scope.pageNumber = parseInt(num, 10)-1;
        $scope.searchId = $scope.pageNumber*10;
        var Products_len=$scope.searchProductsList.length;
        var i=0;
        while((Products_len-$scope.searchId)>0 && i<10){
            $scope.currpageprod[($scope.searchId+i+1)]=$scope.searchProductsList[($scope.searchId+i)];
            Products_len--;
            i++;
        }
    };
    $scope.contains=function(num){
        num=parseInt(num,10)-1;
        if($scope.searchProductsList===undefined || $scope.searchProductsList.length<(num*10))
        {
            return 0;
        }
        return 1;
    };

    $scope.moreitems=function()
    {
        $scope.showmore=true;
        $scope.limit=$scope.SimilarItems.length;
    };
    $scope.lessitems=function()
    {
        $scope.showmore=false;
        $scope.limit=5;
    };

    $scope.sim_drop1=function(similardrop1){
        if (similardrop1 !== "default") {
            $scope.sortdisable = false;
        }

        $scope.orderType = similardrop1;

        if ($scope.orderType == "default") {
            $scope.order = null;
            $scope.sortdisable = true;

        }

        if ($scope.orderType == "name" && $scope.orderType == "asc") {
            $scope.order ="title";

        }
        if ($scope.orderType == "name" && $scope.orderSort === "desc") {
            $scope.order = "-title";
        }

        if ($scope.orderType == "days" && $scope.orderSort == "asc") {
            $scope.order = "timeLeft";
        }
        if ($scope.orderType == "days" && $scope.orderSort == "desc") {
            $scope.order = "-timeLeft";
        }

        if ($scope.orderType == "price" && $scope.orderSort == "asc") {
            $scope.order = 'buyItNowPrice.__value__';

        }
        if ($scope.orderType == "price" && $scope.orderSort == "desc") {
            $scope.order = '-buyItNowPrice.__value__';
        }

        if ($scope.orderType == "cost" && $scope.orderSort == "asc") {
            $scope.order = "shippingCost.__value__";

        }
        if ($scope.orderType == "cost" && $scope.orderSort == "desc") {
            $scope.order = "-shippingCost.__value__";
        }
    };
    $scope.sim_drop2=function(similardrop2){
        $scope.orderSort = similardrop2;

        if ($scope.orderType == "default") {
            $scope.order = null;

        }

        if ($scope.orderType == "name" && $scope.orderSort == "asc") {
            $scope.order = "title";

        }
        if ($scope.orderType == "name" && $scope.orderSort == "desc") {
            $scope.order = "-title";
        }

        if ($scope.orderType == "days" && $scope.orderSort == "asc") {
            $scope.order = "timeLeft";

        }
        if ($scope.orderType == "days" && $scope.orderSort == "desc") {
            $scope.order = "-timeLeft";
        }

        if ($scope.orderType == "price" && $scope.orderSort == "asc") {
            $scope.order = 'buyItNowPrice.__value__';

        }
        if ($scope.orderType == "price" && $scope.orderSort == "desc") {
            $scope.order = '-buyItNowPrice.__value__';
        }

        if ($scope.orderType == "cost" && $scope.orderSort == "asc") {
            $scope.order = "shippingCost.__value__";

        }
        if ($scope.orderType == "cost" && $scope.orderSort == "desc") {
            $scope.order = "-shippingCost.__value__";
        }
    };
    $scope.showProducts=function()
    {
        $scope.bin=0;
        $scope.showProdsWish=true;
        $scope.showProds=true;
        $scope.showItemDetails=false;
        $scope.showWishList=false;
    }
    $scope.showList=function(){
        $scope.showprogress=true;

        if($scope.bin==0)
        {
        $scope.showItemDetails=false;
        $scope.showWishList=false;
        $scope.showProdsWish=true;

        $scope.showProds=true;
     
        }
        else{
            $scope.showItemDetails=false;
            $scope.showProds=false;

            $scope.showProdsWish=true;
            $scope.showWishList=true; 
        }

        $scope.showprogress=false;
    };
    $scope.showItems_Det=function(){
        $scope.showProds=false;
        $scope.showWishList=false;

        $scope.showProdsWish=false;
        $scope.showItemDetails=true;
    };

    $scope.showWish=function()
    {
        $scope.showItemDetails=false;
        $scope.showProds=false;
        $scope.showProdsWish=true;
        $scope.showWishList=true;
        $scope.bin=1;
        $scope.activeTab="not";
    }

    $scope.suggestions = function(searchText) {
        $scope.searchText = searchText;
        $scope.auto="http://csci-hw-8-nodejs.appspot.com/auto?zip="+$scope.searchText;
        return $http.get($scope.auto).then(function(response){
            var result = [];
            if (response.data.postalCodes !== undefined) {
                for (var i = 0; i < response.data.postalCodes.length; i++) {
                    result.push(response.data.postalCodes[i].postalCode)
                }
            }
            // result.push("abc");
            return result;
        });


       
    };


    $scope.postToFacebook=function(name,price,furl)
    {
        var fcburl="https://www.facebook.com/dialog/feed?%20app_id=2615049238524141&%20link="+furl+"&display=popup&quote=";
        fcburl+="Buy "+name+" at $"+price+" from link below";
        window.open(fcburl);
    };



    
}]);