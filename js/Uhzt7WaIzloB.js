!function(e,t){"use strict";var r=!1,a=!1;function s(){if(!a){a=!0;for(var e,r,s=-1!==navigator.appVersion.indexOf("MSIE 10"),n=!!navigator.userAgent.match(/Trident.*rv:11\./),o=t.querySelectorAll("iframe.wp-embedded-content"),i=0;i<o.length;i++)(e=o[i]).getAttribute("data-secret")||(r=Math.random().toString(36).substr(2,10),e.src+="#?secret="+r,e.setAttribute("data-secret",r)),(s||n)&&((r=e.cloneNode(!0)).removeAttribute("security"),e.parentNode.replaceChild(r,e))}}t.querySelector&&e.addEventListener&&(r=!0),e.wp=e.wp||{},e.wp.receiveEmbedMessage||(e.wp.receiveEmbedMessage=function(r){var a=r.data;if(a&&(a.secret||a.message||a.value)&&!/[^a-zA-Z0-9]/.test(a.secret)){for(var s,n,o,i=t.querySelectorAll('iframe[data-secret="'+a.secret+'"]'),c=t.querySelectorAll('blockquote[data-secret="'+a.secret+'"]'),d=0;d<c.length;d++)c[d].style.display="none";for(d=0;d<i.length;d++)s=i[d],r.source===s.contentWindow&&(s.removeAttribute("style"),"height"===a.message&&(1e3<(o=parseInt(a.value,10))?o=1e3:~~o<200&&(o=200),s.height=o),"link"===a.message&&(n=t.createElement("a"),o=t.createElement("a"),n.href=s.getAttribute("src"),o.href=a.value,o.host===n.host&&t.activeElement===s&&(e.top.location.href=a.value)))}},r&&(e.addEventListener("message",e.wp.receiveEmbedMessage,!1),t.addEventListener("DOMContentLoaded",s,!1),e.addEventListener("load",s,!1)))}(window,document);