'use strict';
const express = require('express');
const request = require('request');
const http = require('http');
const cors= require('cors');
var path = require('path');
const app = express();
app.use(cors());
// app.use(express.static('static'));
app.use(express.static(path.join(__dirname, 'public')));
app.get('/api', (req, res) => {
  var index=0;
  console.log(req.query);
  var url1="http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=MeetChet-km-PRD-d16e2f5cf-15e64fa4&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&paginationInput.entriesPerPage=50&keywords="+req.query.keyword;
  if(req.query.category)
  {
    url1+="&categoryId="+req.query.category;
  }
  url1+="&buyerPostalCode="+req.query.zipcode;
  if(req.query.maxdistance)
  {
    url1+="&itemFilter("+index+").name=MaxDistance&itemFilter("+index+").value="+req.query.maxdistance;
    index+=1;
  }
  if(req.query.free!='undefined')
  {
    url1+="&itemFilter("+index+").name=FreeShippingOnly&itemFilter("+index+").value="+req.query.free;
    index+=1;

  }
  if(req.query.local!='undefined')
  {
    url1+="&itemFilter("+index+").name=LocalPickupOnly&itemFilter("+index+").value="+req.query.local;
    index+=1;
  }
  url1+="&itemFilter("+index+").name=HideDuplicateItems&itemFilter("+index+").value=true";
  index+=1;

  if(req.query.new!='undefined' || req.query.used!='undefined' || req.query.unspecified!='undefined')
  {
    url1+="&itemFilter("+index+").name=Condition";
    var value=0;
    if(req.query.new!='undefined' && req.query.new!='false')
    {
        url1+="&itemFilter("+index+").value("+value+")=New";
        value+=1;
    }
    if(req.query.used!='undefined' && req.query.used!='false')
    {
        url1+="&itemFilter("+index+").value("+value+")=Used";
        value+=1;
    }
    if(req.query.unspecified!='undefined'&& req.query.unspecified!='false')
    {
        url1+="&itemFilter("+index+").value("+value+")=Unspecified";
        value+=1;
    }
    index+=1;
  }
  url1+="&outputSelector(0)=SellerInfo&outputSelector(1)=StoreInfo";

  console.log(url1);
  getProd(url1,res);
    
});


app.get('/', (req, res) => {
  res.send("Hello World")
});

app.get('/api2', (req, res) => {
  // console.log(req.params);
  console.log(req.query);
  var url2="http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=JSON&appid=MeetChet-km-PRD-d16e2f5cf-15e64fa4&siteid=0&version=967&ItemID=";
  url2+=req.query.itemId;
  url2+="&IncludeSelector=Description,Details,ItemSpecifics";

  console.log(url2);
  getProd(url2,res);
});
app.get('/api3', (req, res) => {
  // console.log(req.params);
  console.log(req.query);
  var url3="http://svcs.ebay.com/MerchandisingService?OPERATION-NAME=getSimilarItems&SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.1.0&CONSUMER-ID=MeetChet-km-PRD-d16e2f5cf-15e64fa4&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&itemId=";
  url3+=req.query.itemId;
  url3+="&maxResults=20";

  console.log(url3);
  getProd(url3,res);
});

app.get('/google',(req,res) => {
  console.log("hey from google");
  console.log(req.query.title);
  var url4 = "https://www.googleapis.com/customsearch/v1?q=" + req.query.title +
        "&cx=007206620525757196192:k-g5rbjdc3c&imgSize=huge&imgType=news&num=8&" +
        "searchType=image&key=AIzaSyAeXiiwO2tN0L-_qaiv14R-ZqL9qtFW4Cg";
  // var googleurl="https://www.googleapis.com/customsearch/v1?q="
  // googleurl+=req.query.title;
  // googleurl+="&cx=000431876262227656688:buv_tppwjac&imgSize=huge&imgType=news&nums=8&searchType=image&key=AIzaSyCOOZeWnk3YC3CkgnhkuVBe1DUUYPtBLxI";
  console.log(url4);
  getProd(url4,res);
});

app.get('/auto', (req, res) => {
  // console.log(req.params);
  console.log("Zip code auto");
  console.log(req.query.zip);
  var auto_url="http://api.geonames.org/postalCodeSearchJSON?postalcode_startsWith="+req.query.zip+"&username=gadoya&country=US&maxRows=5";
  
  console.log(auto_url);
  getProd(auto_url,res);
});

 const PORT = process.env.PORT || 8080;
 app.listen(PORT, () => {
   console.log(`App listening on port ${PORT}`);
   console.log('Press Ctrl+C to quit.');
 });

 function getProd(url,res){
   request(url,function(error,response,body){
      var result=JSON.parse(response.body);
      // console.log(result);
      var resultObj={};
      // resultObj
      res.send(result);
   });
 }


  