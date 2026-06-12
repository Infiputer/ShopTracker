 <link rel="icon" href="favicon.ico?v1" type="image/x-icon" />
<link rel="shortcut icon" href="favicon.ico?v1" type="image/x-icon" />
<title>Shop Tracker</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300&display=swap" rel="stylesheet">
<style>
	table {
	  font-family: 'Fredoka', sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}
	
	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}
	
	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	input, select, button{
		border-color:black;
		font-family: 'Fredoka', sans-serif;
	}
	h1, body{
		font-family: 'Fredoka', sans-serif;
	}
	#logo{
		text-align: right;
	}
	#title{
		font-size: 40px;
	}
</style>
<center><h1 id = "title">Shop Tracker</h1></center>
<hr>
<h1>Buy an item</h1>
<table>
  <tr>
<td>Item: </td><td><input placeholder = "Item" id = "itemNameBuy"><br></td>
    </tr>
  <tr>
<td>Buying Price per Unit: </td><td><input type = "number" placeholder = "Buying price"
	id = "buyingPrice" min = "0"><br></td>
    </tr>
    <tr>
<td>Selling Price per Unit: </td><td><input type = "number" placeholder = "Selling price"
	id = "sellingPrice" min = "0"><br></td>
      </tr>
      <tr>
<td>Quantity: </td><td><input type = "number" placeholder = "Quantity"
	id = "buyAmount" min = "0"><br></td>
        </tr>
	<tr>
		<td>
Supplier:</td> <td><input placeholder = "Supplier name" id = "supplier"><br></td>
	</tr>
</table>
<button onclick = "buy()">Buy</button><br>
<hr>
<h1>Sell an item</h1>
<select id = "selectSellItem"></select><br>
<input placeholder="Quantity Selling"
	id = "amountSelling" type = "number">
<button onclick = "sell()">Sell</button><br>
<hr>
<h1>Change selling price of an item</h1>
<select id = "selectSellItemChange"></select><br>
<input placeholder="New Price"
	id = "changePrice" min = "0" type = "number">
<button onclick = "changePrice()">Change Price</button><br>
<hr>
<table id = "itemInfo"></table>
<hr>
<table id = "soldItems"></table>
<hr>
<table id = "boughtItems"></table>

