<html>
<head>
    <meta charset="UTF-8">
    <style type="text/css">
        .divstyle
        {
            width:600px;
            height:280px;
            border: 4px solid lightgrey;
            text-align: center;
            margin-left: auto;
            margin-right:auto;
            background-color: whitesmoke;
            margin-top:50px;
            /*margin-bottom: :50px;*/
            font-size:14px;
        }
        .divtitle
        {
            padding-top: 5px;            
            margin-top:-20px;
            text-align: center;
            margin-left:10px;
            height: 50px;
            width:580px;
            transform: scale(1,1.2);
            border-bottom: 2px solid lightgrey;
        }
        .buttons
        {
            margin-left: 150px; 
        }
        form
        {
            text-align:left;
            margin-left:15px; 
            line-height: 200%;

        }
        .greyout{
            color:grey;
        }
        
        table {
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
        }

        td, th, tr {
            border: 2px solid #D1D0D1;
            /*padding-left: 5px;*/
        }
        #table3 tr,#table3 td
        {
            border:0px;
        }
        #div4
        {
            border:1px solid grey;
        }
        #no_similar
        {
            width:850px;
            margin:10px;
        }
        a:hover
        {
            color:grey;
            cursor:pointer;
        }
        #zipcodes
        {
            width:100%;
        }
        #zipcodes tr,#zipcodes td
        {
            border:0px;
        }

    </style>
</head>
<body>

<?php
if(isset($_POST['id_submit']))
{

    $url2="http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=JSON&appid=MeetChet-km-PRD-d16e2f5cf-15e64fa4&siteid=0&version=967&ItemID=";
    $url2=$url2.$_POST['api_text'];
    $url2=$url2."&IncludeSelector=Description,Details,ItemSpecifics";
    // echo $url2;
    $js2=file_get_contents($url2);


    $url3="http://svcs.ebay.com/MerchandisingService?OPERATION-NAME=getSimilarItems&SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.1.0&CONSUMER-ID=MeetChet-km-PRD-d16e2f5cf-15e64fa4&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&itemId=";
    $url3=$url3.$_POST['api_text'];
    $url3=$url3."&maxResults=8";
    // echo $url3;
    $js3=file_get_contents($url3);




}

elseif (isset($_POST['res']))
{
    
}
else
{ 
if(isset($_POST['submitButton']))
{

 $url="http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=MeetChet-km-PRD-d16e2f5cf-15e64fa4&RESPONSE-DATA-FORMAT=JSON";
 $index=1;
 
    if(isset($_POST['keyword']))
    {
        $url=$url."&keywords=";
        $val=urlencode($_POST['keyword']);
        $url=$url.$val; 
    }
    $url=$url."&paginationInput.entriesPerPage=20";

    if(($_POST["category"]=="all")||($_POST["category"]=="all2"))
    {

    }
    else
    {
        $url=$url."&categoryId=";
        $url=$url.$_POST["category"];
    }
    if(isset($_POST["enable"]))
    {    
        
        $rad=$_POST['location_button'];
        if($rad=="here")
        {
            $url=$url."&buyerPostalCode=";
            $val=$_POST['hidden_zip_code'];
            $url=$url.$val;
        
        } 
        
        else
        {
            $url=$url."&buyerPostalCode=";
            $val=$_POST['ziptext'];
            $url=$url.$val;
        }

    
    }
    
    $url=$url."&itemFilter(0).name=HideDuplicateItems&itemFilter(0).value=true";

    if(isset($_POST["enable"]))
    {
        $val=10;
        // echo $val;
        if($_POST["miles"]!="")
        {
            $val=$_POST["miles"];
        }
        $url=$url."&itemFilter(".$index.").name=MaxDistance";
        $url=$url."&itemFilter(".$index.").value=";
        $index++;
        // echo $val;
        $url=$url.$val;
        // echo $url;
    }

    // for shipping
    if(isset($_POST["shipping_free"]))
    {
        $url=$url."&itemFilter(".$index.").name=FreeShippingOnly";
        $url=$url."&itemFilter(".$index.").value=true";
        $index++;


    }
    if(isset($_POST["shipping_local"]))
    {
        
        $url=$url."&itemFilter(".$index.").name=LocalPickupOnly";
        $url=$url."&itemFilter(".$index.").value=true";
        $index++;

    }
    // $url=$url."&itemFilter(".$index.").name=HideDuplicatesItems";
    // $url=$url."&itemFilter(".$index.").value=true";
    // $index++;

    if(isset($_POST["condition_not"]) || isset($_POST["condition_used"]) || isset($_POST["condition_new"]))
    {
        $url=$url."&itemFilter(".$index.").name=Condition";
        $value=0;
        if(isset($_POST["condition_not"]))
        {
            $url=$url."&itemFilter(".$index.").value(".$value.")=Unspecified";
            $value++;
        }
        if(isset($_POST["condition_used"]))
        {
            $url=$url."&itemFilter(".$index.").value(".$value.")=Used";
            $value++;
        }
        if(isset($_POST["condition_new"]))
        {
            $url=$url."&itemFilter(".$index.").value(".$value.")=New";
            $value++;
        }
        $index++;

    }
    // echo $url;
    $js=file_get_contents($url);
    // echo $js;

}
}
?>

