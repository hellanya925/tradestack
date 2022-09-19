let currencies = ['USD','EUR','AUD','BRL','LBP','CAD','EGP','INR','GBP'],
currenciesOptionList = new Array();
currencies = currencies.sort();
for(var i =0 ;i < currencies.length;i++){
    currenciesOptionList.push(`<option value="${currencies[i]}">${currencies[i]}</option>`);
}

function setContent(){
    let lc = document.getElementById('leftCur'),
    rc = document.getElementById('rightCur'),
    lq = document.getElementById('leftQ'),
    rq = document.getElementById('rightQ');
    lc.innerHTML = rc.innerHTML = currenciesOptionList.join();
}
async function switchConverter(){
    let lc = document.getElementById('leftCur'),
    rc = document.getElementById('rightCur'),
    lq = document.getElementById('leftQ'),
    rq = document.getElementById('rightQ');
    let temp = lc.value;
    lc.value = rc.value;
    rc.value = temp;
    const data = JSON.parse(await getRate(lq.value,lc.value,rc.value));
    rq.setAttribute('value',data);
}
async function setRate(q,oldc,newc){
    const val = await getRate(q,oldc,newc);
    let rq = document.getElementById('rightQ');
    rq.setAttribute('value',val);
}
async function getRate(q,oldc,newc){
    return await fetch(`../api/convertCurrency.php?from=${oldc}&to=${newc}`,{
        method: "GET",
        headers: {
            'X-Requested-With': "XMLHttpRequest"
        }
    }).then((response) => {
        if(response.status == 200){
            return response.json();
        }
    }).then((data) => {
        const d = JSON.parse(JSON.stringify(data)),
        low = d['low'],
        high = d['high'],
        val = (low+high)/2,
        quantity = q*val;
        return quantity;
    });
}