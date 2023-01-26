import{X as k,n as w,c as _,a,b as e,t as c,w as l,j as m,o as h,I as F,K as V,F as B,v as O,g as x,d as f,h as C}from"./app-d721267d.js";import{_ as I}from"./Icon-97e692a8.js";import{k as N,_ as U,a as L,t as P,p as S}from"./throttle-815b75de.js";import{_ as T}from"./AdminLayout-b514a101.js";import{_ as A}from"./SearchFilter-b63de53d.js";import{_ as H}from"./Pagination-dbebdbb6.js";import{C as R}from"./Card-73e0c1e9.js";import{_ as $}from"./_plugin-vue_export-helper-c27b6911.js";import"./_defineProperty-2694e645.js";import"./isSymbol-00efd63c.js";import"./debounce-c15f2e14.js";import"./LanguageSwitcher-882651fe.js";import"./Dropdown-1979e914.js";function j(s){return function(r,n,p){for(var i=-1,d=Object(r),u=p(r),b=u.length;b--;){var o=u[s?b:++i];if(n(d[o],o,d)===!1)break}return r}}var D=j,E=D,K=E(),M=K,W=M,X=N;function q(s,r){return s&&W(s,r,X)}var z=q,G=L,J=z,Q=U;function Y(s,r){var n={};return r=Q(r),J(s,function(p,i,d){G(n,i,r(p,i,d))}),n}var Z=Y;const ee={components:{Head:k,Icon:I,Link:w,SearchFilter:A,Pagination:H,Card:R},layout:T,props:{filters:Object,users:Object},data(){return{form:{search:this.filters.search,role:this.filters.role,trashed:this.filters.trashed}}},watch:{form:{deep:!0,handler:P(function(){this.$inertia.get(route("admin.users"),S(this.form),{preserveState:!0})},150)}},methods:{reset(){this.form=Z(this.form,()=>null)}}},te={class:"mb-8 text-3xl font-bold"},se={class:"mb-6 flex items-center justify-between"},re=e("label",{class:"mt-4 block text-gray-700"},"Trashed:",-1),ae=e("option",{value:null},null,-1),oe=e("option",{value:"with"},"With Trashed",-1),ne=e("option",{value:"only"},"Only Trashed",-1),ie=[ae,oe,ne],le=e("span",null,"Create",-1),de=e("span",{class:"hidden md:inline"}," User",-1),ce={class:"w-full whitespace-nowrap"},me=e("tr",{class:"text-left font-bold"},[e("th",{class:"px-6 pb-4 pt-6"},"Name"),e("th",{class:"px-6 pb-4 pt-6"},"Email"),e("th",{class:"px-6 pb-4 pt-6"},"Provider"),e("th",{class:"px-6 pb-4 pt-6"},"Inscription"),e("th",{class:"px-6 pb-4 pt-6",colspan:"2"},"Role")],-1),he={class:"border-t"},_e=["src"],fe={class:"border-t"},pe={class:"border-t"},ue={class:"border-t"},be={class:"border-t"},xe={class:"w-px border-t"},ve={key:0},ye=e("td",{class:"border-t px-6 py-4",colspan:"4"},"No users found.",-1),ge=[ye];function ke(s,r,n,p,i,d){const u=m("Head"),b=m("search-filter"),o=m("Link"),v=m("icon"),y=m("Pagination"),g=m("card");return h(),_("div",null,[a(u,{title:"Users"}),e("h1",te,"Users ("+c(n.users.total)+")",1),e("div",se,[a(b,{modelValue:i.form.search,"onUpdate:modelValue":r[1]||(r[1]=t=>i.form.search=t),class:"mr-4 w-full max-w-md",onReset:d.reset},{default:l(()=>[re,F(e("select",{"onUpdate:modelValue":r[0]||(r[0]=t=>i.form.trashed=t),class:"form-select mt-1 w-full"},ie,512),[[V,i.form.trashed]])]),_:1},8,["modelValue","onReset"]),a(o,{class:"btn-primary",href:s.route("admin.users.create")},{default:l(()=>[le,de]),_:1},8,["href"])]),a(g,null,{default:l(()=>[e("table",ce,[me,(h(!0),_(B,null,O(n.users.data,t=>(h(),_("tr",{key:t.id,class:"focus-within:bg-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700"},[e("td",he,[a(o,{class:"flex items-center px-6 py-4 focus:text-blinest-500",href:s.route("admin.users.edit",t.id)},{default:l(()=>[t.photo?(h(),_("img",{key:0,class:"-my-2 mr-2 block h-10 w-10 rounded-full",src:t.photo},null,8,_e)):x("",!0),f(" "+c(t.name)+" ",1),t.deleted_at?(h(),C(v,{key:1,name:"trash",class:"ml-2 h-3 w-3 flex-shrink-0 fill-gray-400"})):x("",!0)]),_:2},1032,["href"])]),e("td",fe,[a(o,{class:"flex items-center px-6 py-4",href:s.route("admin.users.edit",t.id),tabindex:"-1"},{default:l(()=>[f(c(t.email),1)]),_:2},1032,["href"])]),e("td",pe,[a(o,{class:"flex items-center px-6 py-4",href:s.route("admin.users.edit",t.id),tabindex:"-1"},{default:l(()=>[f(c(t.provider),1)]),_:2},1032,["href"])]),e("td",ue,[a(o,{class:"flex items-center px-6 py-4",href:s.route("admin.users.edit",t.id),tabindex:"-1"},{default:l(()=>[f(c(t.created_at),1)]),_:2},1032,["href"])]),e("td",be,[a(o,{class:"flex items-center px-6 py-4",href:s.route("admin.users.edit",t.id),tabindex:"-1"},{default:l(()=>[f(c(t.is_admin?"Admin":"User"),1)]),_:2},1032,["href"])]),e("td",xe,[a(o,{class:"flex items-center px-4",href:s.route("admin.users.edit",t.id),tabindex:"-1"},{default:l(()=>[a(v,{name:"cheveron-right",class:"block h-6 w-6 fill-gray-400"})]),_:2},1032,["href"])])]))),128)),n.users.length===0?(h(),_("tr",ve,ge)):x("",!0)]),a(y,{links:n.users.links},null,8,["links"])]),_:1})])}const Ae=$(ee,[["render",ke]]);export{Ae as default};