<script type="application/javascript">
window.onload=function()
{
    if(window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome
        var xmlhttp= new XMLHttpRequest();
    }
    else
    {
        var xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");

    }
        // console.log("Hey there");

    xmlhttp.onload=function()
    {   
        // console.log("Hey there");

        if(xmlhttp.readyState===4) 
        {   
            // console.log("Hey there");


            if(xmlhttp.status===200)
            {   
                // console.log("Hey there");
                jsonObj=JSON.parse(xmlhttp.responseText);
                document.getElementById("hidden_zip_code").value=jsonObj.zip;
                document.getElementById("submitButton").disabled=false;
                // console.log(document.getElementById("hidden_zip_code").value);
            }
        }
        // console.log("Hey there");
    };
    xmlhttp.open("POST","http://ip-api.com/json",true);
    xmlhttp.send();
    fillSearch();
    create_table_1();
    create_table_2();
    create_table_3();


};

    function here_click()
    {
        document.getElementById("zipcode_button").checked=false;
        document.getElementById("ziptext").disabled=true;
    }

    function zipcode_click()
    {
        document.getElementById("here_button").checked=false;
        document.getElementById("ziptext").disabled=false;

    }
    function greyout(enable)
    {
        if(enable.checked)
        {
            
            document.getElementById("miles").disabled=false;
            document.getElementById("here_button").disabled=false;
            document.getElementById("zipcode_button").disabled=false;
            // console.log(document.getElementById("zipcode_button").value)
            if(document.getElementById("zipcode_button").checked)
            {
                document.getElementById("ziptext").disabled=false;

            }
            // document.getElementById("ziptext").disabled=false;
            document.getElementById("miles_label").classList.remove("greyout");
            document.getElementById("here_label").classList.remove("greyout");
        }

        else{
            document.getElementById("ziptext").value="";
            document.getElementById("miles").value="";

            document.getElementById("miles").disabled=true;
            document.getElementById("here_button").checked=true;
            document.getElementById("zipcode_button").checked=false;

            document.getElementById("here_button").disabled=true;

            document.getElementById("zipcode_button").disabled=true;
            document.getElementById("ziptext").disabled=true;


            // document.getElementById("ziptext").disabled=false;
            document.getElementById("miles_label").classList.add("greyout");
            document.getElementById("here_label").classList.add("greyout");
        }
    }

    function fillSearch()
    {
        <?php if(isset($_POST["enable"]))
        {
        ?>

        // console.log("HEYY");
        document.getElementById("enable").checked=true;
        greyout(document.getElementById("enable"));
        document.getElementById("miles").value="<?php echo $_POST['miles'];?>"

        <?php 
            if($_POST["location_button"]=="zip")
            {
                ?>
                document.getElementById("zipcode_button").checked=true;
                greyout(document.getElementById("enable"));

                document.getElementById("ziptext").value="<?php echo $_POST['ziptext']?>"
                <?php
            }

        }

        ?>
      
    }
    
    function clearAll()
    {
        document.getElementById("miles").disabled=true;
        document.getElementById("here_button").disabled=true;
        document.getElementById("zipcode_button").disabled=true;
        document.getElementById("ziptext").disabled=true;
        document.getElementById("keyword").value="";
        document.getElementById("new").checked=false;
        document.getElementById("used").checked=false;
        document.getElementById("unspecified").checked=false;
        document.getElementById("shipping_local").checked=false;
        document.getElementById("shipping_free").checked=false;
        document.getElementById("enable").checked=false;
        greyout(document.getElementById("enable"));
        document.getElementById("category").value="all";

        document.getElementById("miles_label").classList.add("greyout");
        document.getElementById("here_label").classList.add("greyout");
        document.getElementById("div1").style.display="none";
        document.getElementById("div2").style.display="none";
        document.getElementById("seller_msg").style.display="none";
        document.getElementById("div4").style.display="none";
        document.getElementById("img1").style.display="none";
        document.getElementById("img2").style.display="none";

    }

