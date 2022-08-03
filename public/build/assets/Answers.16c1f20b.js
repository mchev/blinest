import{g as d,h as f,i as v,E as y,o as a,c as k,w as i,a as t,b as l,t as o,m as h,k as b,y as _,d as g,F as m,N as w}from"./app.e47e5905.js";import{C as E}from"./Card.96514456.js";import"./plugin-vue_export-helper.21dcd24c.js";const N={class:"flex items-center justify-between w-full"},B=t("h3",{class:"text-xl font-bold"},"Playlist",-1),C={key:0,class:"text-xl font-bold text-neutral-500"},T={class:"text-neutral-700"},V={class:"h-64 md:h-80 2xl:h-96 pr-2 overflow-y-scroll"},S={class:"p-2"},A=["src","alt"],F={class:"flex-grow p-2"},L={class:"mr-1 flex-shrink-0 rounded bg-neutral-500 px-1 text-[10px] font-bold uppercase text-neutral-300 text-white"},M={__name:"Answers",props:{users:Array,channel:String},setup(p){const n=p,x=d(n.users),s=d(null),u=d([]);return f(()=>n.users,e=>{x.value=e}),v(()=>{Echo.channel(n.channel).listen("RoundStarted",e=>{s.value=e.round,u.value=[]}).listen("TrackPlayed",e=>{s.value=e.round}).listen("TrackEnded",e=>{u.value.unshift(e.track),s.value=e.round})}),y(()=>{Echo.leave(n.channel)}),(e,P)=>(a(),k(E,null,{header:i(()=>[t("div",N,[B,s.value?(a(),l("span",C,[t("span",T,o(s.value.current),1),h(" / "+o(s.value.tracks.length+1),1)])):b("",!0)])]),default:i(()=>[t("ul",V,[(a(!0),l(m,null,_(u.value,r=>(a(),l("li",{key:r.id,class:"mb-2 flex rounded bg-neutral-900 opacity-70"},[t("div",S,[t("img",{src:r.artwork_url,alt:r.album_name,class:"h-20 w-auto rounded"},null,8,A)]),t("div",F,[g(w,{name:"flip-list",tag:"ul"},{default:i(()=>[(a(!0),l(m,null,_(r.answers,c=>(a(),l("li",{key:c.id,class:"mb-1 flex items-start text-sm"},[t("span",L,o(e.__(c.type.name)),1),h(" "+o(c.value),1)]))),128))]),_:2},1024)])]))),128))])]),_:1}))}};export{M as default};
