!function(){"use strict";var e,r={104:function(e,r,t){var n=window.wp.element,o=window.wp.i18n,l=window.wp.components,a=window.wp.blockEditor,c=window.wp.blocks,i=window.wp.data,s=window.wp.serverSideRender,u=t.n(s),p=JSON.parse('{"name":"occ/rather-simple-carousel"}');const{name:f}=p,d={icon:{src:(0,n.createElement)(l.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,n.createElement)(l.Path,{fill:"none",d:"M0 0h24v24H0V0z"}),(0,n.createElement)(l.G,null,(0,n.createElement)(l.Path,{d:"M20 4v12H8V4h12m0-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 9.67l1.69 2.26 2.48-3.1L19 15H9zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"}))),foreground:"#ff8a00"},edit:e=>{const{attributes:r,className:t}=e,c=(0,i.useSelect)((e=>e("core").getEntityRecords("postType","carousel",{per_page:-1,orderby:"title",order:"asc",_fields:"id,title"})),[]);if(!c)return(0,o.__)("Loading...","rather-simple-carousel");if(0===c.length)return(0,o.__)("No carousels found","rather-simple-carousel");var s=[];s.push({label:(0,o.__)("Select a carousel...","rather-simple-carousel"),value:""});for(var p=0;p<c.length;p++)s.push({label:c[p].title.raw,value:c[p].id});return(0,n.createElement)(n.Fragment,null,(0,n.createElement)(a.InspectorControls,null,(0,n.createElement)(l.PanelBody,{title:(0,o.__)("Settings","rather-simple-carousel")},(0,n.createElement)(l.SelectControl,{label:(0,o.__)("Select a carousel:","rather-simple-carousel"),value:r.id,options:s,onChange:r=>{e.setAttributes({id:Number(r)})}}))),(0,n.createElement)(u(),{block:"occ/rather-simple-carousel",attributes:r,className:t}))},save:()=>null};(0,c.registerBlockType)(f,d)}},t={};function n(e){var o=t[e];if(void 0!==o)return o.exports;var l=t[e]={exports:{}};return r[e](l,l.exports,n),l.exports}n.m=r,e=[],n.O=function(r,t,o,l){if(!t){var a=1/0;for(u=0;u<e.length;u++){t=e[u][0],o=e[u][1],l=e[u][2];for(var c=!0,i=0;i<t.length;i++)(!1&l||a>=l)&&Object.keys(n.O).every((function(e){return n.O[e](t[i])}))?t.splice(i--,1):(c=!1,l<a&&(a=l));if(c){e.splice(u--,1);var s=o();void 0!==s&&(r=s)}}return r}l=l||0;for(var u=e.length;u>0&&e[u-1][2]>l;u--)e[u]=e[u-1];e[u]=[t,o,l]},n.n=function(e){var r=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(r,{a:r}),r},n.d=function(e,r){for(var t in r)n.o(r,t)&&!n.o(e,t)&&Object.defineProperty(e,t,{enumerable:!0,get:r[t]})},n.o=function(e,r){return Object.prototype.hasOwnProperty.call(e,r)},function(){var e={831:0,294:0};n.O.j=function(r){return 0===e[r]};var r=function(r,t){var o,l,a=t[0],c=t[1],i=t[2],s=0;if(a.some((function(r){return 0!==e[r]}))){for(o in c)n.o(c,o)&&(n.m[o]=c[o]);if(i)var u=i(n)}for(r&&r(t);s<a.length;s++)l=a[s],n.o(e,l)&&e[l]&&e[l][0](),e[l]=0;return n.O(u)},t=self.webpackChunkrather_simple_carousel=self.webpackChunkrather_simple_carousel||[];t.forEach(r.bind(null,0)),t.push=r.bind(null,t.push.bind(t))}();var o=n.O(void 0,[294],(function(){return n(104)}));o=n.O(o)}();