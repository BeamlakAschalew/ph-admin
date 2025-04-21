import{D as c}from"./app-CUeQW6pG.js";/**
 * @license lucide-vue-next v0.487.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const l=e=>e.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase(),h=e=>e.replace(/^([A-Z])|[\s-_]+(\w)/g,(t,r,o)=>o?o.toUpperCase():r.toLowerCase()),w=e=>{const t=h(e);return t.charAt(0).toUpperCase()+t.slice(1)},p=(...e)=>e.filter((t,r,o)=>!!t&&t.trim()!==""&&o.indexOf(t)===r).join(" ").trim();/**
 * @license lucide-vue-next v0.487.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var s={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor","stroke-width":2,"stroke-linecap":"round","stroke-linejoin":"round"};/**
 * @license lucide-vue-next v0.487.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const m=({size:e,strokeWidth:t=2,absoluteStrokeWidth:r,color:o,iconNode:n,name:a,class:g,...d},{slots:i})=>c("svg",{...s,width:e||s.width,height:e||s.height,stroke:o||s.stroke,"stroke-width":r?Number(t)*24/Number(e):t,class:p("lucide",...a?[`lucide-${l(w(a))}-icon`,`lucide-${l(a)}`]:["lucide-icon"]),...d},[...n.map(u=>c(...u)),...i.default?[i.default()]:[]]);/**
 * @license lucide-vue-next v0.487.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const C=(e,t)=>(r,{slots:o})=>c(m,{...r,iconNode:t,name:e},o);/**
 * @license lucide-vue-next v0.487.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const k=C("pill",[["path",{d:"m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z",key:"wa1lgi"}],["path",{d:"m8.5 8.5 7 7",key:"rvfmvr"}]]);export{k as P,C as c};
