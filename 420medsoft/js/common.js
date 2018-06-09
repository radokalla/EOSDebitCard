/* ucfirst lcfirst  ucwords  lower alpha strong medium mail percent currency time date  alphadot alphanum alphasymbols   num  numcoma  nosymbols symbols dontallow */
var logouturl="";
var BASE_URL=window.location.toString();
BASE_URL=BASE_URL.split('/');
turl="";
if(typeof urisegments=='undefined')
	urisegments=3;
else
	urisegments=urisegments||3;
for(ix=0;ix<BASE_URL.length;ix++){
	turl+=BASE_URL[ix]+"/";
	if(ix==urisegments)
		break;
}
BASE_URL=turl;
function changetext(txt)
{
	var n=txt.split(" ");
	var output="";
	var length = n.length;
	for (var i = 0; i < length; i++)
	{
		var lstr=n[i].substr(0,1);
		var rstr=n[i].substr(1,n[i].length);
		output=output+lstr.toUpperCase()+rstr.toLowerCase()+" ";
	}
	output=output.substr(0,output.length-1);
	return output;
}

function replacechar(str,pos,char)
{  
	if(pos==0)
		rstr=char+str.substr(pos+1,str.length);
	else if(pos==1)
		rstr=str.substr(0,1)+char+str.substr(pos+1,str.length);
	else
		rstr=str.substr(0,pos)+char+str.substr(pos+1,str.length);
	return rstr;
}


function getSelectionStart(o)
 {
		if (o.createTextRange) {
			var r = document.selection.createRange().duplicate()
			rse = r.text.length;
			r.moveEnd('character', o.value.length)
			if (r.text == '') return o.value.length
			return o.value.lastIndexOf(r.text)
		} else return o.selectionStart
}

function getSelectionEnd(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveStart('character', -o.value.length)
		return r.text.length
	} else return o.selectionEnd
}
	
function objecttostring(e)
{
	var output="";
	for (property in e) {
	  output += property + ': ' + e[property]+';\n ';
	}
	return output;
}


function createSelection(field, start, end) {
    if( field.createTextRange ) {
      var selRange = field.createTextRange();
      selRange.collapse(true);
      selRange.moveStart('character', start);
      selRange.moveEnd('character', end);
      selRange.select();
//      field.focus();
    } else if( field.setSelectionRange ) {
  //    field.focus();
      field.setSelectionRange(start, end);
    } else if( typeof field.selectionStart != 'undefined' ) {
      field.selectionStart = start;
      field.selectionEnd = end;
    //  field.focus();
    }
  }

function getstate(cid,purl)
{
	var http=new XMLHttpRequest();
	sethttp(http,purl,"'cid':"+cid,"POST")
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			json=http.responseText;
			json=JSON.parse(json);
			html='<option value="">-- Select State --</option>';
			for(loopi=0;loopi<json.length;loopi++)
			{
				html+='<option value="'+json[loopi]['id']+'">'+json[loopi]['name']+'</option>';
			}
			document.getElementById('state').innerHTML=html;
		} else {
			alert('Unable get States List');
		}
	}
}

function getcity(sid,purl)
{
	var http=new XMLHttpRequest();
	sethttp(http,purl,"'cid':"+cid,"POST")
	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			json=http.responseText;
			json=JSON.parse(json);
			html='<option value="">-- Select City --</option>';
			for(loopi=0;loopi<json.length;loopi++)
			{
				html+='<option value="'+json[loopi]['id']+'">'+json[loopi]['name']+'</option>';
			}
			document.getElementById('city').innerHTML=html;
			} else {
				alert('Unable get Cities List');
			}
		}
}
	function getValue(dataelement,joiner)
	{
		if(dataelement && (dataelement.substr(0,1)=="." || dataelement.substr(0,1)=="#")){
			result="";
			if(dataelement.substr(0,1)=="." ){
				var elements=document.getElementsByClassName(dataelement.substr(1));
				for(ix=0;ix<elements.length;ix++)
					result+=",'"+elements[ix].getAttribute('name')+"':"+elements[ix].value;
			} else {
				var element=document.getElementById(dataelement.substr(1));
					result+=",'"+element.getAttribute('name')+"':"+element.value;
			}
			return result;
		}
		return "";
	}
	function checkfortarget(id)
	{
		keyCode=46;
		KeyPressedElement=document.getElementById(id)
		var dat=KeyPressedElement.getAttribute('onkeyup');
		if(dat){
			dat=dat.substr(11);
			dat=dat.substr(0,(dat.length-2));
		}
		checkdata(dat);
	}
	
	function checkdata(dataelement)
	{	
		var clas=KeyPressedElement.getAttribute('class');
		var title=KeyPressedElement.getAttribute('title');
		var durl=KeyPressedElement.getAttribute('data-url');
		title=title||'Email';
		if(durl!=""){
			if((keyCode>45 && keyCode<128) || keyCode==8 || keyCode==36){
				var cid=KeyPressedElement.value;
				if((clas.indexOf('unique')>=0 ) || (cid.indexOf('@')>0 && cid.lastIndexOf('.')>cid.indexOf('@') && cid.length>cid.lastIndexOf('.')+1)) {
				var cid="'"+KeyPressedElement.getAttribute('name')+"':"+KeyPressedElement.value+getValue(dataelement)
		//			+getValue(aid1,"|")+getValue(aid2,"|")+getValue(aid3,"|")+getValue(aid4,"|")+getValue(aid5,"|");
					addClass(KeyPressedElement,'spinner');
					var http=new XMLHttpRequest();
					sethttp(http,BASE_URL+durl,cid,"POST")
					//sethttp(http,purl,"'cid':"+cid,"POST")
					http.onreadystatechange = function() {//Call a function when the state changes.
						if(http.readyState == 4 && http.status == 200) {
							result=http.responseText;
							removeClass(KeyPressedElement,'spinner');
							result=result.trim();
							result=parseInt(result);
							if(result==0){
									removeClass(KeyPressedElement,'exist');
								}
							else{
									seterror(KeyPressedElement,title+' Already Exists');
									addClass(KeyPressedElement,'exist');
								}
						}
					}
				}
			}
		}
	}

	function JsonData()
	{
		if(result.length>1){
			result=JSON.parse(result);
		} else  {
			result=[];
		}
		return result;
	}
	
	function getJsonData(element,dataelement,funct)
	{
		result=getData(element,dataelement,JsonData)
		if(funct!=""){
			var fn = window[funct];
			if (typeof fn === "function")
				fn(result);						
		}
	}

	function getData(element,dataelement,funct)
	{
		var durl=element.getAttribute('data-url');
		var title=element.getAttribute('title');
		title=title||'';
		if(durl!=""){
			var data=getValue(dataelement);
			var http=new XMLHttpRequest();
			sethttp(http,BASE_URL+durl,data,"POST")
			http.onreadystatechange = function() {//Call a function when the state changes.
				if(http.readyState == 4 && http.status == 200) {
					result=http.responseText;
					result=result.trim();
					if(funct!=""){
						var fn = window[funct];
						if (typeof fn === "function")
							fn(result);						
					}
				}
			}
		}
	}

	
function valid()
{
	
	var input=document.getElementsByTagName("input");
	var clas="";
	for(il=0;il<input.length;il++)
	{	
		clas=input[il].getAttribute('class');
		if(clas!=null){
			if(clas.indexOf('required')>0)
			{
				l=input.item(il).value.trim().length;
				if(l==0)
					return false;
			}
		}
	}
	var input=document.getElementsByTagName("textarea");
	for(il=0;il<input.length;il++)
	{
		clas=input.item(il).getAttribute('class');
		if(clas!=null && clas.indexOf('required')>0)
		{
			l=input.item(il).value.trim().length;
			if(l==0)
				return false;
		}
	}
	var input=document.getElementsByTagName("select");
	for(il=0;il<input.length;il++)
	{
		clas=input.item(il).getAttribute('class');
		if(clas!=null && clas.indexOf('required')>0)
		{
			l=input.item(il).value.trim().length;
			if(l==0)
				return false;
		}
	}
}

function enablesubmit()
{
	var errorsfound=founderrors();
	if(errorsfound.toString()=="true" || submited.toString()!="true"){
		var elements=findElementByType('submit')
		for(il=0;il<elements.length;il++)
		{
			elements[il].removeAttribute('disabled');
		}
	}
}

function findElementByType(type)
{
	var input=document.getElementsByTagName("input");
	var elements=new Array();
	for(il=0;il<input.length;il++)
	{	
		if(input[il].type==type){
			elements[elements.length]=input[il];
		}
	}
	var input=document.getElementsByTagName("button");
	for(il=0;il<input.length;il++)
	{	
		if(input[il].type==type){
			elements[elements.length]=input[il];
		}
	}
	return elements;	
}

var submited=false;
var fromsubmit=false;
var ajaxsubmit=false;

