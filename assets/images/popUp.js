<!-- Begin
function NewWindow(mypage, myname, w, h, scroll) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
if (myname=='Set') wint=0;
if (myname=='Set2') {wint=0;winl=0;}
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resize=no'
win = window.open(mypage, myname, winprops)
if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function popFullScreen(Url){

			//alert ("huhuy");
			w=window.parent.screen.width;
			h=window.parent.screen.height;	
			jendela=window.open("","jendela","menubar=no,resizable=yes,scrollbars=yes, width=" + (w-15) + ", height=" + (h-60));
			jendela.location.replace(Url);
			jendela.moveTo(0,0);
if (parseInt(navigator.appVersion) >= 4) { jendela.window.focus(); }
			//window.jendela.moveTo(0,0);
}

function popFullScreen2(Url){
//erik 21 mei 2010
window.open(Url, '', 'fullscreen=yes,menubar=no,resizable=yes,scrollbars=yes');
}

function popFullScreen_dgn_menubar(Url){
//erik 21 mei 2010
window.open(Url, '', 'fullscreen=yes,menubar=yes,resizable=yes,scrollbars=yes');
}

function popFullPlasma(Url){

			//alert ("huhuy");
			w=window.parent.screen.width;
			h=window.parent.screen.height;	
			jendela=window.open("","jendela","menubar=no,resizable=yes,scrollbars=yes, width=" + (w-15) + ", height=" + (h-60));
			jendela.location.replace(Url);
			jendela.moveTo(0,0);
if (parseInt(navigator.appVersion) >= 4) { jendela.window.focus(); }
			//window.jendela.moveTo(0,0);
}
//  End -->
