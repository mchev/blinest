import{q as v,o as b,b as f,d as s,u as o,H as g,w as d,F as k,a as m,L as U,m as p,t as i,k as h,j as c,y as F,A as x,K as C}from"./app.e47e5905.js";import{_ as S}from"./AdminLayout.84615827.js";import{F as P}from"./FileInput.c720e1f3.js";import{_ as u}from"./TextInput.a612d17f.js";import{_ as $}from"./TextareaInput.1eb552d4.js";import{_ as D}from"./SelectInput.5dbed021.js";import{C as _}from"./CheckboxInput.bb9d3261.js";import{L as V}from"./LoadingButton.99115c19.js";import{_ as L,a as N}from"./PlaylistsManager.64f9a6fb.js";import{C as y}from"./Card.96514456.js";import"./_defineProperty.14bd0892.js";import"./Logo.f4483971.js";import"./plugin-vue_export-helper.21dcd24c.js";import"./Dropdown.c59b2b1e.js";import"./LanguageSwitcher.004676ec.js";import"./Icon.b99ae8d7.js";import"./debounce.f308b35d.js";const A={class:"mb-8 text-3xl font-bold"},B={class:"flex flex-wrap gap-4"},E={class:"flex w-full flex-col xl:w-1/4"},R={class:"text-xl font-bold"},T=["onSubmit"],j={class:"flex"},H={class:"flex flex-wrap"},I=["value"],O={class:"flex-1"},q={class:"text-xl font-bold"},K=["onSubmit"],M={class:"flex w-full flex-wrap"},W={class:"flex w-full flex-wrap"},z={class:"text-xl font-bold"},G=["onSubmit"],_o={__name:"Edit",props:{room:Object,categories:Array,available_playlists:Array},setup(n){const t=n,e=v({_method:"put",name:t.room.name,description:t.room.description,category_id:t.room.category_id,is_public:t.room.is_public,is_pro:t.room.is_pro,is_random:t.room.is_random,is_active:t.room.is_active,is_chat_active:t.room.is_chat_active,discord_webhook_url:t.room.discord_webhook_url,color:t.room.color,password:t.room.password,tracks_by_round:t.room.tracks_by_round,track_duration:t.room.track_duration,pause_between_tracks:t.room.pause_between_tracks,pause_between_rounds:t.room.pause_between_rounds,photo:null,playlists:t.room_playlists}),w=()=>{e.post(route("admin.rooms.update",t.room.id))};return(a,r)=>(b(),f(k,null,[s(o(g),{title:a.__("Edit Room")},null,8,["title"]),s(S,null,{default:d(()=>[m("h1",A,[s(o(U),{href:a.route("admin.rooms")},{default:d(()=>[p(i(a.__("Rooms")),1)]),_:1},8,["href"]),p(" / "+i(n.room.name),1)]),m("div",B,[m("div",E,[s(y,{class:"mb-4"},{header:d(()=>[m("h3",R,i(a.__("Room")),1)]),footer:d(()=>[n.room.deleted_at?h("",!0):(b(),f("button",{key:0,class:"text-sm text-red-600 hover:underline",tabindex:"-1",type:"button",onClick:r[4]||(r[4]=(...l)=>a.destroy&&a.destroy(...l))},i(a.__("Delete")),1)),s(V,{loading:o(e).processing,class:"btn-primary ml-auto",form:"roomForm",type:"submit"},{default:d(()=>[p(i(a.__("Update")),1)]),_:1},8,["loading"])]),default:d(()=>[m("form",{onSubmit:c(w,["prevent"]),id:"roomForm"},[m("div",j,[m("div",H,[s(u,{modelValue:o(e).name,"onUpdate:modelValue":r[0]||(r[0]=l=>o(e).name=l),error:o(e).errors.name,class:"mb-4 w-full",label:a.__("Name")},null,8,["modelValue","error","label"]),s($,{modelValue:o(e).description,"onUpdate:modelValue":r[1]||(r[1]=l=>o(e).description=l),error:o(e).errors.description,class:"mb-4 w-full",label:a.__("Description")},null,8,["modelValue","error","label"]),s(D,{modelValue:o(e).category_id,"onUpdate:modelValue":r[2]||(r[2]=l=>o(e).category_id=l),error:o(e).errors.category_id,class:"mb-4 w-full",label:a.__("Category")},{default:d(()=>[(b(!0),f(k,null,F(n.categories,l=>(b(),f("option",{key:l.id,value:l.id},i(l.name),9,I))),128))]),_:1},8,["modelValue","error","label"]),s(P,{modelValue:o(e).photo,"onUpdate:modelValue":r[3]||(r[3]=l=>o(e).photo=l),error:o(e).errors.photo,class:"mb-4 w-full",type:"file",accept:"image/*",label:a.__("Photo")},null,8,["modelValue","error","label"])])])],40,T)]),_:1}),s(L,{room:n.room},null,8,["room"])]),m("div",O,[s(y,{class:"mb-4"},{header:d(()=>[m("h2",q,i(a.__("Options")),1)]),footer:d(()=>[s(V,{loading:o(e).processing,class:"btn-primary ml-auto",form:"optionsForm",type:"submit"},{default:d(()=>[p(i(a.__("Update")),1)]),_:1},8,["loading"])]),default:d(()=>[m("form",{onSubmit:c(w,["prevent"]),id:"optionsForm",class:"flex flex-wrap"},[m("div",M,[s(u,{modelValue:o(e).tracks_by_round,"onUpdate:modelValue":r[5]||(r[5]=l=>o(e).tracks_by_round=l),error:o(e).errors.tracks_by_round,type:"number",step:"1",min:"1",max:"100",class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Tracks by round")},null,8,["modelValue","error","label"]),s(u,{modelValue:o(e).track_duration,"onUpdate:modelValue":r[6]||(r[6]=l=>o(e).track_duration=l),error:o(e).errors.track_duration,type:"number",step:"1",min:"5",max:"30",class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Track duration")},null,8,["modelValue","error","label"]),s(u,{modelValue:o(e).pause_between_tracks,"onUpdate:modelValue":r[7]||(r[7]=l=>o(e).pause_between_tracks=l),error:o(e).errors.pause_between_tracks,type:"number",step:"1",min:"0",max:"60",class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Pause between tracks")},null,8,["modelValue","error","label"]),s(u,{modelValue:o(e).pause_between_rounds,"onUpdate:modelValue":r[8]||(r[8]=l=>o(e).pause_between_rounds=l),error:o(e).errors.pause_between_rounds,type:"number",step:"1",min:"0",max:"60",class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Pause between rounds")},null,8,["modelValue","error","label"])]),s(u,{modelValue:o(e).color,"onUpdate:modelValue":r[9]||(r[9]=l=>o(e).color=l),type:"color",error:o(e).errors.color,class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Color")},null,8,["modelValue","error","label"]),m("div",W,[s(_,{modelValue:o(e).is_public,"onUpdate:modelValue":r[10]||(r[10]=l=>o(e).is_public=l),error:o(e).errors.is_public,class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Public")},null,8,["modelValue","error","label"]),s(_,{modelValue:o(e).is_pro,"onUpdate:modelValue":r[11]||(r[11]=l=>o(e).is_pro=l),error:o(e).errors.is_pro,class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Pro")},null,8,["modelValue","error","label"]),s(_,{modelValue:o(e).is_active,"onUpdate:modelValue":r[12]||(r[12]=l=>o(e).is_active=l),error:o(e).errors.is_active,class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Active")},null,8,["modelValue","error","label"]),s(_,{modelValue:o(e).is_chat_active,"onUpdate:modelValue":r[13]||(r[13]=l=>o(e).is_chat_active=l),error:o(e).errors.is_chat_active,class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Chatbox")},null,8,["modelValue","error","label"]),s(_,{modelValue:o(e).password,"onUpdate:modelValue":r[14]||(r[14]=l=>o(e).password=l),class:"w-full pr-4 pb-4 md:w-1/2",label:a.__("Password")},null,8,["modelValue","label"])]),x(s(u,{modelValue:o(e).password,"onUpdate:modelValue":r[15]||(r[15]=l=>o(e).password=l),error:o(e).errors.password,class:"pb-6",type:"password",autocomplete:"new-password",label:a.__("Password")},null,8,["modelValue","error","label"]),[[C,o(e).password]])],40,K)]),_:1}),s(N,{class:"mb-4",room:n.room,playlists:n.available_playlists},null,8,["room","playlists"]),s(y,{class:"mb-4"},{header:d(()=>[m("h2",z,i(a.__("Discord")),1)]),footer:d(()=>[s(V,{loading:o(e).processing,class:"btn-primary ml-auto",form:"discordForm",type:"submit"},{default:d(()=>[p(i(a.__("Update")),1)]),_:1},8,["loading"])]),default:d(()=>[m("form",{onSubmit:c(w,["prevent"]),id:"discordForm",class:"flex flex-wrap"},[s(u,{modelValue:o(e).discord_webhook_url,"onUpdate:modelValue":r[16]||(r[16]=l=>o(e).discord_webhook_url=l),type:"url",error:o(e).errors.discord_webhook_url,class:"mb-4 w-full",label:a.__("Webhook")},null,8,["modelValue","error","label"])],40,G)]),_:1})])])]),_:1})],64))}};export{_o as default};
