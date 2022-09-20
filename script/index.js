let xtp = new XMLHttpRequest(),
earnings = '',
date = new Date(),
year = date.getFullYear(),
productHTML = ``
getEarnings(date,date);
let de = earnings['d'],
me = earnings['m'],
we = earnings['w'],
ye = earnings['y'];
function getProductImage(pname){
    let image = '';
    xtp.open('get','../api/getPImage.php?pname='+pname,false);
    xtp.onreadystatechange = () => {
        if(xtp.readyState == 4 && xtp.status == 200){
            image = xtp.responseText;
        }
    }
    xtp.send();
    return image;
}
async function getLiraRate(){
    fetch('../api/tr.php',{
        method: 'GET',
        headers:{
            'X-Requested-With': "XMLHttpRequest"
        }
    }).then((response)=>{
            return response.json();
    }).then((data)=>{
        data = JSON.parse(JSON.stringify(data));
        let rateC = document.getElementById('khod_latest_rates');
        rateC.innerHTML = `<div class="col">
                            <p>Black Market</p>
                            <input type='text' class="form-control bm" disabled value="${data['bm']}">
                        </div>
                        <div class="col">
                            <p>Sayrafa</p>
                            <input type='text' class="form-control sayrafa" disabled value="${data['sayrafa']}">
                        </div>
                        <div class="col">
                            <p>OMT</p>
                            <input type='text' class="form-control omt" disabled value="36000">
                        </div>`
        });
}

