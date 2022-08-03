import{q as V,e as $,o as d,b as u,d as l,u as e,H as g,w as o,F as y,a as n,L as x,m as f,t as i,k as c,j as v,y as j,c as O,x as _}from"./app.e47e5905.js";import{_ as B}from"./AppLayout.6652fede.js";import{C}from"./Card.96514456.js";import{_ as F}from"./TextInput.a612d17f.js";import{_ as L}from"./TextareaInput.1eb552d4.js";import{_ as N}from"./SelectInput.5dbed021.js";import{L as S}from"./LoadingButton.99115c19.js";import{_ as T}from"./TrashedMessage.b97107ac.js";import{_ as U,a as D,b as P}from"./RoomsList.bdd085c9.js";import"./LanguageSwitcher.004676ec.js";import"./plugin-vue_export-helper.21dcd24c.js";import"./Dropdown.c59b2b1e.js";import"./Icon.b99ae8d7.js";import"./Navbar.5230c35c.js";import"./Logo.f4483971.js";import"./pickBy.615c9b24.js";import"./debounce.f308b35d.js";import"./_defineProperty.14bd0892.js";import"./Pagination.e048b484.js";import"./Modal.e60b654c.js";import"./Spinner.ecf8755a.js";const A={class:"mb-4 text-3xl font-bold"},E=n("span",{class:"font-medium"}," / ",-1),H={class:"flex flex-wrap gap-4"},I={key:0,class:"flex w-full flex-col xl:w-1/4"},q=n("h3",{class:"text-xl font-bold"},"Playlist",-1),M=["onSubmit"],R=["value"],z={class:"flex-1"},fe={__name:"Edit",props:{playlist:Object,filters:Object,answer_types:Object,tracks:Object,moderators:Object},setup(s){const p=s,t=V(p.playlist),b=$().props.value.auth.user,h=()=>{t.put(`/playlists/${p.playlist.id}`,{onSuccess:()=>t.reset("password","photo")})},w=()=>{confirm("Are you sure you want to delete this playlist?")&&_.Inertia.delete(`/playlists/${p.playlist.id}`)},k=()=>{confirm("Are you sure you want to restore this playlist?")&&_.Inertia.put(`/playlists/${p.playlist.id}/restore`)};return(a,m)=>(d(),u(y,null,[l(e(g),{title:`${e(t).name}`},null,8,["title"]),l(B,null,{default:o(()=>[n("h1",A,[l(e(x),{href:a.route("playlists")},{default:o(()=>[f(i(a.__("Playlists")),1)]),_:1},8,["href"]),E,f(" "+i(e(t).name),1)]),n("div",H,[e(b).id===s.playlist.user_id?(d(),u("div",I,[l(C,{class:"mb-4"},{header:o(()=>[q]),footer:o(()=>[s.playlist.deleted_at?c("",!0):(d(),u("button",{key:0,class:"text-sm text-red-500 hover:underline",tabindex:"-1",type:"button",onClick:w},i(a.__("Delete")),1)),l(S,{loading:e(t).processing,class:"btn-primary ml-auto",form:"playlistForm",type:"submit"},{default:o(()=>[f(i(a.__("Update")),1)]),_:1},8,["loading"])]),default:o(()=>[n("form",{id:"playlistForm",class:"p-4",onSubmit:v(h,["prevent"])},[l(F,{modelValue:e(t).name,"onUpdate:modelValue":m[0]||(m[0]=r=>e(t).name=r),error:e(t).errors.name,class:"mb-4 w-full",label:a.__("Title")},null,8,["modelValue","error","label"]),l(L,{modelValue:e(t).description,"onUpdate:modelValue":m[1]||(m[1]=r=>e(t).description=r),error:e(t).errors.description,class:"mb-4 w-full",label:a.__("Description")},null,8,["modelValue","error","label"]),l(N,{modelValue:e(t).user_id,"onUpdate:modelValue":m[2]||(m[2]=r=>e(t).user_id=r),error:e(t).errors.user_id,class:"w-full",label:a.__("Owner")},{default:o(()=>[(d(!0),u(y,null,j(s.playlist.moderators,r=>(d(),u("option",{value:r.id},i(r.name),9,R))),256))]),_:1},8,["modelValue","error","label"]),n("small",null,i(a.__("Transfer the playlist management to a moderator.")),1)],40,M)]),_:1}),l(U,{playlist:s.playlist,class:"mb-4"},null,8,["playlist"]),l(D,{playlist:s.playlist},null,8,["playlist"])])):c("",!0),n("div",z,[s.playlist.deleted_at?(d(),O(T,{key:0,class:"mb-6",onRestore:k},{default:o(()=>[f(i(a.__("This playlist has been deleted.")),1)]),_:1})):c("",!0),l(P,{playlist:s.playlist,filters:s.filters,tracks:s.tracks,answer_types:s.answer_types},null,8,["playlist","filters","tracks","answer_types"])])])]),_:1})],64))}};export{fe as default};
