(self["webpackChunktutor_pro"]=self["webpackChunktutor_pro"]||[]).push([[856],{7856:function(e){
/*! @license DOMPurify 2.4.5 | (c) Cure53 and other contributors | Released under the Apache license 2.0 and Mozilla Public License 2.0 | github.com/cure53/DOMPurify/blob/2.4.5/LICENSE */
(function(t,r){true?e.exports=r():0})(this,(function(){"use strict";function e(t){"@babel/helpers - typeof";return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}function t(e,r){t=Object.setPrototypeOf||function e(t,r){t.__proto__=r;return t};return t(e,r)}function r(){if(typeof Reflect==="undefined"||!Reflect.construct)return false;if(Reflect.construct.sham)return false;if(typeof Proxy==="function")return true;try{Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){})));return true}catch(e){return false}}function n(e,a,i){if(r()){n=Reflect.construct}else{n=function e(r,n,a){var i=[null];i.push.apply(i,n);var o=Function.bind.apply(r,i);var l=new o;if(a)t(l,a.prototype);return l}}return n.apply(null,arguments)}function a(e){return i(e)||o(e)||l(e)||u()}function i(e){if(Array.isArray(e))return c(e)}function o(e){if(typeof Symbol!=="undefined"&&e[Symbol.iterator]!=null||e["@@iterator"]!=null)return Array.from(e)}function l(e,t){if(!e)return;if(typeof e==="string")return c(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);if(r==="Object"&&e.constructor)r=e.constructor.name;if(r==="Map"||r==="Set")return Array.from(e);if(r==="Arguments"||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return c(e,t)}function c(e,t){if(t==null||t>e.length)t=e.length;for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}function u(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var f=Object.hasOwnProperty,s=Object.setPrototypeOf,m=Object.isFrozen,p=Object.getPrototypeOf,d=Object.getOwnPropertyDescriptor;var v=Object.freeze,h=Object.seal,g=Object.create;var y=typeof Reflect!=="undefined"&&Reflect,b=y.apply,T=y.construct;if(!b){b=function e(t,r,n){return t.apply(r,n)}}if(!v){v=function e(t){return t}}if(!h){h=function e(t){return t}}if(!T){T=function e(t,r){return n(t,a(r))}}var N=D(Array.prototype.forEach);var E=D(Array.prototype.pop);var A=D(Array.prototype.push);var w=D(String.prototype.toLowerCase);var S=D(String.prototype.toString);var _=D(String.prototype.match);var k=D(String.prototype.replace);var x=D(String.prototype.indexOf);var L=D(String.prototype.trim);var O=D(RegExp.prototype.test);var C=R(TypeError);function D(e){return function(t){for(var r=arguments.length,n=new Array(r>1?r-1:0),a=1;a<r;a++){n[a-1]=arguments[a]}return b(e,t,n)}}function R(e){return function(){for(var t=arguments.length,r=new Array(t),n=0;n<t;n++){r[n]=arguments[n]}return T(e,r)}}function M(e,t,r){r=r?r:w;if(s){s(e,null)}var n=t.length;while(n--){var a=t[n];if(typeof a==="string"){var i=r(a);if(i!==a){if(!m(t)){t[n]=i}a=i}}e[a]=true}return e}function I(e){var t=g(null);var r;for(r in e){if(b(f,e,[r])===true){t[r]=e[r]}}return t}function F(e,t){while(e!==null){var r=d(e,t);if(r){if(r.get){return D(r.get)}if(typeof r.value==="function"){return D(r.value)}}e=p(e)}function n(e){console.warn("fallback value for",e);return null}return n}var U=v(["a","abbr","acronym","address","area","article","aside","audio","b","bdi","bdo","big","blink","blockquote","body","br","button","canvas","caption","center","cite","code","col","colgroup","content","data","datalist","dd","decorator","del","details","dfn","dialog","dir","div","dl","dt","element","em","fieldset","figcaption","figure","font","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","img","input","ins","kbd","label","legend","li","main","map","mark","marquee","menu","menuitem","meter","nav","nobr","ol","optgroup","option","output","p","picture","pre","progress","q","rp","rt","ruby","s","samp","section","select","shadow","small","source","spacer","span","strike","strong","style","sub","summary","sup","table","tbody","td","template","textarea","tfoot","th","thead","time","tr","track","tt","u","ul","var","video","wbr"]);var H=v(["svg","a","altglyph","altglyphdef","altglyphitem","animatecolor","animatemotion","animatetransform","circle","clippath","defs","desc","ellipse","filter","font","g","glyph","glyphref","hkern","image","line","lineargradient","marker","mask","metadata","mpath","path","pattern","polygon","polyline","radialgradient","rect","stop","style","switch","symbol","text","textpath","title","tref","tspan","view","vkern"]);var z=v(["feBlend","feColorMatrix","feComponentTransfer","feComposite","feConvolveMatrix","feDiffuseLighting","feDisplacementMap","feDistantLight","feFlood","feFuncA","feFuncB","feFuncG","feFuncR","feGaussianBlur","feImage","feMerge","feMergeNode","feMorphology","feOffset","fePointLight","feSpecularLighting","feSpotLight","feTile","feTurbulence"]);var P=v(["animate","color-profile","cursor","discard","fedropshadow","font-face","font-face-format","font-face-name","font-face-src","font-face-uri","foreignobject","hatch","hatchpath","mesh","meshgradient","meshpatch","meshrow","missing-glyph","script","set","solidcolor","unknown","use"]);var B=v(["math","menclose","merror","mfenced","mfrac","mglyph","mi","mlabeledtr","mmultiscripts","mn","mo","mover","mpadded","mphantom","mroot","mrow","ms","mspace","msqrt","mstyle","msub","msup","msubsup","mtable","mtd","mtext","mtr","munder","munderover"]);var j=v(["maction","maligngroup","malignmark","mlongdiv","mscarries","mscarry","msgroup","mstack","msline","msrow","semantics","annotation","annotation-xml","mprescripts","none"]);var G=v(["#text"]);var W=v(["accept","action","align","alt","autocapitalize","autocomplete","autopictureinpicture","autoplay","background","bgcolor","border","capture","cellpadding","cellspacing","checked","cite","class","clear","color","cols","colspan","controls","controlslist","coords","crossorigin","datetime","decoding","default","dir","disabled","disablepictureinpicture","disableremoteplayback","download","draggable","enctype","enterkeyhint","face","for","headers","height","hidden","high","href","hreflang","id","inputmode","integrity","ismap","kind","label","lang","list","loading","loop","low","max","maxlength","media","method","min","minlength","multiple","muted","name","nonce","noshade","novalidate","nowrap","open","optimum","pattern","placeholder","playsinline","poster","preload","pubdate","radiogroup","readonly","rel","required","rev","reversed","role","rows","rowspan","spellcheck","scope","selected","shape","size","sizes","span","srclang","start","src","srcset","step","style","summary","tabindex","title","translate","type","usemap","valign","value","width","xmlns","slot"]);var q=v(["accent-height","accumulate","additive","alignment-baseline","ascent","attributename","attributetype","azimuth","basefrequency","baseline-shift","begin","bias","by","class","clip","clippathunits","clip-path","clip-rule","color","color-interpolation","color-interpolation-filters","color-profile","color-rendering","cx","cy","d","dx","dy","diffuseconstant","direction","display","divisor","dur","edgemode","elevation","end","fill","fill-opacity","fill-rule","filter","filterunits","flood-color","flood-opacity","font-family","font-size","font-size-adjust","font-stretch","font-style","font-variant","font-weight","fx","fy","g1","g2","glyph-name","glyphref","gradientunits","gradienttransform","height","href","id","image-rendering","in","in2","k","k1","k2","k3","k4","kerning","keypoints","keysplines","keytimes","lang","lengthadjust","letter-spacing","kernelmatrix","kernelunitlength","lighting-color","local","marker-end","marker-mid","marker-start","markerheight","markerunits","markerwidth","maskcontentunits","maskunits","max","mask","media","method","mode","min","name","numoctaves","offset","operator","opacity","order","orient","orientation","origin","overflow","paint-order","path","pathlength","patterncontentunits","patterntransform","patternunits","points","preservealpha","preserveaspectratio","primitiveunits","r","rx","ry","radius","refx","refy","repeatcount","repeatdur","restart","result","rotate","scale","seed","shape-rendering","specularconstant","specularexponent","spreadmethod","startoffset","stddeviation","stitchtiles","stop-color","stop-opacity","stroke-dasharray","stroke-dashoffset","stroke-linecap","stroke-linejoin","stroke-miterlimit","stroke-opacity","stroke","stroke-width","style","surfacescale","systemlanguage","tabindex","targetx","targety","transform","transform-origin","text-anchor","text-decoration","text-rendering","textlength","type","u1","u2","unicode","values","viewbox","visibility","version","vert-adv-y","vert-origin-x","vert-origin-y","width","word-spacing","wrap","writing-mode","xchannelselector","ychannelselector","x","x1","x2","xmlns","y","y1","y2","z","zoomandpan"]);var Y=v(["accent","accentunder","align","bevelled","close","columnsalign","columnlines","columnspan","denomalign","depth","dir","display","displaystyle","encoding","fence","frame","height","href","id","largeop","length","linethickness","lspace","lquote","mathbackground","mathcolor","mathsize","mathvariant","maxsize","minsize","movablelimits","notation","numalign","open","rowalign","rowlines","rowspacing","rowspan","rspace","rquote","scriptlevel","scriptminsize","scriptsizemultiplier","selection","separator","separators","stretchy","subscriptshift","supscriptshift","symmetric","voffset","width","xmlns"]);var $=v(["xlink:href","xml:id","xlink:title","xml:space","xmlns:xlink"]);var K=h(/\{\{[\w\W]*|[\w\W]*\}\}/gm);var V=h(/<%[\w\W]*|[\w\W]*%>/gm);var X=h(/\${[\w\W]*}/gm);var Z=h(/^data-[\-\w.\u00B7-\uFFFF]/);var J=h(/^aria-[\-\w]+$/);var Q=h(/^(?:(?:(?:f|ht)tps?|mailto|tel|callto|cid|xmpp):|[^a-z]|[a-z+.\-]+(?:[^a-z+.\-:]|$))/i);var ee=h(/^(?:\w+script|data):/i);var te=h(/[\u0000-\u0020\u00A0\u1680\u180E\u2000-\u2029\u205F\u3000]/g);var re=h(/^html$/i);var ne=function e(){return typeof window==="undefined"?null:window};var ae=function t(r,n){if(e(r)!=="object"||typeof r.createPolicy!=="function"){return null}var a=null;var i="data-tt-policy-suffix";if(n.currentScript&&n.currentScript.hasAttribute(i)){a=n.currentScript.getAttribute(i)}var o="dompurify"+(a?"#"+a:"");try{return r.createPolicy(o,{createHTML:function e(t){return t},createScriptURL:function e(t){return t}})}catch(e){console.warn("TrustedTypes policy "+o+" could not be created.");return null}};function ie(){var t=arguments.length>0&&arguments[0]!==undefined?arguments[0]:ne();var r=function e(t){return ie(t)};r.version="2.4.5";r.removed=[];if(!t||!t.document||t.document.nodeType!==9){r.isSupported=false;return r}var n=t.document;var i=t.document;var o=t.DocumentFragment,l=t.HTMLTemplateElement,c=t.Node,u=t.Element,f=t.NodeFilter,s=t.NamedNodeMap,m=s===void 0?t.NamedNodeMap||t.MozNamedAttrMap:s,p=t.HTMLFormElement,d=t.DOMParser,h=t.trustedTypes;var g=u.prototype;var y=F(g,"cloneNode");var b=F(g,"nextSibling");var T=F(g,"childNodes");var D=F(g,"parentNode");if(typeof l==="function"){var R=i.createElement("template");if(R.content&&R.content.ownerDocument){i=R.content.ownerDocument}}var oe=ae(h,n);var le=oe?oe.createHTML(""):"";var ce=i,ue=ce.implementation,fe=ce.createNodeIterator,se=ce.createDocumentFragment,me=ce.getElementsByTagName;var pe=n.importNode;var de={};try{de=I(i).documentMode?i.documentMode:{}}catch(e){}var ve={};r.isSupported=typeof D==="function"&&ue&&typeof ue.createHTMLDocument!=="undefined"&&de!==9;var he=K,ge=V,ye=X,be=Z,Te=J,Ne=ee,Ee=te;var Ae=Q;var we=null;var Se=M({},[].concat(a(U),a(H),a(z),a(B),a(G)));var _e=null;var ke=M({},[].concat(a(W),a(q),a(Y),a($)));var xe=Object.seal(Object.create(null,{tagNameCheck:{writable:true,configurable:false,enumerable:true,value:null},attributeNameCheck:{writable:true,configurable:false,enumerable:true,value:null},allowCustomizedBuiltInElements:{writable:true,configurable:false,enumerable:true,value:false}}));var Le=null;var Oe=null;var Ce=true;var De=true;var Re=false;var Me=true;var Ie=false;var Fe=false;var Ue=false;var He=false;var ze=false;var Pe=false;var Be=false;var je=true;var Ge=false;var We="user-content-";var qe=true;var Ye=false;var $e={};var Ke=null;var Ve=M({},["annotation-xml","audio","colgroup","desc","foreignobject","head","iframe","math","mi","mn","mo","ms","mtext","noembed","noframes","noscript","plaintext","script","style","svg","template","thead","title","video","xmp"]);var Xe=null;var Ze=M({},["audio","video","img","source","image","track"]);var Je=null;var Qe=M({},["alt","class","for","id","label","name","pattern","placeholder","role","summary","title","value","style","xmlns"]);var et="http://www.w3.org/1998/Math/MathML";var tt="http://www.w3.org/2000/svg";var rt="http://www.w3.org/1999/xhtml";var nt=rt;var at=false;var it=null;var ot=M({},[et,tt,rt],S);var lt;var ct=["application/xhtml+xml","text/html"];var ut="text/html";var ft;var st=null;var mt=i.createElement("form");var pt=function e(t){return t instanceof RegExp||t instanceof Function};var dt=function t(r){if(st&&st===r){return}if(!r||e(r)!=="object"){r={}}r=I(r);lt=ct.indexOf(r.PARSER_MEDIA_TYPE)===-1?lt=ut:lt=r.PARSER_MEDIA_TYPE;ft=lt==="application/xhtml+xml"?S:w;we="ALLOWED_TAGS"in r?M({},r.ALLOWED_TAGS,ft):Se;_e="ALLOWED_ATTR"in r?M({},r.ALLOWED_ATTR,ft):ke;it="ALLOWED_NAMESPACES"in r?M({},r.ALLOWED_NAMESPACES,S):ot;Je="ADD_URI_SAFE_ATTR"in r?M(I(Qe),r.ADD_URI_SAFE_ATTR,ft):Qe;Xe="ADD_DATA_URI_TAGS"in r?M(I(Ze),r.ADD_DATA_URI_TAGS,ft):Ze;Ke="FORBID_CONTENTS"in r?M({},r.FORBID_CONTENTS,ft):Ve;Le="FORBID_TAGS"in r?M({},r.FORBID_TAGS,ft):{};Oe="FORBID_ATTR"in r?M({},r.FORBID_ATTR,ft):{};$e="USE_PROFILES"in r?r.USE_PROFILES:false;Ce=r.ALLOW_ARIA_ATTR!==false;De=r.ALLOW_DATA_ATTR!==false;Re=r.ALLOW_UNKNOWN_PROTOCOLS||false;Me=r.ALLOW_SELF_CLOSE_IN_ATTR!==false;Ie=r.SAFE_FOR_TEMPLATES||false;Fe=r.WHOLE_DOCUMENT||false;ze=r.RETURN_DOM||false;Pe=r.RETURN_DOM_FRAGMENT||false;Be=r.RETURN_TRUSTED_TYPE||false;He=r.FORCE_BODY||false;je=r.SANITIZE_DOM!==false;Ge=r.SANITIZE_NAMED_PROPS||false;qe=r.KEEP_CONTENT!==false;Ye=r.IN_PLACE||false;Ae=r.ALLOWED_URI_REGEXP||Ae;nt=r.NAMESPACE||rt;xe=r.CUSTOM_ELEMENT_HANDLING||{};if(r.CUSTOM_ELEMENT_HANDLING&&pt(r.CUSTOM_ELEMENT_HANDLING.tagNameCheck)){xe.tagNameCheck=r.CUSTOM_ELEMENT_HANDLING.tagNameCheck}if(r.CUSTOM_ELEMENT_HANDLING&&pt(r.CUSTOM_ELEMENT_HANDLING.attributeNameCheck)){xe.attributeNameCheck=r.CUSTOM_ELEMENT_HANDLING.attributeNameCheck}if(r.CUSTOM_ELEMENT_HANDLING&&typeof r.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements==="boolean"){xe.allowCustomizedBuiltInElements=r.CUSTOM_ELEMENT_HANDLING.allowCustomizedBuiltInElements}if(Ie){De=false}if(Pe){ze=true}if($e){we=M({},a(G));_e=[];if($e.html===true){M(we,U);M(_e,W)}if($e.svg===true){M(we,H);M(_e,q);M(_e,$)}if($e.svgFilters===true){M(we,z);M(_e,q);M(_e,$)}if($e.mathMl===true){M(we,B);M(_e,Y);M(_e,$)}}if(r.ADD_TAGS){if(we===Se){we=I(we)}M(we,r.ADD_TAGS,ft)}if(r.ADD_ATTR){if(_e===ke){_e=I(_e)}M(_e,r.ADD_ATTR,ft)}if(r.ADD_URI_SAFE_ATTR){M(Je,r.ADD_URI_SAFE_ATTR,ft)}if(r.FORBID_CONTENTS){if(Ke===Ve){Ke=I(Ke)}M(Ke,r.FORBID_CONTENTS,ft)}if(qe){we["#text"]=true}if(Fe){M(we,["html","head","body"])}if(we.table){M(we,["tbody"]);delete Le.tbody}if(v){v(r)}st=r};var vt=M({},["mi","mo","mn","ms","mtext"]);var ht=M({},["foreignobject","desc","title","annotation-xml"]);var gt=M({},["title","style","font","a","script"]);var yt=M({},H);M(yt,z);M(yt,P);var bt=M({},B);M(bt,j);var Tt=function e(t){var r=D(t);if(!r||!r.tagName){r={namespaceURI:nt,tagName:"template"}}var n=w(t.tagName);var a=w(r.tagName);if(!it[t.namespaceURI]){return false}if(t.namespaceURI===tt){if(r.namespaceURI===rt){return n==="svg"}if(r.namespaceURI===et){return n==="svg"&&(a==="annotation-xml"||vt[a])}return Boolean(yt[n])}if(t.namespaceURI===et){if(r.namespaceURI===rt){return n==="math"}if(r.namespaceURI===tt){return n==="math"&&ht[a]}return Boolean(bt[n])}if(t.namespaceURI===rt){if(r.namespaceURI===tt&&!ht[a]){return false}if(r.namespaceURI===et&&!vt[a]){return false}return!bt[n]&&(gt[n]||!yt[n])}if(lt==="application/xhtml+xml"&&it[t.namespaceURI]){return true}return false};var Nt=function e(t){A(r.removed,{element:t});try{t.parentNode.removeChild(t)}catch(e){try{t.outerHTML=le}catch(e){t.remove()}}};var Et=function e(t,n){try{A(r.removed,{attribute:n.getAttributeNode(t),from:n})}catch(e){A(r.removed,{attribute:null,from:n})}n.removeAttribute(t);if(t==="is"&&!_e[t]){if(ze||Pe){try{Nt(n)}catch(e){}}else{try{n.setAttribute(t,"")}catch(e){}}}};var At=function e(t){var r;var n;if(He){t="<remove></remove>"+t}else{var a=_(t,/^[\r\n\t ]+/);n=a&&a[0]}if(lt==="application/xhtml+xml"&&nt===rt){t='<html xmlns="http://www.w3.org/1999/xhtml"><head></head><body>'+t+"</body></html>"}var o=oe?oe.createHTML(t):t;if(nt===rt){try{r=(new d).parseFromString(o,lt)}catch(e){}}if(!r||!r.documentElement){r=ue.createDocument(nt,"template",null);try{r.documentElement.innerHTML=at?le:o}catch(e){}}var l=r.body||r.documentElement;if(t&&n){l.insertBefore(i.createTextNode(n),l.childNodes[0]||null)}if(nt===rt){return me.call(r,Fe?"html":"body")[0]}return Fe?r.documentElement:l};var wt=function e(t){return fe.call(t.ownerDocument||t,t,f.SHOW_ELEMENT|f.SHOW_COMMENT|f.SHOW_TEXT,null,false)};var St=function e(t){return t instanceof p&&(typeof t.nodeName!=="string"||typeof t.textContent!=="string"||typeof t.removeChild!=="function"||!(t.attributes instanceof m)||typeof t.removeAttribute!=="function"||typeof t.setAttribute!=="function"||typeof t.namespaceURI!=="string"||typeof t.insertBefore!=="function"||typeof t.hasChildNodes!=="function")};var _t=function t(r){return e(c)==="object"?r instanceof c:r&&e(r)==="object"&&typeof r.nodeType==="number"&&typeof r.nodeName==="string"};var kt=function e(t,n,a){if(!ve[t]){return}N(ve[t],(function(e){e.call(r,n,a,st)}))};var xt=function e(t){var n;kt("beforeSanitizeElements",t,null);if(St(t)){Nt(t);return true}if(O(/[\u0080-\uFFFF]/,t.nodeName)){Nt(t);return true}var a=ft(t.nodeName);kt("uponSanitizeElement",t,{tagName:a,allowedTags:we});if(t.hasChildNodes()&&!_t(t.firstElementChild)&&(!_t(t.content)||!_t(t.content.firstElementChild))&&O(/<[/\w]/g,t.innerHTML)&&O(/<[/\w]/g,t.textContent)){Nt(t);return true}if(a==="select"&&O(/<template/i,t.innerHTML)){Nt(t);return true}if(!we[a]||Le[a]){if(!Le[a]&&Ot(a)){if(xe.tagNameCheck instanceof RegExp&&O(xe.tagNameCheck,a))return false;if(xe.tagNameCheck instanceof Function&&xe.tagNameCheck(a))return false}if(qe&&!Ke[a]){var i=D(t)||t.parentNode;var o=T(t)||t.childNodes;if(o&&i){var l=o.length;for(var c=l-1;c>=0;--c){i.insertBefore(y(o[c],true),b(t))}}}Nt(t);return true}if(t instanceof u&&!Tt(t)){Nt(t);return true}if((a==="noscript"||a==="noembed")&&O(/<\/no(script|embed)/i,t.innerHTML)){Nt(t);return true}if(Ie&&t.nodeType===3){n=t.textContent;n=k(n,he," ");n=k(n,ge," ");n=k(n,ye," ");if(t.textContent!==n){A(r.removed,{element:t.cloneNode()});t.textContent=n}}kt("afterSanitizeElements",t,null);return false};var Lt=function e(t,r,n){if(je&&(r==="id"||r==="name")&&(n in i||n in mt)){return false}if(De&&!Oe[r]&&O(be,r));else if(Ce&&O(Te,r));else if(!_e[r]||Oe[r]){if(Ot(t)&&(xe.tagNameCheck instanceof RegExp&&O(xe.tagNameCheck,t)||xe.tagNameCheck instanceof Function&&xe.tagNameCheck(t))&&(xe.attributeNameCheck instanceof RegExp&&O(xe.attributeNameCheck,r)||xe.attributeNameCheck instanceof Function&&xe.attributeNameCheck(r))||r==="is"&&xe.allowCustomizedBuiltInElements&&(xe.tagNameCheck instanceof RegExp&&O(xe.tagNameCheck,n)||xe.tagNameCheck instanceof Function&&xe.tagNameCheck(n)));else{return false}}else if(Je[r]);else if(O(Ae,k(n,Ee,"")));else if((r==="src"||r==="xlink:href"||r==="href")&&t!=="script"&&x(n,"data:")===0&&Xe[t]);else if(Re&&!O(Ne,k(n,Ee,"")));else if(!n);else{return false}return true};var Ot=function e(t){return t.indexOf("-")>0};var Ct=function t(n){var a;var i;var o;var l;kt("beforeSanitizeAttributes",n,null);var c=n.attributes;if(!c){return}var u={attrName:"",attrValue:"",keepAttr:true,allowedAttributes:_e};l=c.length;while(l--){a=c[l];var f=a,s=f.name,m=f.namespaceURI;i=s==="value"?a.value:L(a.value);o=ft(s);u.attrName=o;u.attrValue=i;u.keepAttr=true;u.forceKeepAttr=undefined;kt("uponSanitizeAttribute",n,u);i=u.attrValue;if(u.forceKeepAttr){continue}Et(s,n);if(!u.keepAttr){continue}if(!Me&&O(/\/>/i,i)){Et(s,n);continue}if(Ie){i=k(i,he," ");i=k(i,ge," ");i=k(i,ye," ")}var p=ft(n.nodeName);if(!Lt(p,o,i)){continue}if(Ge&&(o==="id"||o==="name")){Et(s,n);i=We+i}if(oe&&e(h)==="object"&&typeof h.getAttributeType==="function"){if(m);else{switch(h.getAttributeType(p,o)){case"TrustedHTML":i=oe.createHTML(i);break;case"TrustedScriptURL":i=oe.createScriptURL(i);break}}}try{if(m){n.setAttributeNS(m,s,i)}else{n.setAttribute(s,i)}E(r.removed)}catch(e){}}kt("afterSanitizeAttributes",n,null)};var Dt=function e(t){var r;var n=wt(t);kt("beforeSanitizeShadowDOM",t,null);while(r=n.nextNode()){kt("uponSanitizeShadowNode",r,null);if(xt(r)){continue}if(r.content instanceof o){e(r.content)}Ct(r)}kt("afterSanitizeShadowDOM",t,null)};r.sanitize=function(a){var i=arguments.length>1&&arguments[1]!==undefined?arguments[1]:{};var l;var u;var f;var s;var m;at=!a;if(at){a="\x3c!--\x3e"}if(typeof a!=="string"&&!_t(a)){if(typeof a.toString!=="function"){throw C("toString is not a function")}else{a=a.toString();if(typeof a!=="string"){throw C("dirty is not a string, aborting")}}}if(!r.isSupported){if(e(t.toStaticHTML)==="object"||typeof t.toStaticHTML==="function"){if(typeof a==="string"){return t.toStaticHTML(a)}if(_t(a)){return t.toStaticHTML(a.outerHTML)}}return a}if(!Ue){dt(i)}r.removed=[];if(typeof a==="string"){Ye=false}if(Ye){if(a.nodeName){var p=ft(a.nodeName);if(!we[p]||Le[p]){throw C("root node is forbidden and cannot be sanitized in-place")}}}else if(a instanceof c){l=At("\x3c!----\x3e");u=l.ownerDocument.importNode(a,true);if(u.nodeType===1&&u.nodeName==="BODY"){l=u}else if(u.nodeName==="HTML"){l=u}else{l.appendChild(u)}}else{if(!ze&&!Ie&&!Fe&&a.indexOf("<")===-1){return oe&&Be?oe.createHTML(a):a}l=At(a);if(!l){return ze?null:Be?le:""}}if(l&&He){Nt(l.firstChild)}var d=wt(Ye?a:l);while(f=d.nextNode()){if(f.nodeType===3&&f===s){continue}if(xt(f)){continue}if(f.content instanceof o){Dt(f.content)}Ct(f);s=f}s=null;if(Ye){return a}if(ze){if(Pe){m=se.call(l.ownerDocument);while(l.firstChild){m.appendChild(l.firstChild)}}else{m=l}if(_e.shadowroot||_e.shadowrootmod){m=pe.call(n,m,true)}return m}var v=Fe?l.outerHTML:l.innerHTML;if(Fe&&we["!doctype"]&&l.ownerDocument&&l.ownerDocument.doctype&&l.ownerDocument.doctype.name&&O(re,l.ownerDocument.doctype.name)){v="<!DOCTYPE "+l.ownerDocument.doctype.name+">\n"+v}if(Ie){v=k(v,he," ");v=k(v,ge," ");v=k(v,ye," ")}return oe&&Be?oe.createHTML(v):v};r.setConfig=function(e){dt(e);Ue=true};r.clearConfig=function(){st=null;Ue=false};r.isValidAttribute=function(e,t,r){if(!st){dt({})}var n=ft(e);var a=ft(t);return Lt(n,a,r)};r.addHook=function(e,t){if(typeof t!=="function"){return}ve[e]=ve[e]||[];A(ve[e],t)};r.removeHook=function(e){if(ve[e]){return E(ve[e])}};r.removeHooks=function(e){if(ve[e]){ve[e]=[]}};r.removeAllHooks=function(){ve={}};return r}var oe=ie();return oe}))}}]);