function create_table_1()
{
    var empty=null;
    var search_results1 = <?php
        if ($js == NULL)
            echo "empty";
        else
            echo $js;

        ?>;

    if(search_results1 !== null) 
    {
        var trial=search_results1.findItemsAdvancedResponse[0];
        if(trial.ack[0]==="Success")
        {   
            
            if(("item" in trial.searchResult[0]))
            {
                var result = trial.searchResult[0].item;
                var htmlContent = "<table id='tableOne'>";
                if (result.length === 0) 
                {
                    htmlContent += "<div style='text-align: center; background-color: lightgrey; width:700px; margin-left:auto; margin-right:auto'> No Records has been found</div>";
                } 
                else 
                {
                    
                    htmlContent += "<tr><th width='5%''>Index</th><th width='6%'>Photo</th><th width='60%'>Name</th><th width='5%'>Price</th><th width='5%'>Zip code</th>" +
                        "<th width='10%'>Condition</th><th wdith='10%'>Shipping Option</th></tr>";
                    for (i = 0; i < result.length; ++i) 
                    {
                        htmlContent += "<tr>";
                        htmlContent += "<td style='text-align:left'>" + (i+1) + "</td>";
                        htmlContent +="<td style='text-align:center'>"
                        if(("galleryURL") in result[i])
                        {
                            htmlContent += "<img style='max-height: 90px; width: 100%' alt='N/A' src ='" + result[i].galleryURL + "' >";

                        }
                        else
                        {
                            htmlContent+="N/A";   
                        }
                        htmlContent +="</td>"

                        if(("title") in result[i])
                        {
                            htmlContent += "<td style='padding-left:3px'><a title_id='" +result[i].itemId + "' onClick='api_call_2(this);'>" + result[i].title + "</a></td>";
                        }
                        else
                        {
                            htmlContent+="<td style='text-align:center'>N/A</td>";   

                        }
                        if((("sellingStatus")in result[i]) && (("currentPrice")in result[i].sellingStatus[0]) && (("__value__") in result[i].sellingStatus[0].currentPrice[0]))
                        {
                            
                            htmlContent += "<td style='text-align:left'>$" +result[i].sellingStatus[0].currentPrice[0].__value__ + "</td>";
                        }
                        else
                        {
                            htmlContent+="<td style='text-align:center'>N/A</td>";   

                        }
                        htmlContent +="<td style='text-align:left'>"

                        if(("postalCode") in  result[i])
                        {
                            htmlContent += result[i].postalCode;
                        }
                        else
                        {
                            htmlContent+="N/A";   
                        }
                        htmlContent +="</td>"
                        htmlContent +="<td style='text-align:left'>"

                        if(!("condition"in result[i])) 
                        {
                            htmlContent += "N/A";
                        }
                        else
                        {
                            htmlContent += result[i].condition[0].conditionDisplayName[0];
                        }
                        htmlContent +="</td>"
                        htmlContent +="<td style='text-align:left'>"

                        if(null == result[i].shippingInfo[0])
                        {
                            htmlContent += "N/A";
                        }
                        else if("" === result[i].shippingInfo[0])
                        {
                            htmlContent += "N/A";
                        }
                        else if(!(("shippingServiceCost") in result[i].shippingInfo[0] ))
                        {
                            htmlContent += "N/A";

                        }
                        else
                        {
                            var shipping_val = result[i].shippingInfo[0].shippingServiceCost[0].__value__;
                            if("0.0" === shipping_val)
                                htmlContent += "Free Shipping";
                            else
                                htmlContent += "$" + shipping_val;
                        }
                        htmlContent +="</td>"

                        htmlContent += "</tr>";
                    }
                }

                htmlContent += "</table>";
            }
            else
            {
                var htmlContent = "<div style='text-align: center; background-color: lightgrey; width:700px; margin-left:auto;margin-right:auto'> No Records has been found</div>";
            }
        }
        else
        {
            var error=trial.errorMessage[0].error[0].message[0];
            var htmlContent="<div style='text-align:center; width:700px; background-color:lightgrey;margin-left:auto; margin-right:auto'>"+error+"</div>";
        }
        document.getElementById("div1").style.display="block"
        document.getElementById("div1").innerHTML = htmlContent;
    }
}

