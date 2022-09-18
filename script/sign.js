let xtp = new XMLHttpRequest();
function adapt(){
    let form  = document.getElementById('signform');

}
function signup(){
    let erro = 0;
    let form  = document.getElementById('signform'),
    childs = form.children,
    first = childs[0].children[0],
    last = childs[1].children[0],
    email = childs[2].children[0],
    passwd = childs[3].children[0],
    confirm = childs[4].children[0];
    if(isEmpty(first.value)){
        err('empty',first.parentNode);
        erro = 1;
    }else{
        deerror('empty',first.parentNode)
    }
    if(isEmpty(last.value)){
        err('empty',last.parentNode);
        erro = 1;
    }else{
        deerror('empty',last.parentNode)
    }
    if(isEmpty(email.value)){
        err('empty',email.parentNode);
        erro = 1;
    }else{
        deerror('empty',email.parentNode)
    }
    if(isEmpty(passwd.value)){
        err('empty',passwd.parentNode);
        erro = 1;
    }else{
        deerror('empty',passwd.parentNode)
    }
    if(confirm.value !== passwd.value){
        err('nomatch',confirm.parentNode);
        erro = 1;
    }else{
        deerror('nomatch',confirm.parentNode)
    }
    if(erro == 1){
        return;
    }
    xtp.open('get','../api/sign.php?first='+first.value+'&last='+last.value+'&email='+email.value+'&passwd='+passwd.value);
    xtp.onreadystatechange = ()=>{
        if(xtp.readyState == 4 && xtp.status === 200){
            if(xtp.responseText == '1'){
                window.location = '../log.html';
            }else{
                console.log(-1);
            }
        }
    }
    xtp.send();
}
function login(){
    let erro = 0;
    let form  = document.getElementById('signform'),
    childs = form.children,
    uname = childs[1].children[0],
    passwd = childs[2].children[0];
    if(isEmpty(uname.value)){
        err('empty',uname.parentNode);
        erro = 1;
    }else{
        deerror('empty',uname.parentNode)
    }
    if(isEmpty(passwd.value)){
        err('empty',passwd.parentNode);
        erro = 1;
    }else{
        deerror('empty',passwd.parentNode)
    }
    if(erro == 1){
        return;
    }
    console.log(passwd.value);
    xtp.open('get','../api/log.php?uname='+uname.value+'&passwd='+passwd.value);
    xtp.onreadystatechange = ()=>{
        if(xtp.readyState == 4 && xtp.status === 200){
            if(xtp.responseText == '1'){
                window.location = '../index.php';
            }else{
                err('incorrect',passwd.parentNode.parentNode);
            }
        }
    }
    xtp.send();
}
function isEmpty(variable){
    if(variable === null || variable === '' || variable === undefined){
        return true;
    }
    return false;
}
function err(error,object){
    object.classList.add(error);
}
function deerror(error,object){
    object.classList.remove(error);
}
