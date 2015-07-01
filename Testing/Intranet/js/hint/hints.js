// Title: Tigra Hints
// URL: http://www.softcomplex.com/products/tigra_hints/
// Version: 2.1.1
// Date: 09/03/2007
// Note: This script is free for any kind of applications
//	The development of this software is funded by your donations

function THints(a_items,a_cfg){if(!a_items)a_items=[];if(!a_cfg)a_cfg=[];this.a_cfg=a_cfg;this.a_elements=[];this.a_hints=[];this.show=f_hintShow;this.showD=f_hintShowNow;this.hide=f_hintHide;this.hideD=f_hintHideNow;this.n_id=A_HINTS.length;A_HINTS[this.n_id]=this;if(!b_ie5&&!b_ie6)a_cfg.IEfix=false;for(var S in a_items){S=String(S).replace(/\W/g,'');document.write('<div style="position:absolute;left:0;top:0;visibility:hidden;z-index:',((a_cfg['z-index']==null?2:a_cfg['z-index'])+(a_cfg.IEfix?1:0)),';',(a_cfg.IEtrans?'filter:'+a_cfg.IEtrans.join(' '):''),(a_cfg.opacity?' alpha(opacity='+a_cfg.opacity+'); -moz-opacity:'+(a_cfg.opacity/100)+';opacity:'+(a_cfg.opacity/100)+'':''),'" id="h',this.n_id,'_',S,'" class="',(this.a_cfg.css?this.a_cfg.css:'tigraHint'),'" onmouseover="A_HINTS[',this.n_id+'].show(\'',S,'\')" onmouseout="A_HINTS[',this.n_id,'].hide(\'',S,'\')" onmousemove="f_onMouseMove(event)">',a_items[S],'</div>');if(a_cfg.IEfix)document.write('<iframe style="position:absolute;left:0;top:0;visibility:hidden;z-index:',(a_cfg['z-index']==null?2:a_cfg['z-index']),';filter:alpha(opacity=0);" id="h',this.n_id,'_',S,'_if" frameborder="0" scrolling="No"></iframe>');}if(document.addEventListener){document.addEventListener('mousemove',f_onMouseMove,false);window.addEventListener('scroll',f_onwindowChange,false);window.addEventListener('resize',f_onwindowChange,false);}if(window.attachEvent){document.attachEvent('onmousemove',f_onMouseMove);window.attachEvent('onscroll',f_onwindowChange);window.attachEvent('onresize',f_onwindowChange);}else{document.onmousemove=f_onMouseMove;window.onscroll=f_onwindowChange;window.onresize=f_onwindowChange;}}var K=false;function f_hintShow(S,G){if(this.e_timer){clearTimeout(this.e_timer);this.e_timer=null;}var S=String(S).replace(/\W/g,'');if(!this.a_hints[S])this.a_hints[S]=getElement('h'+this.n_id+'_'+S);if(!this.a_hints[S])this.a_hints[S]=getElement(S);if(!this.a_hints[S])throw new Error('001','Can not find the hint with ID='+S);this.a_elements[S]=G;var P=this.a_cfg.show_delay==null?200:this.a_cfg.show_delay;if(!P)return this.showD(S,G);this.e_timer=setTimeout('A_HINTS['+this.n_id+'].showD("'+S+'")',P);}function f_hintShowNow(S,G){if(S==this.o_lastHintID)return;if(G)this.a_elements[S]=G;if(this.o_lastHintID!=null)this.hideD(this.o_lastHintID);this.o_lastIframe=getElement('h'+this.n_id+'_'+S+'_if');if(this.o_lastIframe)this.o_lastIframe.style.visibility='visible';f_hintPosition(this.a_elements[S],this.a_hints[S],this.a_cfg);if(this.a_cfg.IEtrans&&this.a_cfg.IEtrans[0]){try{var D=this.a_hints[S].filters.item(0);D.apply();this.a_hints[S].style.visibility='visible';D.play();}catch(e){this.a_hints[S].style.visibility='visible';};}else{this.a_hints[S].style.visibility='visible';}this.o_lastHintID=S;}function f_hintHide(S){if(this.e_timer){clearTimeout(this.e_timer);this.e_timer=null;}if(S!=null)S=String(S).replace(/\W/g,'');else if(this.o_lastHintID)S=this.o_lastHintID;else return;if(!this.a_hints[S])throw new Error('001','Can not find the hint with ID='+S);var L=this.a_cfg.hide_delay==null?200:this.a_cfg.hide_delay;if(!L)return this.hideD(S);this.e_timer=setTimeout('A_HINTS['+this.n_id+'].hideD("'+S+'")',L);}function f_hintHideNow(S){if(this.a_cfg.IEtrans&&this.a_cfg.IEtrans[1]){try{var D=this.a_hints[S].filters.item(this.a_cfg.IEtrans[0]?1:0);D.apply();this.a_hints[S].style.visibility='hidden';D.play();}catch(e){this.a_hints[S].style.visibility='hidden';};}else this.a_hints[S].style.visibility='hidden';this.o_lastHintID=null;if(this.o_lastIframe){this.o_lastIframe.style.visibility='hidden';this.o_lastIframe=null;}}function f_hintPosition(G,e_hint,A){if(!e_hint)throw new Error('001','hint object reference is missing in parameters');if(!A)A=[];var a_={n_elementWidth:G?G.offsetWidth:0,n_elementHeight:G?G.offsetHeight:0,n_elementLeft:G?f_getPosition(G,'Left')+10:n_mouseX,n_elementTop:G?f_getPosition(G,'Top')+10:n_mouseY,n_hintWidth:e_hint.offsetWidth,n_hintHeight:e_hint.offsetHeight,n_hintLeft:0,n_hintTop:0,n_clientWidth:f_clientWidth(),n_clientHeight:f_clientHeight(),n_scrollTop:f_scrollTop(),n_scrollLeft:f_scrollLeft(),s_align:A.align?A.align:'tlbl',n_gap:A.gap==null?5:A.gap,n_margin:A.margin==null?10:A.margin};f_applyAlign(a_);if(a_.n_hintLeft==0)a_.n_hintLeft=-10000;else if(A.smart||A.smart==null)f_checkFit(a_);e_hint.style.left=a_.n_hintLeft+'px';e_hint.style.top=a_.n_hintTop+'px';var I=getElement(e_hint.id+'_if');if(I){I.style.left=a_.n_hintLeft+'px';I.style.top=a_.n_hintTop+'px';I.style.width=a_.n_hintWidth+'px';I.style.height=a_.n_hintHeight+'px';}}function f_checkFit(a_){if(a_.n_spaceT>=0&&a_.n_spaceR>=0&&a_.n_spaceB>=0&&a_.n_spaceL>=0)return;var B=(a_.n_hintTop+a_.n_hintHeight+a_.n_gap<=a_.n_elementTop)||(a_.n_elementTop+a_.n_elementHeight+a_.n_gap<=a_.n_hintTop);if(B){if(a_.n_spaceL<0||(a_.n_spaceL+a_.n_spaceR<0))a_.n_hintLeft=a_.n_scrollLeft+a_.n_margin;else if(a_.n_spaceR<0)a_.n_hintLeft=a_.n_scrollLeft+a_.n_clientWidth-a_.n_margin-a_.n_hintWidth;}var C=(a_.n_hintLeft+a_.n_hintWidth+a_.n_gap<=a_.n_elementLeft)||(a_.n_elementLeft+a_.n_elementWidth+a_.n_gap<=a_.n_hintLeft);if(C){if(a_.n_spaceT<0||(a_.n_spaceT+a_.n_spaceB<0))a_.n_hintTop=a_.n_scrollTop+a_.n_margin;else if(a_.n_spaceB<0)a_.n_hintTop=a_.n_scrollTop+a_.n_clientHeight-a_.n_margin-a_.n_hintHeight;}if(!B&&(a_.n_spaceL<0||a_.n_spaceR<0)){var N=a_.n_spaceL+a_.n_spaceR,n_hintLeft=a_.n_hintLeft,n_hintTop=a_.n_hintTop;a_.s_align=a_.s_align.replace('r','-');a_.s_align=a_.s_align.replace('l','r');a_.s_align=a_.s_align.replace('-','l');f_applyAlign(a_);if(Math.min(a_.n_spaceL,a_.n_spaceR)<N)a_.n_hintLeft=n_hintLeft;a_.n_hintTop=n_hintTop;}if(!C&&(a_.n_spaceT<0||a_.n_spaceB<0)){var N=Math.min(a_.n_spaceT,a_.n_spaceB),n_hintLeft=a_.n_hintLeft,n_hintTop=a_.n_hintTop;a_.s_align=a_.s_align.replace('t','-');a_.s_align=a_.s_align.replace('b','t');a_.s_align=a_.s_align.replace('-','b');f_applyAlign(a_);if(Math.min(a_.n_spaceT,a_.n_spaceB)<N)a_.n_hintTop=n_hintTop;a_.n_hintLeft=n_hintLeft;}}function f_applyAlign(a_){if(!re_align.exec(a_.s_align))throw new Error('001','Invalid format of align parameter: '+a_.s_align);var J=RegExp.$1,n_top=a_.n_elementTop;if(J=='m')n_top+=Math.round(a_.n_elementHeight/2);else if(J=='b')n_top+=a_.n_elementHeight+a_.n_gap;else n_top-=a_.n_gap;J=RegExp.$3;if(J=='m')n_top-=Math.round(a_.n_hintHeight/2);else if(J=='b')n_top-=a_.n_hintHeight;var M=a_.n_elementLeft;J=RegExp.$2;if(J=='c')M+=Math.round(a_.n_elementWidth/2);else if(J=='r')M+=a_.n_elementWidth+a_.n_gap;else M-=a_.n_gap;J=RegExp.$4;if(J=='c')M-=Math.round(a_.n_hintWidth/2);else if(J=='r')M-=a_.n_hintWidth;a_.n_spaceT=n_top-a_.n_scrollTop-a_.n_margin,a_.n_spaceB=a_.n_clientHeight+a_.n_scrollTop-a_.n_margin-n_top-a_.n_hintHeight,a_.n_spaceL=M-a_.n_scrollLeft-a_.n_margin,a_.n_spaceR=a_.n_clientWidth+a_.n_scrollLeft-a_.n_margin-M-a_.n_hintWidth;a_.n_hintLeft=M;a_.n_hintTop=n_top;}function f_onMouseMove(H){if(!H&&window.event)H=window.event;if(!H)return true;n_mouseX=H.pageX?H.pageX:H.clientX+f_scrollLeft();n_mouseY=H.pageY?H.pageY+2:H.clientY+f_scrollTop();return f_onwindowChange();}function f_onwindowChange(){var Q;for(var i=0;i<A_HINTS.length;i++){Q=A_HINTS[i];if(Q.a_cfg.follow&&Q.o_lastHintID)f_hintPosition(Q.a_elements[Q.o_lastHintID],Q.a_hints[Q.o_lastHintID],Q.a_cfg);}return true;}function f_getPosition(F,R){var O=0,n_offset,e_elem=F;while(e_elem){n_offset=e_elem["offset"+R];O+=n_offset;e_elem=e_elem.offsetParent;}if(b_ieMac)O+=parseInt(document.body[R.toLowerCase()+'Margin']);e_elem=F;while(e_elem!=document.body){n_offset=e_elem["scroll"+R];if(n_offset&&e_elem.style.overflow=='scroll')O-=n_offset;e_elem=e_elem.parentNode;}return O;}function f_clientWidth(){if(typeof(window.innerWidth)=='number')return window.innerWidth;if(document.documentElement&&document.documentElement.clientWidth)return document.documentElement.clientWidth;if(document.body&&document.body.clientWidth)return document.body.clientWidth;return null;}function f_clientHeight(){if(typeof(window.innerHeight)=='number')return window.innerHeight;if(document.documentElement&&document.documentElement.clientHeight)return document.documentElement.clientHeight;if(document.body&&document.body.clientHeight)return document.body.clientHeight;return null;}function f_scrollLeft(){if(typeof(window.pageXOffset)=='number')return window.pageXOffset;if(document.body&&document.body.scrollLeft)return document.body.scrollLeft;if(document.documentElement&&document.documentElement.scrollLeft)return document.documentElement.scrollLeft;return 0;}function f_scrollTop(){if(typeof(window.pageYOffset)=='number')return window.pageYOffset;if(document.body&&document.body.scrollTop)return document.body.scrollTop;if(document.documentElement&&document.documentElement.scrollTop)return document.documentElement.scrollTop;return 0;}getElement=document.all?function(S){return document.all[S]}:function(S){return document.getElementById(S)};var A_HINTS=[],n_mouseX=0,n_mouseY=0,s_userAgent=navigator.userAgent.toLowerCase(),re_align=/^([tmb])([lcr])([tmb])([lcr])$/;var b_mac=s_userAgent.indexOf('mac')!=-1,b_ie5=s_userAgent.indexOf('msie 5')!=-1,b_ie6=s_userAgent.indexOf('msie 6')!=-1&&s_userAgent.indexOf('opera')==-1,b_ieMac=b_mac&&b_ie5,b_safari=b_mac&&s_userAgent.indexOf('safari')!=-1,b_opera6=s_userAgent.indexOf('opera 6')!=-1;