function api_call_2(title_node)
{
    document.getElementById("api_text").value=title_node.getAttribute("title_id");
    document.getElementById("id_submit").click();
}


function create_table_2()
{

    var empty=null;
    var search_results2 = <?php
        if ($js2 == NULL)
            echo "empty";
        else
            echo $js2;

        ?>;

    if(search_results2 !== null)
    {
        
        if("Success"==search_results2.Ack)
        {
            var htmlContent2="<h1>Item Details</h1><table style='margin-top:-20px'>";
            api2=search_results2.Item;
            if(("PictureURL") in api2)
            {
                htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Photo</th><td style='padding-left:10px; padding-bottom:10px;padding-top:10px;'><img src='"+api2.PictureURL[0]+"' style='max-height:230px; max-width:370px' alt='NA'></td></tr>";
            }
            if(("Title") in api2)
            {
                htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Title</th><td style='padding-left:10px'>"+api2.Title+"</td></tr>";
            }
            if(("Subtitle") in api2)
            {
                htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>SubTitle</th><td style='padding-left:10px'>"+api2.Subtitle+"</td></tr>";
            }
            if(("CurrentPrice") in api2)
            {
                if(("Value") in api2.CurrentPrice)
                {
                    if(("CurrencyID") in api2.CurrentPrice)
                    {
                        htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Price</th><td style='padding-left:10px'>"+api2.CurrentPrice.Value+" "+api2.CurrentPrice.CurrencyID+"</td></tr>";
                    }
                    else
                    {
                        htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Price</th><td style='padding-left:10px'>"+api2.CurrentPrice.Value+"</td></tr>";
                    }
                }
                
            }
            if((("Location") in api2) && ("PostalCode") in api2)
            {
                htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Title</th><td style='padding-left:10px'>"+api2.Location+","+api2.PostalCode+"</td></tr>";
            }
            else
            {
                if(("Location") in api2)
                {
                    htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Title</th><td style='padding-left:10px'>"+api2.Title+"</td></tr>";
                }
                if(("PostalCode") in api2)
                {
                    htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Title</th><td style='padding-left:10px'>"+api2.PostalCode+"</td></tr>";
                }
            }
            if(("Seller") in api2)
            {
                if(("UserID") in api2.Seller)
                {
                htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>Seller</th><td style='padding-left:10px'>"+api2.Seller.UserID+"</td></tr>";
                }
            }
            if(("ReturnPolicy") in api2)
            {
                htmlContent2+="<tr><th style='text-align:left; padding-left:10px'>ReturnPolicy (US)</th><td style='padding-left:10px'>"+api2.ReturnPolicy.ReturnsAccepted+" within "+api2.ReturnPolicy.ReturnsWithin+"</td></tr>";
            }
            if("ItemSpecifics" in api2)
            {
                var specifics = api2.ItemSpecifics.NameValueList;
                for(i=0; i<specifics.length; i++)
                {
                    htmlContent2 += "<tr><th style='text-align: left; padding-left:10px'>" + specifics[i] .Name + "</th><td style='padding-left:10px'>" +
                        specifics[i].Value + "</td></tr>";
                }
            }
            else
            {
                htmlContent2 += "<tr><th style='text-align: left; padding-left:10px; width:180px;'>No Detail Info from Seller" + "</th><td style='padding-left:10px; background-color:lightgrey'>" + "</td></tr>";
            }
            if("Description" in api2)
            {
                if(api2.Description)
                {
                    document.getElementById("seller_msg").srcdoc=api2.Description;
                }
                else
                {
                    document.getElementById("seller_msg").srcdoc="<html><body><div style='text-align:center; background-color:lightgrey; width:700px; margin-left:auto;margin-right:auto'>"+"<p>No Seller Message Found</p></div></body></html>";
                }
            }
            htmlContent2+="</table>";
        }
        else
        {
            var error_msg = search_results2.Errors[0].LongMessage;
            var htmlContent2 = "<div style='text-align: center; background-color: lightgray;margin-top:20px;'>" +"<span>"+error_msg+"</span></div>";
            document.getElementById("seller_msg").srcdoc="<html><body><div style='text-align:center; background-color:lightgrey; width:700px; margin-left:auto; margin-right:auto'>"+"<p>No Seller Message Found</p></div></body></html>";

        }
        document.getElementById("div1").style.display="none";
        document.getElementById("div2").style.display="block";

        document.getElementById("div2").innerHTML = htmlContent2;
        document.getElementById("img1").style.display="block";
        document.getElementById("img2").style.display="block";
    
    } 


}

