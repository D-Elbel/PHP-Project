

var showCartVis = false;

function test(){
    console.log("test");
}



function showCart(){

console.log("test");

if(document.getElementById('cartPane').style.display != "block"){
     document.getElementById('cartPane').style.display = "block";
}
else if(document.getElementById('cartPane').style.display == "block"){
    document.getElementById('cartPane').style.display = "none";
}


}