import{j as p,l as v,o as l,c as x,w as d,a as t,t as e,b as a,d as _,h as c,F as i,y as u,g}from"./app.2ccf841f.js";import{_ as y}from"./Modal.95e75e17.js";import{C as h}from"./Card.53d0f134.js";import{S as k}from"./Spinner.ad70554a.js";import"./plugin-vue_export-helper.21dcd24c.js";const S={class:"text-sm bg-neutral-800"},j={class:"px-4 pt-2 flex items-center justify-between"},N={class:"font-bold uppercase text-teal-500"},C=["title"],B=t("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-6 h-6"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18L18 6M6 6l12 12"})],-1),T=[B],P={key:0},L={key:1,class:"grid grid-cols-1 xl:grid-cols-2"},M={class:"flex w-full items-center justify-between"},V={class:"font-bold"},$={class:"rounded bg-teal-500 p-1 font-bold text-white"},F=t("sup",null,"PTS",-1),A={class:"w-full"},D=t("th",{class:"border-b-2 p-2 text-left"},"#",-1),E={class:"border-b-2 p-2 text-left"},O={class:"border-b-2 p-2 text-left"},W={class:"border-b p-2"},q={class:"truncate border-b p-2"},z={class:"border-b p-2"},G={class:"flex w-full items-center justify-between"},H={class:"font-bold"},I={key:0,class:"rounded bg-teal-500 p-1 font-bold text-white"},J=t("sup",null,"PTS",-1),K={class:"w-full"},Q=t("th",{class:"border-b-2 p-2 text-left"},"#",-1),R={class:"border-b-2 p-2 text-left"},U={class:"border-b-2 p-2 text-left"},X={class:"border-b p-2"},Y={class:"truncate border-b p-2"},Z={class:"border-b p-2"},tt={class:"flex w-full items-center justify-between"},et={class:"font-bold"},st={class:"rounded bg-teal-500 p-1 font-bold text-white"},ot=t("sup",null,"PTS",-1),lt={class:"w-full"},at=t("th",{class:"border-b-2 p-2 text-left"},"#",-1),nt={class:"border-b-2 p-2 text-left"},dt={class:"border-b-2 p-2 text-left"},rt={class:"border-b p-2"},_t={class:"truncate border-b p-2"},ct={class:"border-b p-2"},it={class:"flex w-full items-center justify-between"},ut={class:"font-bold"},ht={class:"rounded bg-teal-500 p-1 font-bold text-white"},bt=t("sup",null,"PTS",-1),ft={class:"w-full"},mt=t("th",{class:"border-b-2 p-2 text-left"},"#",-1),pt={class:"border-b-2 p-2 text-left"},wt={class:"border-b-2 p-2 text-left"},vt={class:"border-b p-2"},xt={class:"truncate border-b p-2"},gt={class:"border-b p-2"},Ct={__name:"PodiumModal",props:{room:Object,show:Boolean},setup(b){const w=b,f=p(!0),n=p(null);return v(()=>{axios.get(`/rooms/${w.room.id}/scores`).then(s=>{f.value=!1,n.value=s.data})}),(s,m)=>(l(),x(y,{show:b.show,maxWidth:"5xl"},{default:d(()=>[t("div",S,[t("div",j,[t("h2",N,e(b.room.name),1),t("button",{onClick:m[0]||(m[0]=o=>s.$emit("close")),title:s.__("Close"),class:"hover:text-white hover:animate-[spin_.5s_ease-in-out]"},T,8,C)]),f.value?(l(),a("div",P,[_(k)])):(l(),a("div",L,[_(h,{class:"m-4"},{header:d(()=>[t("div",M,[t("h3",V,e(s.__("All-time")),1),t("span",$,[c(e(n.value.user.lifetime.total),1),F])])]),default:d(()=>[t("table",A,[t("thead",null,[t("tr",null,[D,t("th",E,e(s.__("Name")),1),t("th",O,e(s.__("Score")),1)])]),t("tbody",null,[(l(!0),a(i,null,u(n.value.lifetime,(o,r)=>(l(),a("tr",null,[t("td",W,e(r+1),1),t("td",q,e(o.user.name),1),t("td",z,e(o.total),1)]))),256))])])]),_:1}),_(h,{class:"m-4"},{header:d(()=>[t("div",G,[t("h3",H,e(s.__("Teams")),1),n.value.user.team?(l(),a("span",I,[c(e(n.value.user.team.total),1),J])):g("",!0)])]),default:d(()=>[t("table",K,[t("thead",null,[t("tr",null,[Q,t("th",R,e(s.__("Name")),1),t("th",U,e(s.__("Score")),1)])]),t("tbody",null,[(l(!0),a(i,null,u(n.value.teams,(o,r)=>(l(),a("tr",null,[t("td",X,e(r+1),1),t("td",Y,e(o.team.name),1),t("td",Z,e(o.total),1)]))),256))])])]),_:1}),_(h,{class:"m-4"},{header:d(()=>[t("div",tt,[t("h3",et,e(s.__("Last 7 days")),1),t("span",st,[c(e(n.value.user.week.total),1),ot])])]),default:d(()=>[t("table",lt,[t("thead",null,[t("tr",null,[at,t("th",nt,e(s.__("Name")),1),t("th",dt,e(s.__("Score")),1)])]),t("tbody",null,[(l(!0),a(i,null,u(n.value.week,(o,r)=>(l(),a("tr",null,[t("td",rt,e(r+1),1),t("td",_t,e(o.user.name),1),t("td",ct,e(o.total),1)]))),256))])])]),_:1}),_(h,{class:"m-4"},{header:d(()=>[t("div",it,[t("h3",ut,e(s.__("Last 30 days")),1),t("span",ht,[c(e(n.value.user.month.total),1),bt])])]),default:d(()=>[t("table",ft,[t("thead",null,[t("tr",null,[mt,t("th",pt,e(s.__("Name")),1),t("th",wt,e(s.__("Score")),1)])]),t("tbody",null,[(l(!0),a(i,null,u(n.value.month,(o,r)=>(l(),a("tr",null,[t("td",vt,e(r+1),1),t("td",xt,e(o.user.name),1),t("td",gt,e(o.total),1)]))),256))])])]),_:1})]))])]),_:1},8,["show"]))}};export{Ct as default};