function create_table_3()
{
    var empty=null;
    var search_results3 = <?php
        if ($js3 == NULL)
            echo "empty";
        else
            echo $js3;

        ?>;

    if(search_results3 !== null)
    {
        if(search_results3.getSimilarItemsResponse.ack!=="Success")
        {
            var htmlContent3="<div style='margin-left:auto; margin-right:auto; text-align:center; width:700px; background-color:lightgrey;'>"+search_results3.getSimilarItemsResponse.Errors[0].LongMessage+"</div>";

        }
        else
        {
            api3=search_results3.getSimilarItemsResponse.itemRecommendations.item;
            if(api3.length===0)
            {
                var htmlContent3 = "<table id='no_similar'><tr style='text-align:center; border:1px solid grey'><td><b>No similar items has been found</b></td></tr><table>";
            }
            else
            {
                var htmlContent3="<table id='table3'>";
                htmlContent3+="<tr>"
                for(i=0;i<api3.length;i++)
                {
                    htmlContent3+="<td style='text-align:center;padding-left:20px;padding-right:20px'><img src='"+api3[i].imageURL+"'></td>";
                }
                htmlContent3+="</tr><tr>"
                for(i=0;i<api3.length;i++)
                {
                    htmlContent3+="<td style='text-align:center;padding-left:20px;padding-right:20px'><a title_id='"+api3[i].itemId+"'onClick='api_call_2(this);'>"+api3[i].title+"</td>";
                }
                htmlContent3+="</tr><tr>"
                for(i=0;i<api3.length;i++)
                {
                    htmlContent3+="<td style='text-align:center;padding-left:20px;padding-right:20px'>"+"$<b>"+api3[i].buyItNowPrice.__value__+"</b></td>";
                }
                htmlContent3+="</tr>";

                htmlContent3+="</table>";
            }
        }
        document.getElementById("div4").innerHTML=htmlContent3;


    }
}

