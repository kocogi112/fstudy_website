(()=>{var t={};function e(t,e){return r(t)||n(t,e)||i(t,e)||a()}function a(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function n(t,e){var a=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=a){var n,r,o,i,l=[],s=!0,c=!1;try{if(o=(a=a.call(t)).next,0===e){if(Object(a)!==a)return;s=!1}else for(;!(s=(n=o.call(a)).done)&&(l.push(n.value),l.length!==e);s=!0);}catch(t){c=!0,r=t}finally{try{if(!s&&null!=a["return"]&&(i=a["return"](),Object(i)!==i))return}finally{if(c)throw r}}return l}}function r(t){if(Array.isArray(t))return t}function o(t,e){var a=typeof Symbol!=="undefined"&&t[Symbol.iterator]||t["@@iterator"];if(!a){if(Array.isArray(t)||(a=i(t))||e&&t&&typeof t.length==="number"){if(a)t=a;var n=0;var r=function t(){};return{s:r,n:function e(){if(n>=t.length)return{done:true};return{done:false,value:t[n++]}},e:function t(e){throw e},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var o=true,l=false,s;return{s:function e(){a=a.call(t)},n:function t(){var e=a.next();o=e.done;return e},e:function t(e){l=true;s=e},f:function t(){try{if(!o&&a["return"]!=null)a["return"]()}finally{if(l)throw s}}}}function i(t,e){if(!t)return;if(typeof t==="string")return l(t,e);var a=Object.prototype.toString.call(t).slice(8,-1);if(a==="Object"&&t.constructor)a=t.constructor.name;if(a==="Map"||a==="Set")return Array.from(t);if(a==="Arguments"||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a))return l(t,e)}function l(t,e){if(e==null||e>t.length)e=t.length;for(var a=0,n=new Array(e);a<e;a++)n[a]=t[a];return n}window.onload=function(){var t=document.getElementById("tutor_analytics_search_icon");if(t){t.onclick=function(){var t=document.getElementById("tutor_analytics_search_form");t.submit()}}var a=document.querySelectorAll(".tutor-admin-report-frequency");var n=o(a),r;try{for(n.s();!(r=n.n()).done;){var i=r.value;i.onclick=function(t){var e=t.target.dataset.key;if(e==="custom"){return}var a=new URL(window.location.href);var n=a.searchParams;if(n.has("start_date")){n["delete"]("start_date")}if(n.has("end_date")){n["delete"]("end_date")}n.set("period",e);window.location=a}}}catch(t){n.e(t)}finally{n.f()}var l=o(_tutor_h5p_analytics),s;try{for(l.s();!(s=l.n()).done;){var c=s.value;var d=document.getElementById("".concat(c.id,"_canvas"))?document.getElementById("".concat(c.id,"_canvas")).getContext("2d"):null;var u=[];var f=[];var v=[];for(var m=0,b=Object.entries(c.data);m<b.length;m++){var h=e(b[m],2),y=h[0],p=h[1];var _={month:"short",day:"numeric"};var g=new Date(p.saved_date);var w=g.toLocaleDateString("en-US",_);u.push(w);f.push(p.total);if(p.fees){v.push(p.fees)}}var j=[];j.push({label:c.label,backgroundColor:"#3057D5",borderColor:"#3057D5",data:f,borderWidth:2,fill:false,lineTension:0});if(v.length){j.push({label:c.label2,backgroundColor:"rgba(200, 0, 0, 1)",borderColor:"rgba(200, 0, 0, 1)",data:v,borderWidth:2,fill:false,lineTension:0})}if(d){new Chart(d,{type:"line",data:{labels:u,datasets:j},options:{scales:{yAxes:[{ticks:{min:0,beginAtZero:true,callback:function t(e,a,n){if(Math.floor(e)===e){return e}}}}]},legend:{display:false}}})}}}catch(t){l.e(t)}finally{l.f()}(function t(){document.addEventListener("click",(function(t){var e="data-tutor-modal-target";var a="data-tutor-modal-close";var n="tutor-modal-overlay";if(t.target.hasAttribute(e)||t.target.closest("[".concat(e,"]"))){t.preventDefault();var r=t.target.hasAttribute(e)?t.target.getAttribute(e):t.target.closest("[".concat(e,"]")).getAttribute(e);var o=document.getElementById(r);if(o){}}if(t.target.hasAttribute(a)||t.target.classList.contains(n)||t.target.closest("[".concat(a,"]"))){t.preventDefault();var i=document.querySelectorAll(".tutor-modal.tutor-is-active");i.forEach((function(t){t.classList.remove("tutor-is-active")}))}}))})()};window.jQuery(document).ready((function(t){t(document).on("click",".open-activities-modal",(function(e){e.preventDefault();var a=t(this);var n=a.data("verb");var r=t(".h5p-activities-statements-modal");var o=parseInt(a.data("user-id"),10);t.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:{action:"view_activities_statements_modal",verb:n,user_id:o},beforeSend:function t(){a.addClass("is-loading").attr("disabled",true)},success:function t(e){r.find(".tutor-modal-container").html(e.data.output);r.addClass("tutor-is-active");window.dispatchEvent(new Event(_tutorobject.content_change_event))},complete:function t(){a.removeClass("is-loading").attr("disabled",false)}})}));t(document).on("click",".open-verbs-modal",(function(e){e.preventDefault();var a=t(this);var n=a.data("activity-name");var r=t(".h5p-verbs-statements-modal");var o=parseInt(a.data("user-id"),10);t.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:{action:"view_verb_statements_modal",activity_name:n,user_id:o},beforeSend:function t(){a.addClass("is-loading").attr("disabled",true)},success:function t(e){r.find(".tutor-modal-container").html(e.data.output);r.addClass("tutor-is-active");window.dispatchEvent(new Event(_tutorobject.content_change_event))},complete:function t(){a.removeClass("is-loading").attr("disabled",false)}})}));t(document).on("click",".open-learners-modal",(function(e){e.preventDefault();var a=t(this);var n=a.data("activity-name");var r=a.data("verb");var o=t(".h5p-learners-statements-modal");t.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:{action:"view_learners_statements_modal",activity_name:n,verb:r},beforeSend:function t(){a.addClass("is-loading").attr("disabled",true)},success:function t(e){o.find(".tutor-modal-container").html(e.data.output);o.addClass("tutor-is-active");window.dispatchEvent(new Event(_tutorobject.content_change_event))},complete:function t(){a.removeClass("is-loading").attr("disabled",false)}})}));t(document).on("click",".open-last-ten-statements-modal",(function(e){e.preventDefault();var a=t(this);var n=a.data("activity-name");var r=a.data("verb");var o=t(".h5p-last-ten-statements-modal");var i=parseInt(a.data("user-id"),10);t.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:{action:"view_last_ten_statements_modal",activity_name:n,verb:r,user_id:i},beforeSend:function t(){a.addClass("is-loading").attr("disabled",true)},success:function t(e){o.find(".tutor-modal-container").html(e.data.output);o.addClass("tutor-is-active");window.dispatchEvent(new Event(_tutorobject.content_change_event))},complete:function t(){a.removeClass("is-loading").attr("disabled",false)}})}))}))})();