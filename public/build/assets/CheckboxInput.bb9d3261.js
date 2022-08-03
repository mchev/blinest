import{_ as c}from"./plugin-vue_export-helper.21dcd24c.js";import{v as u}from"./TextInput.a612d17f.js";import{o,b as a,a as l,v as _,t as n,k as r,n as m,C as h,D as f}from"./app.e47e5905.js";const p={inheritAttrs:!1,props:{id:{type:String,default(){return`checkbox-input-${u()}`}},error:String,label:String,modelValue:[Boolean,Number,String]},emits:["update:modelValue"],methods:{focus(){this.$refs.input.focus()},select(){this.$refs.input.select()},setSelectionRange(e,s){this.$refs.input.setSelectionRange(e,s)}}},d=e=>(h("data-v-195982b8"),e=e(),f(),e),v=["for"],b={class:"relative"},k=["id","value","checked"],g=d(()=>l("div",{class:"h-4 w-10 rounded-full bg-neutral-500 shadow-inner"},null,-1)),S=d(()=>l("div",{class:"dot absolute -left-1 -top-1 h-6 w-6 rounded-full bg-neutral-200 shadow transition"},null,-1)),y={key:0,class:"ml-3 font-medium"},I={key:1,class:"form-error"};function V(e,s,t,x,C,w){return o(),a("div",{class:m(e.$attrs.class)},[t.label?(o(),a("label",{key:0,class:"flex cursor-pointer items-center",for:t.id},[l("div",b,[l("input",_({id:t.id},{...e.$attrs,class:null},{value:t.modelValue,checked:t.modelValue,type:"checkbox",class:"sr-only",onInput:s[0]||(s[0]=i=>e.$emit("update:modelValue",i.target.checked))}),null,16,k),g,S]),t.label?(o(),a("div",y,n(t.label),1)):r("",!0)],8,v)):r("",!0),t.error?(o(),a("div",I,n(t.error),1)):r("",!0)],2)}var R=c(p,[["render",V],["__scopeId","data-v-195982b8"]]);export{R as C};