function toggleimg1(image)
{
    
    if(document.getElementById("seller_msg").style.display=='none')
    {
        if(document.getElementById("div4").style.display=='block')
        {
            document.getElementById("image2").src="http://csci571.com/hw/hw6/images/arrow_down.png";
            document.getElementById("sellerSpan2").innerText="Click to show Similar Items";
            document.getElementById("div4").style.display="none";
        }

        document.getElementById("image1").src="http://csci571.com/hw/hw6/images/arrow_up.png";
        document.getElementById("sellerSpan1").innerText="Click to Hide Seller message";
        document.getElementById("seller_msg").style.display="block";
        document.getElementById("seller_msg").style.height=Math.max(document.getElementById("seller_msg").contentWindow.document.body.offsetHeight,document.getElementById("seller_msg").contentWindow.document.documentElement.offsetHeight)+"px";

    }
    else if(document.getElementById("seller_msg").style.display=="block")
    {
        document.getElementById("image1").src="http://csci571.com/hw/hw6/images/arrow_down.png";
        document.getElementById("sellerSpan1").innerText="Click to show Similar Items";
        document.getElementById("seller_msg").style.display="none";
    }


}
function toggleimg2(image)
{
    
    if(document.getElementById("div4").style.display=="none")
    {
        if(document.getElementById("seller_msg").style.display=="block")
        {
            document.getElementById("image1").src="http://csci571.com/hw/hw6/images/arrow_down.png";
            document.getElementById("sellerSpan1").innerText="Click to show Similar Items";
            document.getElementById("seller_msg").style.display="none";
        }

        document.getElementById("image2").src="http://csci571.com/hw/hw6/images/arrow_up.png";
        document.getElementById("sellerSpan2").innerText="Click to Hide Seller message";
        document.getElementById("div4").style.display="block";
        

    }
    else if(document.getElementById("div4").style.display=="block")
    {
        document.getElementById("image2").src="http://csci571.com/hw/hw6/images/arrow_down.png";
        document.getElementById("sellerSpan2").innerText="Click to show Similar Items";
        document.getElementById("div4").style.display="none";
    }


}
</script>



