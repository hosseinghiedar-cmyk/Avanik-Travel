(function(){'use strict';
var d=document;
function q(s,c){return(c||d).querySelector(s)}
function qa(s,c){return Array.prototype.slice.call((c||d).querySelectorAll(s))}
function ready(fn){d.readyState==='loading'?d.addEventListener('DOMContentLoaded',fn):fn()}
ready(function(){
  var body=d.body;
  var scrollTick=false;
  function onScroll(){if(scrollTick)return;scrollTick=true;requestAnimationFrame(function(){body.classList.toggle('av-scrolled',window.scrollY>12);scrollTick=false})}
  window.addEventListener('scroll',onScroll,{passive:true});onScroll();

  qa('.av-reveal').forEach(function(el,i){el.style.transitionDelay=Math.min((i%8)*55,330)+'ms'});
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){e.target.classList.add('is-visible');io.unobserve(e.target)}})},{rootMargin:'0px 0px -8% 0px',threshold:.08});
    qa('.av-reveal').forEach(function(el){io.observe(el)});
  }else qa('.av-reveal').forEach(function(el){el.classList.add('is-visible')});

  qa('[data-av-scroll]').forEach(function(btn){btn.addEventListener('click',function(){var target=q(btn.getAttribute('data-av-scroll'));if(target)target.scrollIntoView({behavior:window.matchMedia('(prefers-reduced-motion: reduce)').matches?'auto':'smooth',block:'start'})})});

  var cards=qa('.av-service-card--link');cards.forEach(function(card){card.addEventListener('mouseenter',function(){card.classList.add('av-hover')});card.addEventListener('mouseleave',function(){card.classList.remove('av-hover')})});

  var forms=qa('[data-av-home-search]');forms.forEach(function(form){form.addEventListener('invalid',function(e){e.target.closest('.av-field')?.classList.add('av-invalid')},true);form.addEventListener('input',function(e){var field=e.target.closest('.av-field');if(field)field.classList.remove('av-invalid')});form.addEventListener('submit',function(){var btn=q('[data-av-search-submit]',form);if(btn){btn.classList.add('is-loading');btn.setAttribute('aria-busy','true')}})});

  qa('.av-tour-card,.av-destination-card').forEach(function(card){card.addEventListener('pointermove',function(e){if(window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;var r=card.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;card.style.setProperty('--mx',(x*2).toFixed(3));card.style.setProperty('--my',(y*2).toFixed(3))});card.addEventListener('pointerleave',function(){card.style.removeProperty('--mx');card.style.removeProperty('--my')})});
});
})();