function addP(){
    let oldcur = localStorage.getItem('showcur');
    let pform = document.getElementById('pform'),
    plist = document.getElementById('plist'),
    pname = pform.children[1],
    pprice = pform.children[3].children[0];
    quantity = pform.children[2].children[0].value;
    if(pname.value === null || pname.value == "" || pprice.value == null || pprice.value == 0 || quantity == null || quantity == 0){
        return;
    }
    xtp.open('get','../api/addP.php?pname='+pname.value+'&pprice='+convertPrice(pprice.value,oldcur,'USD')+'&quantity='+quantity);
    xtp.onreadystatechange = () => {
        if(xtp.readyState == 4 && xtp.status == 200){
            if(!isNaN(xtp.responseText)){
                let node = document.createElement('div');
                node.setAttribute('class','col-lg-4 col-sm-12 col-xs-12 p-2 plistItem');
                node.setAttribute('onclick','showSellTool(this)');
                let id = xtp.responseText;
                node.innerHTML = `
                <div class="col">
                                <img src="${getProductImage(pname.value)}" class="productIcon">
                            </div>
                                <div class="col">
                                    <p>Type</p>
                                    <input type="text" class="form-control" value="${pname.value}" disabled >
                                </div>
                                <div class="col">
                                    <p>Quantity</p>
                                    <input type="text" class="form-control" disabled value=${quantity}>
                                </div>
                                <div class="row d-flex m-0 p-0">
                                    <div class="col-lg-12">
                                        <p>Price</p>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 p-0 m-0">
                                        <input type="text" class="form-control" style="float:left" disabled value='${convertPrice(pprice.value,oldcur,'USD')}'>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 p-0 m-0">
                                    <select type="text" class="form-select" onmousedown=localStorage.setItem("showoldcur",this.value) onchange="setPriceFilter(this,this.parentNode.parentNode.children[1].children[0],localStorage.getItem(\'showoldcur\'))">
                                        <option value="USD" selected>USD</option>
                                        <option value="sayrafa">LBP (Sayrafa)</option>
                                        <option value="bm">LBP (Black Market)</option>
                                        <option value="omt">LBP (OMT)</option>
                                    </select>
                                    </div>
                                </div>
                                <input type="text" disabled hidden value="${id}">`;
            let income = document.getElementsByClassName('expense')[0],
            netI = document.getElementsByClassName('net')[0],
            net = parseFloat(netI.innerText);
            net -= parseFloat(convertPrice(pprice.value,oldcur,'USD')*quantity);
            netI.innerText = net+' USD';
            income.innerHTML = (parseFloat(income.innerText)+parseFloat(convertPrice(pprice.value,oldcur,'USD')*quantity))+" USD";
                if(plist.children[1].tagName == 'p'){
                    plist.children[1].outerHTML = '';
                }
                plist.appendChild(node);
                notify('Product added to stack','green');
            }else{
                console.log(xtp.responseText);
            }
        }
    }
    xtp.send();
}
async function getEarnings(date1,date2){
    let dat = new Date(date1),
    y1 = dat.getFullYear(),
    m1 = dat.getMonth()+1,
    d1 = dat.getDate();
    dat = new Date(date2),
    y2 = dat.getFullYear(),
    m2 = dat.getMonth()+1,
    d2 = dat.getDate();
    await fetch('../api/getEarnings.php?y1='+y1+'&m1='+m1+'&d1='+d1+'&y2='+y2+'&m2='+m2+'&d2='+d2,{
            method: "GET",
            headers:{
                'X-Requested-With': "XMLHttpRequest"
            }
        }).then((response) => {
            return response.json();
        }).then((response) => {
            earnings = JSON.stringify(response)
        });
    
    /*xtp.open('get','../api/getEarnings.php?y1='+y1+'&m1='+m1+'&d1='+d1+'&y2='+y2+'&m2='+m2+'&d2='+d2,false);
    xtp.onreadystatechange = () => {
        if(xtp.readyState == 4 && xtp.status == 200){
                earnings = xtp.responseText;
        }
    }
    xtp.send();*/
}
function load(object){
    object.innerHTML = '<h5>Loading Prices</h5><div class="loadingContainer"><p class="loading"></p></div>';
}
function stopLoading(){
    document.getElementsByClassName('loading')[0].style.backgroundColor = 'transparent';
}
function sellP(object){
    let id = localStorage.getItem('id'),
    plist = document.getElementById('plist'),
    selltools = document.getElementsByClassName('sellTools')[0];
    let avq = selltools.parentNode.children[2].children[1].value,
    quantity = selltools.children[0].value,
    price = selltools.children[1].value,
    earningsI = document.getElementsByClassName('earningsInp')[0],
    sales = document.getElementsByClassName('salesInp')[0];
    if(parseInt(quantity) > parseInt(avq)){
        notify('quantity not available','red');
        return;
    }
    if(price == 0 || price == null || quantity == 0 || quantity == null){
        return;
    }
    price = convertPrice(price,localStorage.getItem('sellcur'),'USD');
    xtp.open('get','../api/sellP.php?id='+id+'&quantity='+quantity+'&price='+price);
    xtp.onreadystatechange = () => {
        if(xtp.readyState == 4 && xtp.status == 200){
            if(xtp.responseText == '1'){
                if(quantity == avq){
                    selltools.parentNode.outerHTML = '';
                    if(plist.children.length == 1){
                        let node = document.createElement('p');
                        node.innerText = 'No more products';
                        plist.append(node);
                    }
                }else{
                    selltools.parentNode.children[2].children[1].value -= quantity;
                }
                earningsI.value = parseFloat(earningsI.value)+parseFloat(quantity*price);
                sales.value = parseInt(sales.value)+parseInt(quantity);
                let income = document.getElementsByClassName('income')[0],
                netI = document.getElementsByClassName('net')[0],
                expenses = parseFloat(document.getElementsByClassName('expense')[0].innerText),
                net = (parseFloat(income.innerText)+parseFloat(price*quantity)) - expenses;
                income.innerText = (parseFloat(income.innerText)+parseFloat(price*quantity))+' USD';
                if(net >= 0){
                    netI.innerHTML = `<span style="color: #02d026 !important;">${net} USD</span>`
                }else{
                    netI.innerHTML = `<span style="color: #ff5151 !important;">${net} USD</span>`
                }
                notify('product sold','green');
            }else{
                console.log(xtp.responseText);
                notify('internal error','red');
            }
        }
    }
    xtp.send();
}
// inserts category to db
function addNewCategory(){
    let form = document.getElementById('catform'),
    categs = document.getElementsByClassName('categories')[0],
    catname = document.getElementById('cform'),
    formdata = new FormData(form),
    file = document.getElementById('catIcon');
    file = file.files[0];
    formdata.append('file',file);
    formdata.append('pname',catname.value);
    xtp.open('post','../api/addCat.php',true);
    xtp.onreadystatechange = () => {
        if(xtp.readyState == 4 && xtp.status == 200){
            if(xtp.responseText == '1'){
                notify('Category added successfully','green');
                let op = `<option value="${catname.value}">${catname.value}</option>`;
                categs.innerHTML = categs.innerHTML+op;
            }else if(xtp.responseText == '-1'){
                notify('Category exists in the stack','orange');
            }else{
                notify('Internal error','orange')
            }
        }
    }
    xtp.send(formdata);
}
async function setEarningFilter(date1,date2){
    if((new Date(date1)).valueOf() > (new Date(date2)).valueOf()){
        let temp = date1;
        date1 = date2;
        date2 = temp;
    }
    let inp = document.getElementsByClassName('earningsInp')[0];
    //getEarnings(date1,date2);
    await getEarnings(date1,date2);
    let js = JSON.parse(earnings);
    inp.value = js['earnings'];
}
async function setSalesFilter(date1,date2){
    let inp = document.getElementsByClassName('salesInp')[0];
    if((new Date(date1)).valueOf() > (new Date(date2)).valueOf()){
        let temp = date1;
        date1 = date2;
        date2 = temp;
    }
    await getEarnings(date1,date2);
    let js = JSON.parse(earnings);
    inp.value = js['sales'];
}
function showSellTool(object){
    let tr = document.getElementsByClassName('sellTools')[0];
    if(tr !== undefined){
        if(object !== tr.parentNode){
            tr.outerHTML = '';
        }else{
            return;
        }

    }
    localStorage.setItem('id',object.children[object.children.length-1].value);
    let sellTools = `
    <div class="row mt-5 p-3 sellTools d-flex" onmousedown="localStorage.setItem('sellcur',this.children[2].value)">
        <input type="number" class="col-lg-6 form-control" min='1' placeholder="quantity">
        <input type="number" class="col-lg-3 form-control" step="0.01" min='0' placeholder="sale price">
        <select type="text" class="form-select col-lg-3" onmousedown="localStorage.setItem('selloldcur',this.value)" onmouseup="localStorage.setItem('sellcur',this.value)" onchange="setPriceFilter(this,this.parentNode.children[1],localStorage.getItem('selloldcur'))">
            <option value="USD" selected>USD</option>
            <option value="sayrafa">LBP (Sayrafa)</option>
            <option value="bm">LBP (Black Market)</option>
            <option value="omt">LBP (OMT)</option>
        </select>
        <button class="btn btn-danger col-lg-6" onclick="deleteP(localStorage.getItem('id'),this.parentNode.children[0],this.parentNode.parentNode.children[1].children[1],this.parentNode.parentNode.children[2].children[1])">Remove</button>
        <button class="btn btn-success col-lg-6" onclick="sellP(localStorage.getItem('id'))">Sell</button>
    </div>
    `;
    object.innerHTML = object.innerHTML + sellTools

}
function convertPrice(price,oldcur,newcur){
    let bmprice = parseInt(document.getElementsByClassName('bm')[0].value),
    sprice = parseInt(document.getElementsByClassName('sayrafa')[0].value),
    omtprice = parseInt(document.getElementsByClassName('omt')[0].value);
    let newprice = 0;
    if(newcur == 'USD'){
        if(oldcur == 'USD'){
            return price;
        }else if(oldcur == 'sayrafa'){
            newprice = parseFloat(price)/sprice;
        }else if(oldcur == 'bm'){
            newprice = parseFloat(price)/bmprice;
        }else{
            newprice = parseFloat(price)/omtprice;
        }
    }else if(newcur == 'sayrafa'){
        if(oldcur == 'USD'){
            newprice = parseFloat(price)*sprice;
        }else if(oldcur == 'sayrafa'){
            return price;
        }else if(oldcur == 'bm'){
            let inusd = parseFloat(price)/bmprice;
            newprice = inusd*sprice;
        }else{
            let inusd = parseFloat(price)/omtprice;
            newprice = inusd*sprice;
        }
    }else if(newcur == 'bm'){
        if(oldcur == 'USD'){
            input.value = parseFloat(input.value)*bmprice;
        }else if(oldcur == 'sayrafa'){
            let inusd = parseFloat(input.value)/sprice;
            input.value = inusd*bmprice;
        }else if(oldcur == 'bm'){
            return price;
        }else{
            let inusd = parseFloat(input.value)/omtprice;
            input.value = inusd*bmprice;
        }
    }else if(newcur == 'omt'){
        if(oldcur == 'USD'){
            input.value = parseFloat(input.value)*omtprice;
        }else if(oldcur == 'sayrafa'){
            let inusd = parseFloat(input.value)/sprice;
            input.value = inusd*omtprice;
        }else if(oldcur == 'bm'){
            let inusd = parseFloat(input.value)/bmprice;
            input.value = inusd*omtprice;
        }else{
            return price;
        }
    }
    return newprice;
}
function deleteP(id,quantityI,oldqI,price){
    let quantity = parseInt(quantityI.value),
    expense = document.getElementsByClassName('expense')[0];
    if(quantity == '' || isNaN(quantity)){
        notify('Specify Quantity','yellow');
        return;
    }
    xtp.open('get','../api/deleteP.php?id='+id+'&quantity='+quantity);
    xtp.onreadystatechange = ()=>{
        if(xtp.readyState == 4 && xtp.status === 200){
            if(xtp.responseText === '1'){
                oldqI.value -= quantity;
                expense.innerText = parseFloat(expense.innerText) - parseFloat(price.value)*quantity+' USD';
                notify('Product removed from stack','green');
            }else if(xtp.responseText === '2'){
                oldqI.parentNode.parentNode.outerHTML = '';
                expense.innerText = parseFloat(expense.innerText) - parseFloat(price.value)*quantity +' USD';
                let plist = document.getElementById('plist');
                if(plist.children.length == 1){
                    let node = document.createElement('p');
                    node.innerText = 'No more products';
                    plist.append(node);
                }
                let netI = document.getElementsByClassName('net')[0],
                net = parseFloat(netI.innerText);
                net += (quantity*price.value);
                netI.innerText = net+' USD';
                notify('Product removed from stack','green');
            }else{
                notify('Internal Error','red');
                console.log(xtp.responseText);
            }
        }
    }
    xtp.send();
}
function setPriceFilter(object,input,oldcur){
    let newcur = object.value,
    bmprice = parseInt(document.getElementsByClassName('bm')[0].value),
    sprice = parseInt(document.getElementsByClassName('sayrafa')[0].value),
    omtprice = parseInt(document.getElementsByClassName('omt')[0].value);
    if(newcur == 'USD'){
        if(oldcur == 'USD'){
            return;
        }else if(oldcur == 'sayrafa'){
            input.value = parseFloat(input.value)/sprice;
        }else if(oldcur == 'bm'){
            input.value = parseFloat(input.value)/bmprice;
        }else{
            input.value = parseFloat(input.value)/omtprice;
        }
    }else if(newcur == 'sayrafa'){
        if(oldcur == 'USD'){
            input.value = parseFloat(input.value)*sprice;
        }else if(oldcur == 'sayrafa'){
            return;
        }else if(oldcur == 'bm'){
            let inusd = parseFloat(input.value)/bmprice;
            input.value = inusd*sprice;
        }else{
            let inusd = parseFloat(input.value)/omtprice;
            input.value = inusd*sprice;
        }
    }else if(newcur == 'bm'){
        if(oldcur == 'USD'){
            input.value = parseFloat(input.value)*bmprice;
        }else if(oldcur == 'sayrafa'){
            let inusd = parseFloat(input.value)/sprice;
            input.value = inusd*bmprice;
        }else if(oldcur == 'bm'){
            return;
        }else{
            let inusd = parseFloat(input.value)/omtprice;
            input.value = inusd*bmprice;
        }
    }else if(newcur == 'omt'){
        if(oldcur == 'USD'){
            input.value = parseFloat(input.value)*omtprice;
        }else if(oldcur == 'sayrafa'){
            let inusd = parseFloat(input.value)/sprice;
            input.value = inusd*omtprice;
        }else if(oldcur == 'bm'){
            let inusd = parseFloat(input.value)/bmprice;
            input.value = inusd*omtprice;
        }else{
            return;
        }
    }
}
function notify(msg,color){
    let node = document.getElementById('notif');
    node.style.opacity = '100%';
    node.innerText = msg;
    node.style.color = color;
    setTimeout(()=>{
        node.style.opacity = '0%';
    },2000)
}
function shownav(){
    let navbar = document.getElementsByClassName('navb')[0];
    let rect = navbar.getBoundingClientRect();
    if(rect.width != 0){
        navbar.style.right = '100%';
    }else{
        navbar.style.right = '0px';
    }
}