function addslashes(str) {
    str = str.replace(/\\/g, '\\\\');
    str = str.replace(/\'/g, '\\\'');
    str = str.replace(/\"/g, '\\"');
    str = str.replace(/\0/g, '\\0');
    return str;
}
 
function stripslashes(str) {
	//str =str.replace(new RegExp('\\n','g'), '<br />');
    str = str.replace(/\\\\'/g, '\'');
	 str = str.replace(/\n'/g, '<br />');
	str = str.replace(/\\'/g, '\'');
    str = str.replace(/\\"/g, '"');
    str = str.replace(/\\0/g, '\0');
    str = str.replace(/\\\\/g, '\\');
    return str;
}


function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}
function titlestr (str) {
	str=str.substr(0,1).toUpperCase()+str.substr(1,str.length-1);
    return (str + '').replace(/^([a-z])|\.\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

Array.prototype.unset = function(value) {
	
    if(typeof value!='undefined' && typeof value!=null && this.indexOf(value) != -1) { // Make sure the value exists
        this.splice(this.indexOf(value), 1);
    }   
}

String.prototype.repeat = function( num )
{
    return new Array( num + 1 ).join( this );
}

String.prototype.replaceAll = function (find, replace) {
    var str = this;
    return str.replace(new RegExp(find, 'g'), replace);
};

var validNavigation = 0;
 
function endSession(purl) {
	if(typeof purl!="undefined" && purl.length!=0 ) {
		//document.location=purl;
		var http=new XMLHttpRequest();
		sethttp(http,purl,"","POST")
	}
}
window.onbeforeunload = confirmExit;
  function confirmExit()
  {
	  if (validNavigation==0 && logouturl!=null && logouturl.length>0) {
		 endSession(logouturl);
		 return "Thank you for Visiting our Web Site <br> Visit Again!";
	  }
  }
  
var chkele; 

document.addEventListener( "DOMContentLoaded", ready, false );

function ready()
{
	validNavigation=0;
	if(typeof purl!="undefined" && purl.length!=0) {
	  setallEvents(); 
	}
	addAlertCsstoHead();
	//document.forms[0].elements[0].focus();
}

function sleep(delay){
	var now = new Date();
	var desiredTime = new Date().setSeconds(now.getSeconds() + delay);
	
	while (now < desiredTime) {
		now = new Date(); // update the current time
	}
}

function validevent(e)
{
	if(typeof e.target.type!="undefined" ) {
		type=e.target.type
		element=e.target;
		if(typeof prevelement!="undefined"){
			validateelement(prevelement.target);}
		if(type=="textarea" || type=="text" || type=="password" || type=="select-multiple" ||type=="select-one" || type=="radio"  || type=="checkbox"){
			validateelement(element);
		}
	}
}

function validsevent(e)
{
	if(typeof e.target.type!="undefined") {
		type=e.target.type
		element=e.target;
		if(type=="select-one" || type=="select-multiple"   || type=="password" || type=="radio"  || type=="file"   || type=="checkbox" || type=="text"){
			validateelement(element);
		}
	}
}

var keyCode;
var charCode;
var printable;
var SelectionStart;
var SelectionEnd;
var KeyPressedElement;
var KeyPressedMaxLength;
var KeyPressedValue;
var ClipBoardText;

function upper(e)
{
	if(charCode>96 && charCode<123)
		charCode=charCode-32;
}

function lower(e)
{
	if(charCode>64 && charCode<91)
		charCode=charCode+32;
}
function ucword()
{
	PutChar(charCode);
	KeyPressedElement.value=ucwords(KeyPressedElement.value);
}
function titles()
{
	PutChar(charCode);
	KeyPressedElement.value=titlestr(KeyPressedElement.value);
}

function upper_paste(e)
{	
	KeyPressedValue=KeyPressedElement.value;
	if(KeyPressedValue.length<KeyPressedMaxLength || SelectionStart!=SelectionEnd){
		if(SelectionStart==SelectionEnd){
			AvailableLength=KeyPressedMaxLength-KeyPressedValue.length;
			if(AvailableLength!=0){
				if(ClipBoardText.length>AvailableLength)
					PutString(ClipBoardText.substr(0,AvailableLength).toUpperCase(),AvailableLength);
				else
					PutString(ClipBoardText.toUpperCase(),ClipBoardText.length);
			}
		} else {
			if(SelectionStart==0 && SelectionEnd==(KeyPressedValue.length))
				SelectionStart=1;
			AvailableLength=SelectionEnd-SelectionStart;
			if(AvailableLength!=0){
				if(ClipBoardText.length>AvailableLength)
					PutString(ClipBoardText.substr(0,AvailableLength).toUpperCase(),AvailableLength);
				else
					PutString(ClipBoardText.toUpperCase(),ClipBoardText.length);
			}
		}
	}
	charCode=0;
}

function lower_paste(e)
{	
	KeyPressedValue=KeyPressedElement.value;
	if(KeyPressedValue.length<KeyPressedMaxLength || SelectionStart!=SelectionEnd){
		if(SelectionStart==SelectionEnd){
			AvailableLength=KeyPressedMaxLength-KeyPressedValue.length;
			if(AvailableLength!=0){
				if(ClipBoardText.length>AvailableLength)
					PutString(ClipBoardText.substr(0,AvailableLength).toLowerCase(),AvailableLength);
				else
					PutString(ClipBoardText.toLowerCase(),ClipBoardText.length);
			}
		} else {
			if(SelectionStart==0 && SelectionEnd==(KeyPressedValue.length))
				SelectionStart=1;
			AvailableLength=SelectionEnd-SelectionStart;
			if(AvailableLength!=0){
				if(ClipBoardText.length>AvailableLength)
					PutString(ClipBoardText.substr(0,AvailableLength).toLowerCase(),AvailableLength);
				else
					PutString(ClipBoardText.toLowerCase(),ClipBoardText.length);
			}
		}
	}
	charCode=0;
}

function ucword_paste()
{
	lower_paste()
	KeyPressedElement.value=ucwords(KeyPressedElement.value);
}
function titles_paste()
{
	lower_paste()
	KeyPressedElement.value=titlestr(KeyPressedElement.value);
}

function putpaste(TClipBoardText)
{
	if(TClipBoardText.length==0){
		charCode=0;
		return false;
	}
	KeyPressedValue=KeyPressedElement.value;
	if(KeyPressedValue.length<KeyPressedMaxLength || SelectionStart!=SelectionEnd){
		if(SelectionStart==SelectionEnd){
			AvailableLength=KeyPressedMaxLength-KeyPressedValue.length;
			if(AvailableLength!=0){
				if(TClipBoardText.length>AvailableLength)
					PutString(TClipBoardText.substr(0,AvailableLength).toUpperCase(),AvailableLength);
				else
					PutString(TClipBoardText.toUpperCase(),TClipBoardText.length);
			}
		} else {
			if(SelectionStart==0 && SelectionEnd==(KeyPressedValue.length))
				SelectionStart=1;
			AvailableLength=SelectionEnd-SelectionStart;
			if(AvailableLength!=0){
				if(TClipBoardText.length>AvailableLength)
					PutString(TClipBoardText.substr(0,AvailableLength).toUpperCase(),AvailableLength);
				else
					PutString(TClipBoardText.toUpperCase(),ClipBoardText.length);
			}
		}
	}
	charCode=0;

}

function num_paste()
{ 
	putpaste(converttext(ClipBoardText,nums));
}

function alpha_paste()
{ 
	putpaste(converttext(ClipBoardText,alphas));
}

function alphaspace_paste()
{ 
	putpaste(converttext(ClipBoardText,alphaspace));
}

function alphadot_paste()
{ 
	putpaste(converttext(ClipBoardText,alphadot));
}

function alphacomma_paste()
{ 
	putpaste(converttext(ClipBoardText,alphacomma));
}
function alphasymbols_paste()
{ 
	putpaste(converttext(ClipBoardText,alphasymbols));
}

var nums=new Array(48,49,50,51,52,53,54,55,56,57);
var alphas=new Array(65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122);
var symbols=new Array(33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,91,92,93,94,95,96);

var alphaspace=new Array();
alphaspace=alphas;
alphaspace[alphaspace.length]=32;

var alphadot=new Array();
alphadot=alphas;
alphadot[alphadot.length]=46;

var alphacomma=new Array();
alphacomma=alphas;
alphacomma[alphacomma.length]=44;

var alphanum=new Array();
alphanum=nums.concat(alphas);
var alphasymbols=new Array();
alphasymbols=alphas.concat(symbols);

function converttext(str,arr)
{	var result="";
	for(x=0;x<str.length;x++){
		if(inarray(str[x].charCodeAt(0),arr))

			result+=str[x];
	}
	return result;
}


function num()
{ 
	if(inarray(charCode,nums)){
		dmax=KeyPressedElement.getAttribute('data-max');
		dvalue=KeyPressedElement.getAttribute('value');
		dvalue=(isNaN(parseInt(dvalue)))?0:parseInt(dvalue);
		if(typeof dmax=='undefined' || dmax==null){
			PutChar(charCode);
		} else {
			dmax=(isNaN(parseInt(dmax)))?0:parseInt(dmax);
			tvalue=PutCharR(charCode);
			tvalue=(isNaN(parseInt(tvalue)))?0:parseInt(tvalue);
			if(tvalue>dmax){
				seterror(KeyPressedElement,'Maximum Allowed Upto '+dmax+' .');
				eventerror=true;
			} else {
				PutChar(charCode);
			}
		}
	} else {
		seterror(KeyPressedElement,'Only Numbers From 0 to 9 are Allowed.');
		eventerror=true;
	}
}

function alpha()
{ 
	if(inarray(charCode,alphas)){
		PutChar(charCode);
	} else {
		seterror(KeyPressedElement,'Only Alphabets From A to Z are Allowed.');
		eventerror=true;
	}
}
function alphaspace()
{ 
	if(inarray(charCode,alphaspace)){
		PutChar(charCode);
	} else {
		seterror(KeyPressedElement,'Only Alphabets From A to Z and Space are Allowed.');
		eventerror=true;
	}
}

function alphadot()
{ 
	if(inarray(charCode,alphadot)){
		PutChar(charCode);
	} else {
		seterror(KeyPressedElement,'Only Alphabets From A to Z and Dot(.) are Allowed.');
		eventerror=true;
	}
}

function alphacomma()
{ 
	if(inarray(charCode,alphacomma)){
		PutChar(charCode);
	} else {
		seterror(KeyPressedElement,'Only Alphabets From A to Z and Comma(,) are Allowed.');
		eventerror=true;
	}
}

function alphasymbols()
{ 
	if(inarray(charCode,alphasymbols)){
		PutChar(charCode);
	} else {
		seterror(KeyPressedElement,'Only Alphabets From A to Z and Comma(,) are Allowed.');
		eventerror=true;
	}
}
function alphanums()
{ 
	if(inarray(charCode,alphanum)){
		PutChar(charCode);
	} else {
		seterror(KeyPressedElement,'Only Alphabets From A to Z and Numbers From 0-9 are Allowed.');
		eventerror=true;
	}
}

function currency()
{
	vdata=KeyPressedElement.getAttribute('vdata');
	if(vdata==null){
		vdata="####.##";
	}
	var pos=0,ppos=-1;
	var coma=new Array();
	var point=2;
	while(pos>=0){
		pos=vdata.indexOf(',',ppos+1);
		if(pos>=0){
			coma[coma.length]=pos;
			ppos=pos;
		}
	}
	if(vdata.lastIndexOf('.')>=0)
		point=vdata.length-(vdata.lastIndexOf('.')+1);
	tstr=KeyPressedElement.value;
	if(charCode==46){
		if(tstr.indexOf('.')>=0){
			seterror(KeyPressedElement,'Only One Dot(.) is Allowed.');
			eventerror=true;
		} else {

			PutChar(charCode);
		}
	} else if(charCode>47 && charCode<58) {
		if(tstr.indexOf('.')>=0 && SelectionStart-tstr.indexOf('.')>point){
			seterror(KeyPressedElement,'Only '+point+' Digits Allowed after Dot(.).');
		} else {
			PutChar(charCode);
		}
	} else {
		seterror(KeyPressedElement,'Only Numbers from 0 to 9 and One Dot(.) is Allowed.');
		eventerror=true;
	}
}

function percent()
{
	tstr=KeyPressedElement.value;
	point=2;
	if(charCode==46){
		if(tstr.indexOf('.')>=0){
			seterror(KeyPressedElement,'Only One Dot(.) is Allowed.');
			eventerror=true;
		} else {
			PutChar(charCode);
		}
	} else if(charCode>47 && charCode<58) {
		if(parseFloat(PutCharR(charCode))<=100){
			if(tstr.indexOf('.')>=0) {
				if(SelectionStart-tstr.indexOf('.')<=point){
					PutChar(charCode);
				} else {
					seterror(KeyPressedElement,'Only '+point+' Digits Allowed after Dot(.).');
					eventerror=true;
				}
			} else {
				PutChar(charCode);
			}
		} else {
			seterror(KeyPressedElement,'You can Enter Value from 0.00 to 100.00');
			eventerror=true;
		}
	} else {
		seterror(KeyPressedElement,'Only Numbers from 0 to 9 and One Dot(.) is Allowed.');
		eventerror=true;
	}
}

function validtext()
{
	vdata=KeyPressedElement.getAttribute('vdata');
	vdata=vdata.replaceAll(' ',',');
	tstr=vdata.split(',');
	var allowed=new Array();
	for(il=0;il<tstr.length;il++){
		temp=tstr[il].trim().split('-');
		if(temp.length>1){
			if(tstr[il].trim()=='A-z' || tstr[il].trim()=='Z-a'){
				for(iz=65;iz<=90;iz++)
					allowed[allowed.length]=iz;
				for(iz=97;iz<=122;iz++)
					allowed[allowed.length]=iz;
			} else {
				if(temp[0].charCodeAt(0)>temp[1].charCodeAt(0)){
					start=temp[0].charCodeAt(0);
					end=temp[1].charCodeAt(0);
				} else {
					start=temp[0].charCodeAt(0);
					end=temp[1].charCodeAt(0);
				}
				for(iz=start;iz<=end;iz++)
					allowed[allowed.length]=iz;
			}
		} else {
				allowed[allowed.length]=temp[0].charCodeAt(0);
		}
	}
	if(inarray(charCode,allowed)){
		PutChar(charCode);
	} else {
		charCode=0;
		seterror(KeyPressedElement,'Only '+vdata+' are Allowed.');
		eventerror=true;
	}
}

function check_date()
{
	vdata=KeyPressedElement.getAttribute('vdata');
	if(vdata==null)
		vdata="dd/mm/yyyy";
	KeyPressedElement.setAttribute('maxlength',vdata.length);
	KeyPressedElement.setAttribute('minlength',vdata.length);
	var seperator="/";
	var datelength,monthlength,yearlength;
	var pos=new Array();
	var ppos=0;
	var cpos=0;
	var first,second,third;
	if(vdata.indexOf('/')>=0){
		tstr=vdata.split('/');	
		seperator="/";
	} else if(vdata.indexOf('-')>=0){
		seperator="-";
		tstr=vdata.split('-');	
	} else if(vdata.indexOf('.')>=0){
		tstr=vdata.split('.');	
		seperator=".";
	}
	while(cpos>=0){
		cpos=vdata.indexOf(seperator,ppos);
		if(cpos>=0){
			pos[pos.length]=cpos-ppos;		
		}
		ppos=cpos+1;
	}
	pos[pos.length]=vdata.length-(vdata.lastIndexOf(seperator)+1);		
	masktext="";
	for(il=0;il<pos.length;il++){
		cpos=tstr[il].toUpperCase();
		if (cpos.indexOf('D')>=0) {
			datelength=pos[il];
			masktext+='_'.repeat(datelength)+seperator;
		}
		if (cpos.indexOf('M')>=0){
			monthlength=pos[il];
			masktext+='_'.repeat(monthlength)+seperator;
		}
		if (cpos.indexOf('Y')>=0){
			yearlength=pos[il];
			masktext+='_'.repeat(yearlength)+seperator;
		}
		if(il==0)
			first=cpos.substr(0,1);
		if(il==1)
			second=cpos.substr(0,1);
		if(il==2)
			third=cpos.substr(0,1);
	}
	masktext=masktext.substr(0,masktext.length-1);
	value=KeyPressedElement.value;
	ans=false;
	if((charCode>47 && charCode<58) || charCode==(seperator.charCodeAt(0))){
		if(charCode==(seperator.charCodeAt(0))){
			if(SelectionStart<pos[0]){
				validtypeddate(first,0,pos[0],vdata);
			} else if(SelectionStart<(pos[0]+1+pos[1])){
				validtypeddate(second,pos[0]+1,pos[1],vdata);
			} else {
				validtypeddate(third,pos[0]+pos[1]+2,pos[2],vdata);
			}
		} else {
			if(value.length<masktext.length){
				if(masktext.substr(SelectionStart,1)==seperator){
					KeyPressedElement.setSelectionRange(SelectionStart+1,SelectionStart+1);
					SelectionStart++;
					value=KeyPressedElement.value;
					if(SelectionStart<pos[0]){
						ans=validtypeddate(first,0,pos[0],vdata);
					} else if(SelectionStart<(pos[0]+1+pos[1])){
						ans=validtypeddate(second,pos[0]+1,pos[1],vdata);
					} else {
						ans=validtypeddate(third,pos[0]+pos[1]+2,pos[2],vdata);
					}
				}
				else if(masktext.substr(SelectionStart+1,1)==seperator){
					if(SelectionStart<pos[0]){
						ans=validtypeddate(first,0,pos[0],vdata);
					} else if(SelectionStart<(pos[0]+1+pos[1])){
						ans=validtypeddate(second,pos[0]+1,pos[1],vdata);
					} else {
						ans=validtypeddate(third,pos[0]+pos[1]+2,pos[2],vdata);
					}
					value=KeyPressedElement.value;
					if(ans && value.substr(SelectionStart+1,1)!=seperator){
						PutChar(seperator.charCodeAt(0));
					}
				} else {
					if(SelectionStart<pos[0]){
						validtypeddate(first,0,pos[0],vdata);
					} else if(SelectionStart<(pos[0]+1+pos[1])){
						validtypeddate(second,pos[0]+1,pos[1],vdata);
					} else {
						validtypeddate(third,pos[0]+pos[1]+2,pos[2],vdata);
					}
					
				}
			} 
		}
	} else {
		seterror(KeyPressedElement,'Only Numbers from 0 to 9 and ('+seperator+') is Allowed.');
		eventerror=true;
	}
}

function validtypeddate(mode,start,end,vdata)
{	
	removeerror(KeyPressedElement);
	y=new Date().getFullYear();
	var dec="";
	dec=y.toString().substr(0,2);
	year=y.toString().substr(2,2);
	value=PutCharR(charCode);
	value=value.substr(start,end);
	if(mode=='D')
		maxno=31;
	else if(mode=='M')
		maxno=12
	else if(end>2){
		maxno=2500;
	} else 
		maxno=99;
	if(charCode>47 && charCode<58) {
		if(parseInt(value)<=maxno){
			if(mode!='D' && mode!='M'){
				if(parseInt(value)>=year && parseInt(value)<100){
					dec=parseInt(dec)-1;
					dec=dec.toString();
					KeyPressedElement.setSelectionRange(SelectionStart-1,SelectionStart-1);
					PutChar(dec.charCodeAt(0));
					PutChar(dec.charCodeAt(1));
					KeyPressedElement.setSelectionRange(SelectionStart+2,SelectionStart+2);
				} else if(parseInt(value)>9 && parseInt(value)<100) {
					dec=dec.toString();
					KeyPressedElement.setSelectionRange(SelectionStart-1,SelectionStart-1);
					PutChar(dec.charCodeAt(0));
					PutChar(dec.charCodeAt(1));
					KeyPressedElement.setSelectionRange(SelectionStart+2,SelectionStart+2);
				}
				PutChar(charCode);
			} else {
			PutChar(charCode);}
			return true;
		} else {
			seterror(KeyPressedElement,'Upto Max ('+maxno+') you can Enter Here. Date Format :'+vdata);
			eventerror=true;
		}
	} else {
		if(mode=='D' || mode=='M') {
			KeyPressedElement.setSelectionRange(SelectionStart-1,SelectionStart-1)
			PutChar(48);
			KeyPressedElement.setSelectionRange(SelectionStart+2,SelectionStart+2)
			PutChar(charCode);
		} else {
			
		}
		
	}
	
}

function check_time()
{
	value=KeyPressedElement.value;
	vdata=KeyPressedElement.getAttribute('vdata');
	if(vdata==null)
		vdata="hh:mm:ss";
	var seperator=":";
	var datelength,monthlength,yearlength;
	var pos=new Array();
	var ppos=0;
	var cpos=0;
	var first,second,third;
	if(vdata.indexOf(':')>=0){
		tstr=vdata.split(':');	
		seperator=":";
	} else if(vdata.indexOf('-')>=0){
		seperator="-";
		tstr=vdata.split('-');	
	} else if(vdata.indexOf('.')>=0){
		tstr=vdata.split('.');	
		seperator=".";
	}
	if(SelectionStart!=SelectionEnd){
		stext=value.substring(SelectionStart,SelectionEnd);
		if(stext.indexOf(seperator)>=0){
			return false;
		}
	}
	while(cpos>=0){
		cpos=vdata.indexOf(seperator,ppos);
		if(cpos>=0){
			pos[pos.length]=cpos-ppos;		
		}
		ppos=cpos+1;
	}
	pos[pos.length]=vdata.length-(vdata.lastIndexOf(seperator)+1);		
	masktext="";
	for(il=0;il<pos.length;il++){
		cpos=tstr[il].toUpperCase();
		if (cpos.indexOf('H')>=0) {
			datelength=pos[il];
			masktext+='_'.repeat(datelength)+seperator;
		}
		if (cpos.indexOf('M')>=0){
			monthlength=pos[il];
			masktext+='_'.repeat(monthlength)+seperator;
		}
		if (cpos.indexOf('S')>=0){

			yearlength=pos[il];
			masktext+='_'.repeat(yearlength)+seperator;
		}
		if(il==0)
			first=cpos.substr(0,1);
		if(il==1)
			second=cpos.substr(0,1);
		if(il==2)
			third=cpos.substr(0,1);
	}
	masktext=masktext.substr(0,masktext.length-1);
	value=KeyPressedElement.value;
	ans=false;
	if((charCode>47 && charCode<58) || charCode==(seperator.charCodeAt(0))){
		if(charCode==(seperator.charCodeAt(0))){
			if(SelectionStart<pos[0]){
				validtypedtime(first,0,pos[0],vdata);
			} else if(SelectionStart<(pos[0]+1+pos[1])){
				validtypedtime(second,pos[0]+1,pos[1],vdata);
			} else {
				validtypedtime(third,pos[0]+pos[1]+2,pos[2],vdata);
			}
		} else {
			if(value.length<masktext.length){
				if(masktext.substr(SelectionStart,1)==seperator){
					KeyPressedElement.setSelectionRange(SelectionStart+1,SelectionStart+1);
					SelectionStart++;
					//PutChar(seperator.charCodeAt(0));
					value=KeyPressedElement.value;
					if(value.length<masktext.length){
						if(SelectionStart<pos[0]){
							validtypedtime(first,0,pos[0],vdata);
						} else if(SelectionStart<(pos[0]+1+pos[1])){
							validtypedtime(second,pos[0]+1,pos[1],vdata);
						} else {
							validtypedtime(third,pos[0]+pos[1]+2,pos[2],vdata);
						}
					}
				}
				else if(masktext.substr(SelectionStart+1,1)==seperator){
					if(SelectionStart<pos[0]){
						ans=validtypedtime(first,0,pos[0],vdata);
					} else if(SelectionStart<(pos[0]+1+pos[1])){

						ans=validtypedtime(second,pos[0]+1,pos[1],vdata);
					} else {
						ans=validtypedtime(third,pos[0]+pos[1]+2,pos[2],vdata);
					}
					value=KeyPressedElement.value;
					if(ans && value.substr(SelectionStart+1,1)!=seperator){
						PutChar(seperator.charCodeAt(0));
					}
				} else {
					if(SelectionStart<pos[0]){
						validtypedtime(first,0,pos[0],vdata);
					} else if(SelectionStart<(pos[0]+1+pos[1])){
						validtypedtime(second,pos[0]+1,pos[1],vdata);
					} else {
						validtypedtime(third,pos[0]+pos[1]+2,pos[2],vdata);
					}
					
				}
			} 
		}
	} else {
		seterror(KeyPressedElement,'Only Numbers from 0 to 9 and ('+seperator+') is Allowed. Time Format :'+vdata);
		eventerror=true;
	}
}

function validtypedtime(mode,start,end,vdata)
{	
	value=PutCharR(charCode);
	value=value.substr(start,end);
	if(mode=='H')
		maxno=23;
	else 
		maxno=59
	if(charCode>47 && charCode<58) {
		if(parseInt(value)<=maxno){
			PutChar(charCode);
			return true;
		} else {
			seterror(KeyPressedElement,'Upto Max ('+maxno+') you can Enter Here. Time Format :'+vdata);
			eventerror=true;
			return false;
		}
	} else if(SelectionStart!=start) {
		value=KeyPressedElement.value;
		if(value.substr(SelectionStart,1)!=String.fromCharCode(charCode)){
			KeyPressedElement.setSelectionRange(SelectionStart-1,SelectionStart-1)
			PutChar(48);
			KeyPressedElement.setSelectionRange(SelectionStart+2,SelectionStart+2)
			PutChar(charCode);
		} else{
			KeyPressedElement.setSelectionRange(SelectionStart+1,SelectionStart+1)
		}
	}
	
}


function inarray(value,array1)
{
	for(li=0;li<array1.length;li++)
	{	
		if(array1[li]==value)
		{	return true; }
	}
	return false;
}


function PutChar(CharCode,Position)
{	if(CharCode==0)
		return false;
	Position=Position || 1;
	mode=KeyPressedElement.getAttribute('readonly');
	if(mode==null){
		tstr=KeyPressedElement.value;
		SelectionStart = getSelectionStart(KeyPressedElement);
		SelectionEnd = getSelectionEnd(KeyPressedElement);
		tstr=tstr.substr(0,SelectionStart)+String.fromCharCode(CharCode)+tstr.substr(SelectionEnd,tstr.length-SelectionEnd);
		KeyPressedElement.value=tstr;
		KeyPressedElement.setSelectionRange(SelectionStart+Position,SelectionStart+Position)
	} else {
		CharCode=0;
	}
}

function PutCharR(CharCode)
{	if(CharCode==0)
		return false;
	tstr=KeyPressedElement.value;
	tstr=tstr.substr(0,SelectionStart)+String.fromCharCode(CharCode)+tstr.substr(SelectionEnd,tstr.length-SelectionEnd);
	return tstr;
}

function PutString(string,Position)
{	Position=Position || 1;
	tstr=KeyPressedElement.value;
	SelectionStart = getSelectionStart(KeyPressedElement);
	SelectionEnd = getSelectionEnd(KeyPressedElement);
	tstr=tstr.substr(0,SelectionStart)+string+tstr.substr(SelectionEnd,tstr.length-SelectionEnd);
	KeyPressedElement.value=tstr;
	KeyPressedElement.setSelectionRange(SelectionStart+Position,SelectionStart+Position)
}

function PutStringR(string)
{
	tstr=KeyPressedElement.value;
	tstr=tstr.substr(0,SelectionStart)+string+tstr.substr(SelectionEnd,tstr.length-SelectionEnd);
	return tstr;
}


var keypress=function (e) {
	KeyPressedElement=e.target;
	charCode=e.charCode || e.which;
	if(e.ctrlKey==0 &&  e.altKey==0)
		ctrl=0;
	else
		ctrl=1;
	if(ctrl==0) {
		printable=e.charCode;
		type=e.target.type;
		if(typeof type!="undefined" && type!=null){
			cla=e.target.getAttribute('class');
			if(cla!=null){
				if(printable!=0 && (type=="text" || type=="password" || type=="textarea" )){
					KeyPressedElement=e.target;
					KeyPressedMaxLength=KeyPressedElement.getAttribute('maxlength');
					if(KeyPressedMaxLength==null)
						KeyPressedMaxLength=100000;
					KeyPressedValue=KeyPressedElement.value;
					SelectionStart = getSelectionStart(e.target);
					SelectionEnd = getSelectionEnd(e.target);
					if((KeyPressedValue.length<KeyPressedMaxLength || SelectionEnd!=SelectionStart)) {
						cla=explode(" ",cla);
						if(inarray('upper',cla)){
							upper(e); }
						if(inarray('lower',cla)){
							lower(e);			}
						if(inarray('ucwords',cla)){
							ucword(e);	return false;		}
						if(inarray('title',cla)){
							titles(e); return false;		}
						if(inarray('num',cla)){
							num(e); return false;		}
						if(inarray('alphaspace',cla)){
							alphaspace(e); return false;		}
						if(inarray('alphadot',cla)){
							alphadot(e); return false;		}
						if(inarray('alphacomma',cla)){
							alphacomma(e); return false;		}
						if(inarray('alphasymbols',cla)){
							alphasymbols(e); return false;		}
						if(inarray('alphanum',cla)){
							alphanums(e); return false;		}
							
						if(inarray('alpha',cla)){
							alpha(e); return false;		}
						if(inarray('date',cla)){
							check_date(e); return false;		}
							
						if(inarray('currency',cla)){
							currency(e); return false;		}
						if(inarray('percent',cla)){
							percent(e); return false;		}
						if(inarray('time',cla)){
							check_time(e); return false;		}
						if(inarray('check',cla)){
							validtext(e); return false;		}
						PutChar(charCode);
						e.preventDefault();
					}
				}
			} else {
				if(printable!=0)
					e.preventDefault();
			}
		}
	}
}

function paste(e)
{
	ClipBoardText=e.clipboardData.getData('text/plain');
	return;
	if (ClipBoardText.trim().length==0)
		return false;
	type=e.target.type;
	if(typeof type!="undefined" && type!=null){
		cla=e.target.getAttribute('class');
		if(cla!=null){
			if(type=="text" || type=="password" || type=="textarea"){
				KeyPressedElement=e.target;
				KeyPressedMaxLength=KeyPressedElement.getAttribute('maxlength');
				if(KeyPressedMaxLength==null)
					KeyPressedMaxLength=100000;
				KeyPressedValue=KeyPressedElement.value;
				SelectionStart = getSelectionStart(e.target);
				SelectionEnd = getSelectionEnd(e.target);
				if((KeyPressedValue.length<KeyPressedMaxLength || SelectionEnd!=SelectionStart)) {
					cla=explode(" ",cla);
					if(inarray('upper',cla)){
						upper_paste(e); }
					if(inarray('lower',cla)){
						lower_paste(e);			}
					if(inarray('ucwords',cla)){
						ucword_paste(e);
						return false;		}
					if(inarray('title',cla)){
						titles_paste(e); return false;		}
					if(inarray('num',cla)){
						num_paste(e); return false;		}
					if(inarray('alphaspace',cla)){
						alphaspace_paste(e); return false;		}
					if(inarray('alphadot',cla)){
						alphadot_paste(e); return false;		}
					if(inarray('alphacomma',cla)){
						alphacomma_paste(e); return false;		}
					if(inarray('alpha',cla)){
						alpha_paste(e); return false;		}
					if(inarray('alphasymbols',cla)){
						alphasymbols_paste(e); return false;		}
					if(inarray('currency',cla)){
						currency(e); return false;		}
					if(inarray('percent',cla)){
						percent(e); return false;		}
					if(inarray('time',cla)){
						check_time(e); return false;		}
					if(inarray('check',cla)){
						validtext(e); return false;		}
					PutChar(charCode);
					e.preventDefault();
				}
			}
		} else {
			if(printable!=0)
				e.preventDefault();
		}

	}

}

var keydown=function (e) {
	KeyPressedElement=e.target;
	eventerror=false;
	keyCode=e.keyCode || e.which;
    if (e.keyCode == 116){
      validNavigation = 1;
    }
	
}
var eventerror;
var keyup = function (e) {
keyCode=e.keyCode || e.which;

validevent(e);
e = e || window.event;
enablesubmit();
var k = e.keyCode || e.which;
	if(k==120) //F9
    {   	}
	if(k==119)  //F8
    {  	}
};
var prevelement;
var mdown=function(e){
	if(typeof e!="undefined" && e.target.type!="submit"){
		eventerror=false;
		enablesubmit();
		validNavigation = 1;
	}
	if(typeof e!="undefined" && e.target.type=="reset"){
		clearerrors();
	}
	if(typeof e!="undefined" && e.target.nodeName=="A"){
	    validNavigation = 1;
	}
	
	if(typeof e!="undefined" && e.target.type!=null){
			validevent(e);
	}
	};

//document.onkeydown = key;
document.onkeyup = keyup;

document.onkeypress = keypress;

document.onkeydown = keydown;

document.onpaste=paste;

//document.onmousedown= mdown;
document.onmouseup= mdown;

function saveevent(event)
{	if(typeof event.target.type!="undefined" && event.target.type!="" && event.target.type!="null")
		prevelement=event;
}

window.addEventListener('focus', function(event) { 
	if(typeof event.target.type!="undefined" )
		if(typeof event.target.setAttribute=="function" && event.target.type=="password"){
			//event.target.setAttribute("onpaste", "return false");
			//event.target.setAttribute("oncopy", "return false");
		}
		validevent(event);
		saveevent(event);
   }, true);

window.addEventListener('blur', function(event) { 
	if(typeof event.target.type!="undefined" ){
		validevent(event);
		saveevent(event);
	 }
   }, true);

window.addEventListener('change', function(event) { 
	if(typeof event.target!="undefined"){
		validsevent(event);
		saveevent(event);
	}
   }, true);


window.addEventListener('submit', function(event) { 
	eventerror=false;
	try{
		if(prevelement.target.type=="submit") {
			prevelement.target.setAttribute('disabled','disabled');
			submited=true;
			validNavigation = 1;
		}
	} catch(e){
	}
	formid=event.target.getAttribute('id')
	if(formid==null)
		{
			event.target.setAttribute('id','myvalidautogen');
			formid='myvalidautogen';
		}
	fromsubmit=true;
	isvalid=my_validate(formid);
	if(isvalid==false){
		fromsubmit=false;
		submited=false;
		event.preventDefault();	
		validNavigation=0;
	}
   }, true);

var namewiseerrors=new Array();
function my_validate(formid)
{
	namewiseerrors=new Array();
	ans=true;
	var allele=document.getElementById(formid).elements;
	for(il=0;il<allele.length;il++){
		res=validateelement(allele[il]);
		if(res.toString()=="false") 
			ans=false;
	}	
	namewiseerrors=new Array();
	return ans;
}

function check()
{
	var div2 = document.getElementById('tab1');
	namewiseerrors=new Array();
	for(i = j = 0; i < div2.childNodes.length; i++)
	if(div2.childNodes[i].nodeName=="DIV")
    if(div2.childNodes[i].nodeName == 'INPUT'){
 		validateelement(div2.childNodes[i]);	
    }	
	namewiseerrors=new Array();
}

	function getValue2Ajax(dataelement,joiner)
	{
		if(dataelement && (dataelement.substr(0,1)=="." || dataelement.substr(0,1)=="#")){
			result="";
			if(dataelement.substr(0,1)=="." ){
				var elements=document.getElementsByClassName(dataelement.substr(1));
				for(ix=0;ix<elements.length;ix++)
					if(elements.item(ix).nodeName=="INPUT" || elements.item(ix).nodeName=="SELECT" || elements.item(ix).nodeName=="TEXTAREA"  ){
						if(elements.item(ix).type=="checkbox" || elements.item(ix).type=="radio") {
							if(elements.item(ix).checked)
								result+="&"+elements[ix].getAttribute('name')+"="+elements[ix].value	
						} else {
								result+="&"+elements[ix].getAttribute('name')+"="+elements[ix].value	
						}
					} else {
						if(elements[ix].getAttribute('name')!=null)
							result+="&"+elements[ix].getAttribute('name')+"="+elements.item(ix).innerHTML	
					}
			} else {
				var element=document.getElementById(dataelement.substr(1));
					result+="&"+elements[ix].getAttribute('name')+"="+elements[ix].value	
			}
			return result;
		}
		return "";
	}

	function ClearValues(dataelement)
	{
		if(dataelement && (dataelement.substr(0,1)=="." || dataelement.substr(0,1)=="#")){
			if(dataelement.substr(0,1)=="." ){
				var elements=document.getElementsByClassName(dataelement.substr(1));
				for(ix=0;ix<elements.length;ix++)
					if(elements.item(ix).nodeName=="INPUT" || elements.item(ix).nodeName=="SELECT" || elements.item(ix).nodeName=="TEXTAREA"  ){
						if(elements.item(ix).type=="checkbox" || elements.item(ix).type=="radio") {
							elements.item(ix).checked=false;
						} else {
							elements[ix].value="";
						}
					} else {
						if(elements[ix].getAttribute('name')!=null)
							elements.item(ix).innerHTML="";
					}
			} else {
				var element=document.getElementById(dataelement.substr(1));
					element.value="";
			}
		}
		return true;
	}


function validateelement(element)
{
	if(typeof element.getAttribute!="function")
		return true;
	var visible=isOnScreen(element);
	if(!visible)
		return true;
	msg="";
	selected=0;	
	name=element.name;
	type=element.type;
	id=element.id;
	cals1=element.getAttribute('class');
	if(cals1!=null)
		cals1=cals1.split(' ');
	else{
		cals1=new Array();
		cals1[cals1.length]='';
	}
	errorelement=inarray('error',cals1)
	if(!fromsubmit && !errorelement && !ajaxsubmit)
		return true;
	if(typeof id=="undefined" || id==null || id=="" && name!="")
	{ 
		for(srno=1;srno<1000;srno++)
		{
			tid=name+'_'+srno;
			telement=document.getElementById(tid);
			if(typeof telement=="undefined" || telement==null){
				element.setAttribute('id',tid);
				id=tid;
				break;
			} 
		}
	}
	multiple=element.multiple;
	clas=element.getAttribute("class");
	required=0;
	if(typeof clas=="undefined" || clas==null)
	{ clas=""; }
	if(typeof clas!="undefined" && clas!=null && clas.indexOf('required')>=0){
			required=1;
	}
	minlength=element.getAttribute("minlength");
	if(typeof minlength=="undefined" || minlength==null && (required==1) ){
			minlength=1;
	}
	maxlength=element.getAttribute("maxlength");
	if(typeof minlength=="undefined" || minlength==null ){
		maxlength=0;
	}
	ph=element.getAttribute("placeholder");
	if((ph==null || typeof ph=="undefined") && ph!="")
		ph="";
	title=element.getAttribute("title");
	if((title==null || typeof title=="undefined") && title!="")
		title=""
	if(title!="")
		msg=title;
	if(msg=="")
		msg=ph;
	if(msg=="")
		msg="This field"
	msg=msg.replace('is required','');
	msg=msg.replace('Is Required','');
	msg=msg.replace('is Required','');
	msg=msg.replace('Is required','');
	msg=msg.replace('IS REQUIRED','');
	msg=msg.replace('are required','');
	msg=msg.replace('Are Required','');
	msg=msg.replace('are Required','');
	msg=msg.replace('Are required','');
	msg=msg.replace('ARE REQUIRED','');
	msg=msg.replace('required','');
	msg=msg.replace('Required','');
	msg=msg.replace('REQUIRED','');

	value="";
	show=0;
	errorfound=0;
	if(type!="button" && type!="submit" && type!="hidden" && name!=null && (required==1 || minlength>0)){
			show=1;
			if(type=="select-one"){
				value=element.options[element.selectedIndex].value;
				if(value.trim().length==0)
				{ errorfound=1;} 
				msg='Please Select '+msg+'.';
			}
			else if(type=="select-multiple") {
				for (x=0;x<element.length;x++) {
					if (element.options[x].selected) {
					 value+= "," + element[x].value;
					 selected++;
		           	}
         		}
				if(selected==0){
					msg='Please Select '+msg+'.';
					errorfound=1;
				}
				else if(selected<minlength){
					msg='Please Select Minimum '+minlength+'.';
					errorfound=1; 
				}
				if(selected>=minlength){
					if(value.length>=minlength)
						value=value.substr(1,(value.length-1));
					    errorfound=0;
				}
			}
			else if(type=="text"){
				value=element.value;
				if(clas.indexOf(' num')>0 || clas.indexOf(' currency')>0){
					dmax=element.getAttribute('data-max');
					dmin=element.getAttribute('data-min');
					dvalue=(isNaN(parseInt(value)))?0:parseInt(value);
					if(dmax!=null || dmin!=null) {
						if(value.trim().length==0){
							msg+=" Is Required.";
							errorfound=1;
						} else {
							if(typeof dmax!='undefined' && dmax!=null){
								dmax=(isNaN(parseInt(dmax)))?0:parseInt(dmax);
								if(dvalue>dmax){
									msg="Entered Value Exceeded. Maximum allowed upto "+dmax+".";	
									errorfound=1;
								}
							}
							if(typeof dmin!='undefined' && dmin!=null){
								dmin=(isNaN(parseInt(dmin)))?0:parseInt(dmin);
								if(dvalue<dmin){
									msg="Required Minimum Value is "+dmin+".";	
									errorfound=1;
								}
							}
						}
					} else {
						if(value.trim().length==0){
							msg+=" Is Required.";
							errorfound=1;
						}
						else if (value.trim().length<minlength){
							msg+=" Is Required Minimum "+minlength+" Characters.";
							errorfound=1;
						} else {
							errorfound=0;
						}
					}
				}else if(clas.indexOf('mail')>0 || clas.indexOf('unique')>0){
					title=(title.length==0)?"Data":title;
					if(value.trim().length==0){
						msg+=" Is Required.";
					    errorfound=1;
					}
					else if (value.trim().length>0 && clas.indexOf('mail')>0 && isMail(value).toString()=="false"){
						msg="Please Enter Valid "+title+".";	
						errorfound=1;
					} else {
						clas=document.getElementById(id).getAttribute('class');
						if(clas.indexOf('exist')>0){
							msg=title+" Already Exists.";
							errorfound=1;
						} else {
							errorfound=0;
						}
					}
				}else if(clas.indexOf('url')>0){
					title=(title.length==0)?"Url ":title;
					if(value.trim().length==0){
						msg+=" Is Required.";
					    errorfound=1;
					}
					else if (value.trim().length>0 && clas.indexOf('url')>0 && isUrl(value).toString()=="false"){
						msg="Please Enter Valid "+title+". <span style='color:#000;'>Ex. hhtp://www.example.com</span>";	
						errorfound=1;
					} else {
						msg="";
						errorfound=0;
					}
				} else {
					if(value.trim().length==0){
						msg+=" Is Required.";
						errorfound=1;
					}
					else if (value.trim().length<minlength){
						msg+=" Is Required Minimum "+minlength+" Characters.";
						errorfound=1;
					} else {
						errorfound=0;
					}
				}
			} else if(type=="textarea"){
				value=element.value;
				if(value.trim().length==0){
					msg+=" Is Required.";
					errorfound=1;
				}
				else if (value.trim().length<minlength){
					msg+=" Is Required Minimum "+minlength+" Characters.";
					errorfound=1;
				} else {
					errorfound=0;
				}
			} else if(type=="password"){
					value=element.value;
					eqalto=element.getAttribute('equalto');
					if(eqalto!=null && eqalto.lengyh!=0 ){
						if(msg=='This field')
							msg="Confrim Password";
						eqalto=eqalto.replace('#','');
						passval=document.getElementById(eqalto);
						if(passval!=null){
							if(passval.value.trim().length==0){
								msg+=" Is Required.";
								errorfound=1;
							} else if(passval.value!=value){
								msg="Please Enter Valid Confrim Password";
								errorfound=1;
							} else {
								msg=""
								errorfound=0;
							}
						}else {
							msg+=" Is Required.";
							errorfound=1;
						}
					} else {
						if(msg=='This field')
							msg="Password";
						if(value.trim().length==0){
							msg+=" Is Required.";
							errorfound=1;
						} else if(value.trim().length<minlength){
							msg+=" Is Required Minimum "+minlength+" Characters.";
							errorfound=1;
						} else {
							errorfound=0;
						}
					}
			}else if(type=="file"){
					value=element.value;
					if(value.trim().length==0){
						msg+=" Is Required.";
						errorfound=1;
					} else {
						var typs=element.getAttribute('data-type');
						var atyps=new Array();
						if(typeof typs!='undefined' && typs!=null && typs.length!=0){
							atyps=typs.split(",");
							if(atyps.length>0){
								var ext=value.substr(value.lastIndexOf(".")+1,value.length-(value.lastIndexOf(".")+1));
							if(!inarray(ext,atyps)){
								msg=ext+" File Type Not Allowed. Alows Only ("+atyps.join()+") Types.";
								errorfound=1;
							}
							}else{
								errorfound=0;
							}
						}else {
							errorfound=0;
						}
					}
			}
			//alert('type='+type+'\nph='+ph+'\nminlength='+minlength+'\nmaxlength='+maxlength+'\nid='+id+'\nValue='+value);	
	} else { 
		if(element.nodeName!="LABEL"){
				removeerror(element);}
	}
	
	if(type=="checkbox" || type=="radio"){
		chkele=getmainelement(name,type);
		if(typeof chkele!="undefined" ){
			msg=chkele.getAttribute('title');
			minlength=chkele.getAttribute('minlength');
			if(minlength==null)
				minlength=0;
		}
	}

	if(required==0 && minlength>0 && name!=null && type!="button" && type!="submit" && type!="hidden" ){
		show=1;
		if(type=="radio"){	
			ele=document.getElementsByName(name);
			for(x=0;x<ele.length;x++){
				if(ele[x].checked.toString()=="true"){
						value="1";
				}
			}
			if(msg==null)
				msg="";
			msg='Please Select Any One '+msg+'.';
			if(value!="1")
				errorfound=1;
		}
		if(type=="checkbox"){
			selected=getselectedlength(name,type)
			if(selected==0){
			value="";
			msg='Please Select '+msg+'.';
			errorfound=1;
			show=1;
		}
		else if(selected<minlength){
			value="";
			msg='Please Select Minimum '+minlength+'.';
			errorfound=1;
			show=1;
		}
		else if(selected>=minlength){
			value="1";
			errorfound=0;
		}
		}
	}
	if(errorfound==1 && show==1){
		seterror(element,msg);
		return false;
	} else if(errorfound==0 && show==1) {
		removeerror(element);
		return true;
	} else{
		return true;
	}
	
//	.nodeName
}


function isOnScreen(element)
{
	if(element.offsetHeight==0)
		return false;
	else
		return true;
}

function explode(needle,str)
{
	var result=new Array();
	str=str.split(needle);
	for(x=0;x<str.length;x++)
	{
		if(str[x].trim().length!=0)
			result[result.length]=str[x].trim();
	}
	return result;	
}

function impplode(needle,array)
{
	var result="";
	for(x=0;x<array.length;x++)
	{
		result+=array[x].trim()+needle;
	}
	return result.trim();	
}


function addClass(element,newclass)
{
	if(typeof element!="undefined"  && typeof element.getAttribute=="function" ){
		tstr="";
		clas=element.getAttribute("class");
		if(clas!=null && clas!=""){
			clas=explode(" ",clas);
			for(x=0;x<clas.length;x++)
			{
				if(clas[x]!=newclass)
					tstr+=' '+clas[x];
			}
		}
		tstr+=' '+newclass;
		element.setAttribute('class',tstr);
	}
}
function removeClass(element,newclass)
{	
	if(typeof element!="undefined" && typeof element.getAttribute=="function" ){
		tstr="";
		clas=element.getAttribute("class");
		if(clas!=null && clas!=""){
			clas=explode(" ",clas);
			for(x=0;x<clas.length;x++)
			{
				if(clas[x]!=newclass)
					tstr+=' '+clas[x];
			}
		}
		element.setAttribute('class',tstr);
	}
}


function getmainelement1(elementname)
{
	ele=document.getElementsByName(elementname);
	for(x=0;x<ele.length;x++){
		ml=ele[x].getAttribute('minlength')
		if(typeof ml!="undefined" && ml>0) {
			return ele[x];
		}
	}
}

function getmainelement(elementname,elementtype)
{		
	var e = document.getElementsByTagName('input');//.type == elementtype;
	if(elementname.indexOf('[')>0)
		elementname=elementname.substr(0,elementname.indexOf('['));
	var i;
	for (i=0;i<e.length;i++)
	{
		if(typeof e[i].type!="undefined" && e[i].type==elementtype ){
			if(elementtype=="checkbox") {
				telementname=e[i].name.substr(0,e[i].name.indexOf('['));
			} else {
				telementname=e[i].name;
			}
			if(telementname==elementname){
				ml=e[i].getAttribute('minlength')
				if(typeof ml!="undefined" && ml>0) {
					return e[i];
				}
			}
		}
	}
	if(e.length>0)
		return e[0];
}

function getselectedlength(elementname,elementtype)
{		
	var e = document.getElementsByTagName('input');//.type == elementtype;
	elementname=elementname.substr(0,elementname.indexOf('['));
	var slength=0;
	for (i=0;i<e.length;i++)
	{
		if(typeof e[i].type!="undefined" && e[i].type==elementtype ){
			telementname=e[i].name.substr(0,e[i].name.indexOf('['));
			if(telementname==elementname && e[i].checked==true){
				slength++;
			}
		}
			
	}
	return slength;
}

	function setautoid(element)
	{
		id=element.getAttribute('id');
		if(typeof id=="undefined" || id==null || id=="")
		for(srno=1;srno<1000;srno++)
		{
			tid='auto_'+srno;
			telement=document.getElementById(tid);
			if(typeof telement=="undefined" || telement==null){
				element.setAttribute('id',tid);
				id=tid;
				break;
			} 
		}
		return id;
	}
var mindivwidth=100;	
	function seterror(element,msg)
	{
		name=element.name;	
		type=element.type;
		if(typeof element.getAttribute!="function")
			return false;
		if(type=="checkbox" || type=="radio"){
			ele=getmainelement(name,type);
			if(typeof ele!="undefined"){
				id=setautoid(ele);
			} else {
				return false;
			}
		} else {
			id=setautoid(element);
		}
		var errorlbl = document.createElement('label');
		errorlbl.setAttribute('class','error');
		errorlbl.setAttribute('for',id);
		errorlbl.setAttribute('id','err_'+id);
		errorlbl.innerHTML=msg;
		addClass(element,'error');
		errorelement=document.getElementById("err_"+id);
		if(typeof errorelement=="undefined" || errorelement==null){
			
			if(element.parentNode.offsetWidth < mindivwidth){
				pele=element.parentNode
				wid=pele.offsetWidth;
				while(wid < mindivwidth){
				wid=pele.offsetWidth;
				pele=pele.parentNode;
				}
				pele.appendChild(errorlbl);
				errorlbl.setAttribute('class','error');
			}else {
				element.parentNode.appendChild(errorlbl);
				errorlbl.setAttribute('class','error');
			}
		}
		else{
			errorelement.innerHTML=msg;
			errorelement.setAttribute('class','error');
			addClass(errorelement,'error');
		}
		setStyle(id);
	}

	function removeerror(element)
	{	
		if(typeof element!="undefined") {
			type=element.type;
			if(type=="checkbox" || type=="radio"){
				ele=getmainelement(name,type);
				if(typeof ele!="undefined"){
					id=ele.id;
				} else {
					return false;
				}
			} else {
				id=element.id;
			}
			if(!eventerror){
				id='err_'+id;
				removeClass(element,'error');
				var ele=document.getElementById(id);
				var pelement=element.parentNode;
				if(typeof ele!="undefined" && ele!=null){
					ele.innerHTML="";
					if(typeof pelement!="undefined" && pelement!=null) {
						for(x=0;x<pelement.childNodes.length;x++){
							if(pelement.childNodes[x]==ele){
								pelement.removeChild(ele);
							}
						}
					}
				}
			}
		}
	}

	var labelerrstyel="";
	var errstyle="";
	var beforeerrstyle;


var CssAdded=false;	
var AlertCssAdd=false;
	function addCsstoHead()
	{
		if(!CssAdded){
			var css ='label.error { color:#F00; display:block; font-family:Arial; font-size:10px; } input.error,select.error,textarea.error { /* color:#F00; */border:1px solid #F00; }';
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');
			style.type = 'text/css';
			if (style.styleSheet){
			  style.styleSheet.cssText = css;

			} else {
			  style.appendChild(document.createTextNode(css));
			}
			head.appendChild(style);
		}
	}
	function addAlertCsstoHead()
	{
		if(!AlertCssAdd){
			var css ='#modalContainer { 	background-color:transparent; 	position:absolute; 	width:100%; 	height:100%; 	top:0px; 	left:0px; 	z-index:10000; 	background-image:url(tp.png); /* required by MSIE to prevent actions on lower z-index elements */  } #alertBox { 	position:relative; 	width:300px; 	min-height:100px; 	margin-top:50px; 	border:2px solid #000; 	background-color:#F2F5F6; 	background-image:url(alert.png); 	background-repeat:no-repeat; 	background-position:20px 30px; } #modalContainer > #alertBox { 	position:fixed; }   #alertBox h1 { 	margin:0; 	font:bold 0.9em verdana,arial; 	background-color:#78919B; 	color:#FFF; 	border-bottom:1px solid #000; 	padding:2px 0 2px 5px; } #alertBox p { 	font:0.7em verdana,arial; 	height:50px; 	padding-left:5px; 	margin-left:55px; } #alertBox #closeBtn { 	display:block; 	position:relative; 	margin:5px auto; 	padding:3px; 	border:2px solid #000; 	width:70px; 	font:0.7em verdana,arial; 	text-transform:uppercase;	text-align:center;	color:#FFF;	background-color:#78919B;	text-decoration:none;} /* unrelated styles */ #mContainer { 	position:relative; 	width:600px; 	margin:auto; 	padding:5px; 	border-top:2px solid #000; 	border-bottom:2px solid #000; 	font:0.7em verdana,arial; } code { 	font-size:1.2em; 	color:#069; }  #credits { 	position:relative; 	margin:25px auto 0px auto; 	width:350px;  	font:0.7em verdana; 	border-top:1px solid #000; 	border-bottom:1px solid #000; 	height:90px; padding-top:4px; }  #credits img { 	float:left;	margin:5px 10px 5px 0px;	border:1px solid #000000;	width:80px;	height:79px;}.important {	background-color:#F5FCC8;	padding:2px;} code span { 	color:green; } </style>';
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');
			style.type = 'text/css';
			if (style.styleSheet){
			  style.styleSheet.cssText = css;
			} else {
			  style.appendChild(document.createTextNode(css));
			}
			head.appendChild(style);
		}
	}
	
	function toHex(n) 
	{
		 n = parseInt(n,10);
		 if (isNaN(n)) return "00";
		 n = Math.max(0,Math.min(n,255));
		 return "0123456789ABCDEF".charAt((n-n%16)/16)
			  + "0123456789ABCDEF".charAt(n%16);
	}
	function rgbToHex(colorcode) 
	{	
			y=colorcode.substr(4,colorcode.length-5);
			y=y.split(',');
			y=rgbToHex(y[0],y[1],y[2]);
			beforeerrstyle="color:#"+y+';';

		return toHex(R)+toHex(G)+toHex(B);
	}

	function setStyle(id)
	{	
		if(!CssAdded){
			labelerrstyel=" ";
			styleProp="color";
			element=document.getElementById("err_"+id);
			if (element.currentStyle)
				var y = element.currentStyle[styleProp];
			else if (window.getComputedStyle)
				var y = document.defaultView.getComputedStyle(element,null).getPropertyValue(styleProp);
			if(y!='rgb(255, 0, 0)'){
				addCsstoHead();
			}
		}
		CssAdded=true;

	}	
	
	function isMail(mailid)
	{
		if(mailid.lastIndexOf('@')<mailid.lastIndexOf('.') && mailid.lastIndexOf('.')<mailid.length-1) {
			//var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var filter=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
			return filter.test(this.value)
		} else {
			return false;
		}
	}

	function isUrl(url)
	{
		var filter= /(http|https|ftp)+[:]+[//]+[//]+([w]{3})+\.+\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		return filter.test(url)
	}

	function founderrors()
	{
		var e = document.getElementsByTagName('*');
		ans=false;
		for (i=0;i<e.length;i++)
		{
			if(typeof e[i].type!="undefined" && typeof e[i].getAttribute=="function" && isOnScreen(e[i])){
				cla=e[i].getAttribute('class')
				if(cla!=null && cla.indexOf('error')>=0){
					submited=false;
					validNavigation=0;
					return true;
				}
			}
		}
		return ans;		
	}

	function clearerrors()
	{
	
		var e = document.getElementsByTagName('*');
		ans=false;
		for (i=0;i<e.length;i++)
		{
			cla=e[i].getAttribute('class')
			if(cla!=null && cla.indexOf('error')>=0){
				if(e[i].nodeName=="LABEL")
					e[i].remove(0)
				else
					removeClass(e[i],'error');	
			}
		}
	}


	function validateByDiv(divid)
	{	ans=false;
		ajaxsubmit=true;
		var e = document.getElementById(divid).getElementsByTagName('*');
		var i;
		for (i=0;i<e.length;i++)
		{
			if(typeof e[i].type!="undefined"){
				an=validateelement(e[i]);
				if(an.toString()=="false")
					ans=true;
				}
		}
		ajaxsubmit=false;
		return ans;
	}
	
	function checkfile(fname)
	{
		var ext=fname.substr(fname.lastIndexOf('.')+1,fname.length-fname.lastIndexOf('.')+1);
		if(ext.trim().toUpperCase()!='XML'){
			document.getElementById('coursexml').value="";
			alert('Please Select XML File.');
		}
	}

	function ajax(url,data,type)
	{
		var http=new XMLHttpRequest();
		sethttp(http,url,data,type)
		http.onreadystatechange = function() {//Call a function when the state changes.
			if(http.readyState == 4 && http.status == 200) {
				alert(http.responseText);
			}
		}
	}
	
	function sethttp(http,url,data,type)
	{
		if(url.length=0){
			alert('Ajax Call With out BASE_URL');
			return false;
		}
		if(type.length=0){
			type="GET";
		}
		var params = buildparams(data);
		if(type=="GET")
			http.open("GET", url+'?'+params, true);
		else
			http.open("POST", url, true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Content-length", params.length);
		http.setRequestHeader("Connection", "close");
		if(type=="GET")
			http.send(null);
		else
			http.send(params);		
		
	}
	
	function buildparams(data)
	{	
		data=data.replaceAll(',','&');
		data=data.replaceAll("'",'');
		data=data.replaceAll("{",'');
		data=data.replaceAll("}",'');
		data=data.replaceAll(":",'=');
		return data
	}

	// constants to define the title of the alert and button text.
	var ALERT_TITLE = "Oops!";
	var ALERT_BUTTON_TEXT = "Ok";
	
	// over-ride the alert method only if this a newer browser.
	// Older browser will see standard alerts
/*	if(document.getElementById) {
		window.alert = function(txt) {
			createCustomAlert(txt);
		}
	}
*/	
	function createCustomAlert(txt) {
		// shortcut reference to the document object
		d = document;
	
		// if the modalContainer object already exists in the DOM, bail out.
		if(d.getElementById("modalContainer")) return;
	
		// create the modalContainer div as a child of the BODY element
		mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
		mObj.id = "modalContainer";
		 // make sure its as tall as it needs to be to overlay all the content on the page
		mObj.style.height = document.documentElement.scrollHeight + "px";
	
		// create the DIV that will be the alert 
		alertObj = mObj.appendChild(d.createElement("div"));
		alertObj.id = "alertBox";
		// MSIE doesnt treat position:fixed correctly, so this compensates for positioning the alert
		if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
		// center the alert box
		alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
	
		// create an H1 element as the title bar
		h1 = alertObj.appendChild(d.createElement("h1"));
		h1.appendChild(d.createTextNode(ALERT_TITLE));
	
		// create a paragraph element to contain the txt argument
		msg = alertObj.appendChild(d.createElement("p"));
		msg.innerHTML = txt;
		
		// create an anchor element to use as the confirmation button.
		btn = alertObj.appendChild(d.createElement("a"));
		btn.id = "closeBtn";
		btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
		btn.href = "#";
		// set up the onclick event to remove the alert when the anchor is clicked
		btn.onclick = function() { removeCustomAlert();return false; }
	
		
	}
	
	// removes the custom alert from the DOM
	function removeCustomAlert() {
		document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
	}

	function moveout(fromid,toid,hiddenid,mode)
	{
		mode=mode||"+";
		var select = document.getElementById(fromid);
		value = select.selectedIndex;
		if(value!=-1) {
			stext=select[value].text;
			ival=select[value].value;
			select.removeChild(select[value]);
			var opt = document.createElement('option');
			opt.value = ival;
			opt.innerHTML = stext;
			if(typeof hiddenid!='undefined' && hiddenid!=null ){
				var val=document.getElementById(hiddenid).value;
				if(mode=="+")
					val+=' '+ival+',';
				else
					val.replace(' '+ival,'');
				val.replace(',,',',');
				val.substr(0,val.length-1);
				document.getElementById(hiddenid).value=val;
			}
			var select = document.getElementById(toid);
			select.appendChild(opt);
		}
	}

	function myAlert(text,width,height)
	 {
			
		 
		 width=width || 550;
		 height=height || 300;
		 var maxw=screen.availWidth-50;
		 var maxh=screen.availHeight-50;
		 if(width>maxw)
		 	width=maxw-50;
		 if(height>maxh)
		 	height=maxh-50;
		 var swid=(screen.availWidth-width)/2;
		 var shei=(screen.availHeight-height)/2;
		alertHtml='<div id="MyAlert" style="width:100%; z-index:100000; position:fixed; left:0px; top:0px; height:100%; background:rgba(0,0,0,0.2);">';
		alertHtml+='<div  id="MyAlertI" style=" position:fixed; box-shadow: 10px 10px 5px #888888; left:'+(swid-25)+'px; top:'+(shei-25)+'px; border:1px solid #000; width:'+width+'px; height: '+(height-50)+'px;   padding:20px 20px '+(height-50)+'px 40px; background:#FFF; border-radius:3px!important; z-index:100000; ">';
		alertHtml+='<img src="http://dreamleads.se/auth/assets/js/fancybox/fancy_close.png" title="Close" style="position:absolute; top:12px; right:-12px; margin-top:-25px; cursor:pointer;" onclick="removeAlert();">';
		alertHtml+='<div style="font-size:16px; overflow:auto; margin-bottom:10px; min-height:90px; max-height:'+(height-100)+'px;">'+text+'</div>';
		alertHtml+='<div><div align="center"><input type="button" class="btn btn-info" style="padding:8px 20px; background-color:#09F; border:none; cursor:pointer; color:#fff; font-weight:bold;" title="Close" onclick="removeAlert();" value="Ok"></div></div>';
		alertHtml+="</div></div>";
		document.body.innerHTML=document.body.innerHTML+alertHtml;
	 }
	
	function removeAlert()
	{
		$('#MyAlert').remove();
	}	
	
		
		function Subscribe(validatediv,elemtnsclass)
		{
			validatediv=validatediv || 'subscribe';
			elemtnsclass=elemtnsclass || 'subscribe';
			var url1=$('#'+validatediv).attr('data-url');
			if(url1==null || url1==""){
				alert('Data Url is required');
				return false;
			}			
			if(!validateByDiv(validatediv)) {
				sdata=getValue2Ajax('.'+elemtnsclass);
				$.ajax({
					type: "POST",
					dataType: 'json',
					url: burl+'welcome/Subscribe',
					data: sdata,
					success: function(json) {
						if(json=="1"){
							msg='Thank you for Subscribe Newsletter.';
						} else {
							msg='Your Un-Subscripted form Newsletter.';
						}
						if(document.getElementById(validatediv+'_result')) {
							$('#'+validatediv+'_result').html(msg);
							$('#'+validatediv+'_result').show();
							$('#'+validatediv+'_result').delay(8000).fadeOut(400);
						} else {
							myAlert(msg);
						}
						ClearValues('.'+elemtnsclass);
					}
				});
			}
		}

    
		function sendEnquiry(validatediv,elemtnsclass)
		{
			validatediv=validatediv || 'contactus';
			elemtnsclass=elemtnsclass || 'contactus';
			var url1=$('#'+validatediv).attr('data-url');
			if(url1==null || url1==""){
				alert('Data Url is required');
				return false;
			}
			if(!validateByDiv(validatediv)) {
				sdata=getValue2Ajax('.'+elemtnsclass);
				$.ajax({
					type: "POST",
					dataType: 'json',
					url: burl+url1,
					data: sdata,
					success: function(json) {
						if(json.error=="0"){
							if(document.getElementById(validatediv+'_result')) {
								$('#'+validatediv+'_result').html('Your message was sent successfully. Thanks.');
								$('#'+validatediv+'_result').show();
								$('#'+validatediv+'_result').delay(8000).fadeOut(400);
							} else {
								alert('Your message was sent successfully. Thanks.');
							}
							ClearValues('.'+elemtnsclass);
						} else {
							$('#captcha_pic').attr('src',json.captcha);
							alert(json.errormsg);
						}
					}
				});
				return false;
			}
		}

		function sendComment(validatediv,elemtnsclass)
		{
			validatediv=validatediv || 'contactus';
			elemtnsclass=elemtnsclass || 'contactus';
			var url1=$('#'+validatediv).attr('data-url');
			if(url1==null || url1==""){
				alert('Data Url is required');
				return false;
			}
			if(!validateByDiv(validatediv)) {
				sdata=getValue2Ajax('.'+elemtnsclass);
				$.ajax({
					type: "POST",
					dataType: 'json',
					url: burl+url1,
					data: sdata,
					success: function(json) {
						if(json.error=="0"){
							if(document.getElementById(validatediv+'_result')) {
								$('#'+validatediv+'_result').html('Your message was sent successfully. Thanks.');
								$('#'+validatediv+'_result').show();
								$('#'+validatediv+'_result').delay(8000).fadeOut(400);
							} else {
								alert('Your message was sent successfully. Thanks.');
							}
							ClearValues('.'+elemtnsclass);
						}
						else
						{
							$('#captcha_pic').attr('src',json.captcha);
							alert(json.errormsg);
						}
					}
				});
			}
		}


	function loadImages(element,funct)
	{
		files=element.files;
		for (var i = 0; i < files.length; i++) {
			var file = files[i];
			var imageType = /image.*/;
			if (!file.type.match(imageType)) {
				continue;
        }
			var img = document.getElementById(id);
		   /* img.src = file;
			img.onload = function () {
			}; */
			var reader = new FileReader();
			reader.onload = (function (aImg) {
				return function (e) {
					aImg.src = e.target.result;
				};
			})(img);
			reader.readAsDataURL(file);
		}
	}

	function loadFile(file,id) {
			var imageType = /image.*/;
	        var img = document.getElementById(id);
			var reader = new FileReader();
			reader.onload = (function (aImg) {
				return function (e) {
					aImg.src = e.target.result;
				};
			})(img);
			reader.readAsDataURL(file);
	}
	
	
  	function base64_encode(data) {
		var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
		var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
		ac = 0,
		enc = '',
		tmp_arr = [];
		
		if (!data) {
			return data;
		}
		do { // pack three octets into four hexets
			o1 = data.charCodeAt(i++);
			o2 = data.charCodeAt(i++);
			o3 = data.charCodeAt(i++);
			bits = o1 << 16 | o2 << 8 | o3;
			h1 = bits >> 18 & 0x3f;
			h2 = bits >> 12 & 0x3f;
			h3 = bits >> 6 & 0x3f;
			h4 = bits & 0x3f;
			// use hexets to index into b64, and append result to encoded string
			tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
		} while (i < data.length);
		enc = tmp_arr.join('');
		var r = data.length % 3;
		return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
	}

	
	var html_entity_decode = function(str) {
 		 return str.replace(/&#(\d+);/g, function(match, dec) {
		    return String.fromCharCode(dec);
		  });
	};
 
	var html_entity_encode = function(str) {
	  var buf = [];
	  for (var i=str.length-1;i>=0;i--) {
		buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
	  }
	  return buf.join('');
	};

	var html_entities = function(str) {
	  var buf = [];
	  for (var i=str.length-1;i>=0;i--) {
		buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
	  }
	  return buf.join('');
	};		

function FileUpload(element,funct)
{
	var formname="myUploadForm";
	var form = document.createElement("FORM");
	form.setAttribute("id", formname);
	form.setAttribute("name", formname);
	form.setAttribute("method", "POST");
	form.setAttribute("enctype", "multipart/form-data");
	document.body.appendChild(form);

	var nameElement = document.createElement("INPUT");
	nameElement.setAttribute("type", "hidden");
	nameElement.setAttribute("value", element.getAttribute('name'));
	nameElement.setAttribute("name", "name");
	document.getElementById(formname).appendChild(nameElement);

/*
	var fileElement = document.createElement("INPUT");
	fileElement.setAttribute("type", "file");
	fileElement.setAttribute("name", element.getAttribute('name'));
	//fileElement.setAttribute("value", element.value);
	fileElement.files=element.files;
	document.getElementById(formname).appendChild(fileElement);
*/
		var oldElement = jQuery('#' + element.getAttribute('id'));
		var newElement = jQuery(oldElement).clone();
		jQuery(oldElement).attr('id', 'fileId');
		jQuery(oldElement).before(newElement);
		$('#'+formname).append(newElement);

/*	var node=element.cloneNode(true);
	document.getElementById(formname).appendChild(node);
	*/
	var durl=element.getAttribute('data-url');
	var title=element.getAttribute('title');
	var name=element.name;
	title=title||'';
	if(durl!=null && durl!=""){
		var 
		oData = new FormData(document.forms.namedItem(formname));
		var oReq = new XMLHttpRequest();
		oReq.open("POST",  durl, true);
		oReq.onload = function(oEvent) {
			var arraybuffer  = oReq.response;
			if(funct!=""){
				var fn = window[funct];
				if (typeof fn === "function")
					fn(arraybuffer);      
				}
				if (oReq.status == 200) {
				//alert("Uploaded!");
				} else {
				//alert("Error " + oReq.status + " occurred uploading your file.<br \/>");
				}
		};
		oReq.send(oData);
   } else {
	    alert('data-url attribute missing');
   }
}		