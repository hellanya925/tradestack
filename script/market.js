async function switchConverter(){
    let lc = document.getElementById('leftCur'),
    rc = document.getElementById('rightCur'),
    lq = document.getElementById('leftQ'),
    rq = document.getElementById('rightQ');
    let temp = lc.value;
    lc.value = rc.value;
    rc.value = temp;
    await fetch('../api/convertCurrency.php')
    let rightQ = convertPrice(parseFloat(lq.value),lc.value,rc.value);
}