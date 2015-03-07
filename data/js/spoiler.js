function show(elem,elem2,elem3) {
    var obj = document.getElementById(elem);
    var obj2 = document.getElementById(elem2);
    var obj3 = document.getElementById(elem3);
    if( obj.style.display == "none" )
    {
      obj.style.display = "block";
      obj3.style.display = "block";
      obj2.style.display = "none";
    }
    else
    {
      obj.style.display = "none";
      obj3.style.display = "none";
      obj2.style.display = "block";
    }
}