<script>
  boughtTable = [];
	function firstUpper(s){
      return s[0].toUpperCase()+s.substring(1).toLowerCase()
  }
	function mathmap(x, in_min, in_max, out_min, out_max) {
			return (x - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
	}
	function graph(graph){
		cwidth = canvas.width;
		cheight = canvas.height;
		pointSpace = cwidth/graph.length;
		gmax=Math.max(...graph)+1;
		gmin=Math.min(...graph)-1;
		prevX=0;
		prevY=0;
		for(i in graph){
			ctx.beginPath();
			ctx.arc(i*pointSpace,
				mathmap(graph[i],	gmax,gmin,0,cheight),
			1, 0, 2 * Math.PI);
			ctx.stroke();
			ctx.moveTo((i-1)*pointSpace, prevY);
			ctx.lineTo(i*pointSpace,
				mathmap(graph[i],	gmax,gmin,0,cheight));
			prevY=mathmap(graph[i],	gmax,gmin,0,cheight);
			ctx.stroke();
		}
	}
	if(localStorage["products"]==undefined){
		localStorage["products"] = "{}";
		localStorage["soldTable"] = "[]";
	}
	try{
		products = JSON.parse(localStorage["products"]);
		soldTable = JSON.parse(localStorage["soldTable"]);
		boughtTable = JSON.parse(localStorage["boughtTable"]);
	}
	catch(e){
		console.log(e);
		localStorage.clear();
		products = {};
		soldTable=[];
	}
	function checkData(n){
		for(i in n){
			if(document.getElementById(n[i]).value == "" || document.getElementById(n[i]).value.trim()==""){
				return false;
			}
		}
		return true;
	}
	function writeData(){
		localStorage["products"]=JSON.stringify(products);
		localStorage["soldTable"]=JSON.stringify(soldTable);
		localStorage["boughtTable"]=JSON.stringify(boughtTable);
	}
	function buy(){
		if(!checkData(["itemNameBuy", "sellingPrice", "buyingPrice", "buyAmount"])){
      alert("Fill in all fields")
			return;
		}
		itemBought = firstUpper(document.getElementById("itemNameBuy").value);
    if(!confirm("Proceed with buying "+itemBought+"?")){
      return;
    }
		if(products[itemBought]==undefined){
			products[itemBought]={}
		}
		products[itemBought]["sellPrice"]=document.getElementById("sellingPrice").value;
		products[itemBought]["buyPrice"]=document.getElementById("buyingPrice").value;
		products[itemBought]["supplier"]=document.getElementById("supplier").value;
		let current = new Date();
		let cDate = current.getDate()+"-"+(current.getMonth() + 1)+"-"+current.getFullYear();
		let cTime = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
		let dateTime = cDate + ' ' + cTime;
		boughtTable.push({
			"item":itemBought,
			"supplier":document.getElementById("supplier").value,
			"date":dateTime,
			"buyPrice":document.getElementById("buyingPrice").value,
			"amount":document.getElementById("buyAmount").value,
			"left":document.getElementById("buyAmount").value
		});
		showBoughtTable()
		if(products[itemBought]["amount"]==undefined){
			products[itemBought]["amount"]=parseInt(document.getElementById("buyAmount").value);
		}else{
			products[itemBought]["amount"]+=parseInt(document.getElementById("buyAmount").value);
		}
		showTable();
		changeSellDropdown();
		writeData();
	}
	function changeSellDropdown(){
		document.getElementById("selectSellItem").innerHTML="";
		document.getElementById("selectSellItemChange").innerHTML="";
		for(item in products){
			document.getElementById("selectSellItem").innerHTML+=`
				<option value = "${item}">${item}</select>
			`
			document.getElementById("selectSellItemChange").innerHTML+=`
				<option value = "${item}">${item}</select>
			`
		}
		writeData();
	}
	function changePrice(){
		if(!checkData(["changePrice"])){
			return;
		}
		itemChange = document.getElementById("selectSellItemChange").value;
		newPrice = document.getElementById("changePrice").value;
		products[itemChange]["sellPrice"]=newPrice;
		showTable();
		writeData();
	}

	function setBuyingPrice(){
		priceData = {};
		for(i in boughtTable){
			if(priceData[boughtTable[i]["item"]] == undefined){
				priceData[boughtTable[i]["item"]] = {
					"price":parseInt(boughtTable[i]["buyPrice"])*parseInt(boughtTable[i]["left"]),
					"amount":parseInt(boughtTable[i]["left"])
				};
			}
			else{
				priceData[boughtTable[i]["item"]]["price"]+=parseInt(boughtTable[i]["buyPrice"])*parseInt(boughtTable[i]["left"]);
				priceData[boughtTable[i]["item"]]["amount"]+=parseInt(boughtTable[i]["left"]);
			}
		}
		for(i in priceData){
			products[i]["buyPrice"] = (priceData[i]["price"]/priceData[i]["amount"]).toFixed(2);
		}
	}
	
	function sell(){
		if(!checkData(["amountSelling"])){
			return;
		}
		amountSold = document.getElementById("amountSelling").value;
		itemSold = document.getElementById("selectSellItem").value;
		if(products[itemSold]["amount"]-amountSold<0){
			alert("not enough "+itemSold);
			return;
		}
		products[itemSold]["amount"]-=amountSold;
		showTable()
		amountToRemove = parseInt(amountSold);
		for(i in boughtTable){
			if(boughtTable[i]["item"] == itemSold){
				if(boughtTable[i]["left"]<amountToRemove){
					amountToRemove -= boughtTable[i]["left"];
					boughtTable[i]["left"] = 0;
				}
				else{
					boughtTable[i]["left"] -= amountToRemove;
					amountToRemove=0;
					break;
				}
				if(amountToRemove<1){
					break;
				}
			}
		}
		showBoughtTable()
		let current = new Date();
		let cDate = current.getDate()+"-"+(current.getMonth() + 1)+"-"+current.getFullYear();
		let cTime = current.getHours() + ":" + current.getMinutes() + ":" + current.getSeconds();
		let dateTime = cDate + ' ' + cTime;
		soldTable.push(
			{"item":itemSold,
			 "sellPrice":products[itemSold]["sellPrice"]*amountSold,
			 "buyPrice":products[itemSold]["buyPrice"]*amountSold,
			 "time":dateTime
			}
		)
		showSold();
		writeData();
	}
	function showTable(){
		setBuyingPrice();
    productkeys = Object.keys(products).sort();
		document.getElementById("itemInfo").innerHTML = `
			<tr>
				<th>Item</th>
				<th>Buy Price</th>
				<th>Sell Price</th>
				<th>Quantity</th>
				<th>Supplier</th>
			</tr>`;
		for(x in productkeys){
      i=productkeys[x];
			document.getElementById("itemInfo").innerHTML += `
			  <tr>
			    <td>${i}</td>
			    <td>${products[i]["buyPrice"]}</td>
			    <td>${products[i]["sellPrice"]}</td>
					<td>${products[i]["amount"]}</td>
					<td>${products[i]["supplier"]}</td>
			  </tr>
			`
		}
		writeData();
	}
	function showSold(){
		document.getElementById("soldItems").innerHTML = `
			<tr>
				<th>Item Sold</th>
				<th>Date</th>
				<th>Sold For</th>
				<th>Bought for</th>
				<th>Profit</th>
			</tr>`;
		totalProfit = 0;
		graphData = [];
		dailyProfits = {};
		for(i in soldTable){
			document.getElementById("soldItems").innerHTML+=`
			<tr>
		    <td>${soldTable[i]["item"]}</td>
				<td>${soldTable[i]["time"]}</td>
		    <td>${soldTable[i]["sellPrice"]}</td>
		    <td>${soldTable[i]["buyPrice"]}</td>
				<td>${soldTable[i]["sellPrice"]-soldTable[i]["buyPrice"]}</td>
		  </tr>
			`;
			if(dailyProfits[soldTable[i]["time"].split(" ")[0]]==undefined){
				dailyProfits[soldTable[i]["time"].split(" ")[0]]=0;
			}
			dailyProfits[soldTable[i]["time"].split(" ")[0]]+=soldTable[i]["sellPrice"]-soldTable[i]["buyPrice"];
			totalProfit+=soldTable[i]["sellPrice"]-soldTable[i]["buyPrice"];
		}
		document.getElementById("soldItems").innerHTML+=`
			<tr>
		    <td>N/A</td>
		    <td>N/A</td>
		    <td>N/A</td>
				<td>N/A</td>
				<td>${totalProfit}</td>
		  </tr>
			`;
	}
  function showBoughtTable(){
		document.getElementById("boughtItems").innerHTML = `
			<tr>
				<th>Item</th>
				<th>Buy Price</th>
				<th>Quantity</th>
				<th>Left</th>
				<th>Supplier</th>
        <th>Date</th>
			</tr>`;
		for(x in boughtTable){
			document.getElementById("boughtItems").innerHTML += `
			  <tr>
			    <td>${boughtTable[x]["item"]}</td>
			    <td>${boughtTable[x]["buyPrice"]}</td>
					<td>${boughtTable[x]["amount"]}</td>
					<td>${boughtTable[x]["left"]}</td>
					<td>${boughtTable[x]["supplier"]}</td>
          <td>${boughtTable[x]["date"]}</td>
			  </tr>
			`
		}
		writeData();
	}
	showSold();
	showTable();
	changeSellDropdown();
  showBoughtTable();
</script>
<img src = "favicon.ico" id = "logo">
