var nf_format = Array();

function number_format (id) {
  var number;
  var dom = document.getElementById?1:0;
  var ie = (document.all)?1:0;
  var obj = (dom)?document.getElementById(id):ie?document.all.id:document.id;
  number = obj.value;

  if (typeof(number) == 'undefined' || number == '') return '';

  var reg = /\s/;
  while (reg.test(number))  number = number.replace(reg, "");

  //reg = /([^\d\.])/;
  reg = /[^\d]/;
  while (reg.test(number))  number = number.replace(reg, "");

  //reg = /^(\d+)\.(\d*)/;
  //var i = 0;
  //if (reg.test(number))  i = 1;


  var separator = ' ';
  reg = /(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g;
  //var rr = number.split('.');
  //var r = rr[0].replace(reg, "\$1" + separator);
  var r = number.replace(reg, "\$1" + separator);

  //if (i == 1)  r = r + "." + rr[1];
  obj.value = r;

  nf_format[id] = 1;

  return true;
};

function number_format_4 (id) {
  var number;
  var dom = document.getElementById?1:0;
  var ie = (document.all)?1:0;
  var obj = (dom)?document.getElementById(id):ie?document.all.id:document.id;
  number = obj.value;

  if (typeof(number) == 'undefined' || number == '') return '';

  var reg = /\s/;
  while (reg.test(number))  number = number.replace(reg, "");

  reg = /[^\d]/;
  while (reg.test(number))  number = number.replace(reg, "");

  var separator = ' ';
  reg = /(\d{1,4}(?=(\d{4})+(?:\.\d|\b)))/g;
  var r = number.replace(reg, "\$1" + separator);

  obj.value = r;

  return true;
};

function unformat () {
  var reg = /\s/;
  var dom = document.getElementById?1:0;
  var ie = (document.all)?1:0;
  var obj;
  var id = '';
  var number = 0;

  for (name in nf_format) {
    id = name;
    obj = (dom)?document.getElementById(id):ie?document.all.id:document.id;
    number = obj.value;
    while (reg.test(number))  number = number.replace(reg, "");
    obj.value = number;
  }

  return true;
};


function unformat_number(text)
{
  return text.replace(/\s/g, '');
}