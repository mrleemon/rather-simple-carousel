!function(e){var t={};function r(l){if(t[l])return t[l].exports;var o=t[l]={i:l,l:!1,exports:{}};return e[l].call(o.exports,o,o.exports,r),o.l=!0,o.exports}r.m=e,r.c=t,r.d=function(e,t,l){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:l})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var l=Object.create(null);if(r.r(l),Object.defineProperty(l,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(l,o,function(t){return e[t]}.bind(null,o));return l},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=3)}([function(e,t){!function(){e.exports=this.wp.element}()},,,function(e,t,r){"use strict";r.r(t);var l=r(0),o=wp.blocks.registerBlockType,a=wp.components,n=a.G,c=a.Path,s=a.SVG,i=a.Placeholder,u=a.SelectControl,p=wp.data.withSelect,f=wp.element.RawHTML,m=wp.i18n.__;o("occ/rather-simple-carousel",{title:m("Rather Simple Carousel","rather-simple-carousel"),description:m("Display a carousel.","rather-simple-carousel"),icon:Object(l.createElement)(s,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},Object(l.createElement)(c,{fill:"none",d:"M0 0h24v24H0V0z"}),Object(l.createElement)(n,null,Object(l.createElement)(c,{d:"M20 4v12H8V4h12m0-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 9.67l1.69 2.26 2.48-3.1L19 15H9zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"}))),category:"common",keywords:[m("images","rather-simple-carousel"),m("photos","rather-simple-carousel")],attributes:{id:{type:"integer",default:0}},edit:p((function(e){return{posts:e("core").getEntityRecords("postType","carousel",{per_page:-1,orderby:"title",order:"asc",_fields:"id,title"})}}))((function(e){var t=e.attributes,r=e.className;if(!e.posts)return m("Loading...","rather-simple-carousel");if(0===e.posts.length)return m("No carousels found","rather-simple-carousel");var o=[];o.push({label:m("Select a carousel...","rather-simple-carousel"),value:""});for(var a=0;a<e.posts.length;a++)o.push({label:e.posts[a].title.raw,value:e.posts[a].id});return Object(l.createElement)(i,{key:"rather-simple-carousel-block",icon:"images-alt2",label:m("Rather Simple Carousel","rather-simple-carousel"),className:r},Object(l.createElement)(u,{label:m("Select a carousel:","rather-simple-carousel"),value:t.id,options:o,onChange:function(t){e.setAttributes({id:Number(t)})}}))})),save:function(e){var t='[carousel id="'+e.attributes.id+'"]';return Object(l.createElement)(f,null,t)}})}]);