<div class="divstyle">
    <div class="divtitle"> <h2><i>Product Search</i></h2> 
    </div><br/>
    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>" accept-charset="UTF-8" id="searchForm">
        <b>Keyword </b><input type="text" name="keyword" id="keyword" value="<?php if(isset($_POST['keyword'])) echo $_POST['keyword']?>"required autocomplete="off"> <br>
        <b>Category</b>
        <select name="category" id="category">
            <option value="all" <?php if(isset($_POST['category']) && $_POST['category']=='all') echo "selected='selected'"?>>All Categories</option>
            <option value="all2" disabled>-------------------------------</option>
            <option value=550 <?php if(isset($_POST['category']) && $_POST['category']==550) echo "selected='selected'"?>>Art</option>
            <option value=2984 <?php if(isset($_POST['category']) && $_POST['category']==2984) echo "selected='selected'"?>>Baby</option>
            <option value=267 <?php if(isset($_POST['category']) && $_POST['category']==267) echo "selected='selected'"?>>Books</option>
            <option value=11450 <?php if(isset($_POST['category']) && $_POST['category']==11450) echo "selected='selected'"?>>Clothing, Shoes & Accessories</option>
            <option value=58058 <?php if(isset($_POST['category']) && $_POST['category']==58058) echo "selected='selected'"?>>Computer/Tablets & Networking</option>
            <option value=26395 <?php if(isset($_POST['category']) && $_POST['category']==26395) echo "selected='selected'"?>>Health & Beauty</option>
            <option value=11233 <?php if(isset($_POST['category']) && $_POST['category']==11233) echo "selected='selected'"?>>Music</option>
            <option value=1249 <?php if(isset($_POST['category']) && $_POST['category']==1249) echo "selected='selected'"?>>Video Games and Consoles</option>
            
        </select>
        <br>
        <b>Conditions </b><input type="checkbox" name="condition_new"  id="new" <?php if(isset($_POST['condition_new'])) echo "checked='checked'";?>/>New
        <input type="checkbox" name="condition_used"  id="used" <?php if(isset($_POST['condition_used'])) echo "checked='checked'";?>/>Used
        <input type="checkbox" name="condition_not"  id="unspecified" <?php if(isset($_POST['condition_not'])) echo "checked='checked'";?>/>Unspecified
        
        <br>
        <b>Shipping Options </b><input type="checkbox" name="shipping_local" placeholder="10" id="shipping_local" <?php if(isset($_POST['shipping_local'])) echo "checked='checked'";?>/>Local Pickup
        <input type="checkbox" name="shipping_free" placeholder="10" id="shipping_free" <?php if(isset($_POST['shipping_free'])) echo "checked='checked'";?>/>Free Shipping

        <br>
        <table id="zipcodes"><tr><td style="width:55%">
        <input type="checkbox" name="enable" id="enable" onchange="greyout(this)" />Enable Nearby Search
        <span>

        <input type="text" name="miles" id="miles" placeholder="10" size="5" disabled autocomplete="off"/>
        <label id="miles_label" name="miles_label" class="greyout">Miles from</label>
            </td><td>
            <input type="radio" name="location_button" value="here" onclick="here_click()" id="here_button" checked disabled>
            <label id="here_label" name="here_label" class="greyout">Here</label> <br></td></tr>
            
            <tr><td></td><td>
            <input type="radio" name="location_button" value="zip" onclick="zipcode_click()" id="zipcode_button" disabled>
            <input type="text" name="ziptext" placeholder="location" id="ziptext" required  disabled autocomplete="off"/>
        </td>
        </tr></table>
        </span>
        <br>
        <div class="buttons">
            <input type="submit" value="Search" id="submitButton" name="submitButton" disabled>
            <input type="button" value="Clear" onclick="clearAll()" justify-content="center" name="res" id="res">
        </div>
        <input type="text" name="hidden_zip_code" id="hidden_zip_code" style="display: none;"/>
        <input type="text" name="api_text" id="api_text" style="display:none"/>
        <input type="submit" name="id_submit" id="id_submit" style="display:none;">
    </form>
</div>

<div id="div1" style="margin-top: 20px; display: none"></div>

<div id="div2" style="width:700px; margin-left: auto;margin-right: auto; text-align: center;display: none">

</div>

<div id="img1" style="text-align:center;display: none">
<span id="sellerSpan1" style="font-size:12; color:grey; "> Click here to show seller message</span><br/>
<img src="http://csci571.com/hw/hw6/images/arrow_down.png" id="image1" style="width:4%; height:3%; margin-left: auto; margin-right: auto;" onclick="toggleimg1(this) "/>
</div>
<iframe id="seller_msg" scrolling="no" style=" width:85%;display: none; margin-left: auto;margin-right: auto; border:none;text-align: center;"></iframe>

<div id="img2" style="text-align:center; display: none">
<span id="sellerSpan2" style="font-size:12; color:grey; "> Click here to show Similar Items</span><br/>
<img src="http://csci571.com/hw/hw6/images/arrow_down.png" id="image2"style="width:4%; height:3%; margin-left: auto; margin-right: auto;" onclick="toggleimg2(this)" />
</div>
<div id="div4" style="overflow-x: scroll; width:800px ;margin-top:40px; margin-left:auto; margin-right:auto;display: none">
</div>
</body>
</html>
