(function(){'use strict';
function qsa(s,c){return Array.prototype.slice.call((c||document).querySelectorAll(s));}
function fa(n,d){return Math.floor(n/d);}
function g2j(gy,gm,gd){var m=[0,31,59,90,120,151,181,212,243,273,304,334],y=gm>2?gy+1:gy,days=355666+365*gy+fa(y+3,4)-fa(y+99,100)+fa(y+399,400)+gd+m[gm-1],jy=-1595+33*fa(days,12053);days%=12053;jy+=4*fa(days,1461);days%=1461;if(days>365){jy+=fa(days-1,365);days=(days-1)%365;}var jm,jd;if(days<186){jm=1+fa(days,31);jd=1+days%31;}else{jm=7+fa(days-186,30);jd=1+(days-186)%30;}return[jy,jm,jd];}
function j2g(jy,jm,jd){jy+=1595;var days=-355668+365*jy+fa(jy,33)*8+fa((jy%33)+3,4)+jd;if(jm<7)days+=(jm-1)*31;else days+=(jm-7)*30+186;var gy=400*fa(days,146097);days%=146097;if(days>36524){gy+=100*fa(--days,36524);days%=36524;if(days>=365)days++;}gy+=4*fa(days,1461);days%=1461;if(days>365){gy+=fa(days-1,365);days=(days-1)%365;}var gd=days+1,leap=(gy%4===0&&gy%100!==0)||gy%400===0,g=[0,31,leap?29:28,31,30,31,30,31,31,30,31,30,31],gm=1;while(gm<=12&&gd>g[gm]){gd-=g[gm];gm++;}return[gy,gm,gd];}
function faDigits(v){return String(v).replace(/[۰-۹]/g,function(x){return'۰۱۲۳۴۵۶۷۸۹'.indexOf(x);});}
function pad(n){return String(n).padStart(2,'0');}
function jalaliToIso(v){v=faDigits(v).replace(/-/g,'/');var m=v.match(/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/);if(!m)return'';var g=j2g(+m[1],+m[2],+m[3]);return g[0]+'-'+pad(g[1])+'-'+pad(g[2]);}
qsa('[data-jalali-date]').forEach(function(i){i.type='text';i.inputMode='numeric';i.addEventListener('blur',function(){var v=faDigits(i.value).replace(/\D/g,'');if(v.length===8)i.value=v.slice(0,4)+'/'+v.slice(4,6)+'/'+v.slice(6);});});
qsa('[data-av-home-search]').forEach(function(form){form.addEventListener('submit',function(){qsa('[data-jalali-date]',form).forEach(function(i){var iso=jalaliToIso(i.value),hidden=form.querySelector('input[name="'+i.name.replace('_jalali','')+'"]');if(hidden&&iso)hidden.value=iso;});},true);});
var slider=document.querySelector('[data-av-hero-slider]');if(slider){var dots=qsa('.av-hero__dots i',slider),slides=qsa('.av-hero__slide',slider);function sync(){dots.forEach(function(d,i){d.classList.toggle('is-active',!!slides[i]&&slides[i].classList.contains('is-active'));});}if(slider.addEventListener){var observer=new MutationObserver(sync);slides.forEach(function(s){observer.observe(s,{attributes:true,attributeFilter:['class']});});sync();}}
})();
