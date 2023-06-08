import{C as S}from"./CountryService-353aad24.js";import{r as t,o as T,b as n,c as D,d as A,e as l,f as s}from"./index-7af86d08.js";const B={class:"card"},R=l("h5",null,"Float Label",-1),q={class:"grid p-fluid mt-3"},F={class:"field col-12 md:col-4"},P={class:"p-float-label"},Y=l("label",{for:"inputtext"},"InputText",-1),E={class:"field col-12 md:col-4"},G={class:"p-float-label"},O=l("label",{for:"autocomplete"},"AutoComplete",-1),j={class:"field col-12 md:col-4"},z={class:"p-float-label"},H=l("label",{for:"calendar"},"Calendar",-1),J={class:"field col-12 md:col-4"},K={class:"p-float-label"},Q=l("label",{for:"chips"},"Chips",-1),W={class:"field col-12 md:col-4"},X={class:"p-float-label"},Z=l("label",{for:"inputmask"},"InputMask",-1),$={class:"field col-12 md:col-4"},ll={class:"p-float-label"},el=l("label",{for:"inputnumber"},"InputNumber",-1),ol={class:"field col-12 md:col-4"},tl={class:"p-inputgroup"},sl=l("span",{class:"p-inputgroup-addon"},[l("i",{class:"pi pi-user"})],-1),nl={class:"p-float-label"},al=l("label",{for:"inputgroup"},"InputGroup",-1),ul={class:"field col-12 md:col-4"},dl={class:"p-float-label"},il=l("label",{for:"dropdown"},"Dropdown",-1),cl={class:"field col-12 md:col-4"},pl={class:"p-float-label"},ml=l("label",{for:"multiselect"},"MultiSelect",-1),rl={class:"field col-12 md:col-4"},_l={class:"p-float-label"},vl=l("label",{for:"textarea"},"Textarea",-1),hl={__name:"FloatLabel",setup(fl){const c=t([]),p=t([{name:"New York",code:"NY"},{name:"Rome",code:"RM"},{name:"London",code:"LDN"},{name:"Istanbul",code:"IST"},{name:"Paris",code:"PRS"}]),m=t(null),r=t(null),_=t(null),v=t(null),f=t(null),V=t(null),b=t(null),h=t(null),C=t(null),x=t(null),I=t(null),g=new S;T(()=>{g.getCountries().then(u=>c.value=u)});const w=u=>{const e=[],d=u.query;for(let a=0;a<c.value.length;a++){const i=c.value[a];i.name.toLowerCase().indexOf(d.toLowerCase())==0&&e.push(i)}m.value=e};return(u,e)=>{const d=n("InputText"),a=n("AutoComplete"),i=n("Calendar"),U=n("Chips"),k=n("InputMask"),y=n("InputNumber"),L=n("Dropdown"),M=n("MultiSelect"),N=n("Textarea");return D(),A("div",B,[R,l("div",q,[l("div",F,[l("span",P,[s(d,{type:"text",id:"inputtext",modelValue:r.value,"onUpdate:modelValue":e[0]||(e[0]=o=>r.value=o)},null,8,["modelValue"]),Y])]),l("div",E,[l("span",G,[s(a,{id:"autocomplete",modelValue:_.value,"onUpdate:modelValue":e[1]||(e[1]=o=>_.value=o),suggestions:m.value,onComplete:e[2]||(e[2]=o=>w(o)),field:"name"},null,8,["modelValue","suggestions"]),O])]),l("div",j,[l("span",z,[s(i,{inputId:"calendar",modelValue:v.value,"onUpdate:modelValue":e[3]||(e[3]=o=>v.value=o)},null,8,["modelValue"]),H])]),l("div",J,[l("span",K,[s(U,{inputId:"chips",modelValue:f.value,"onUpdate:modelValue":e[4]||(e[4]=o=>f.value=o)},null,8,["modelValue"]),Q])]),l("div",W,[l("span",X,[s(k,{id:"inputmask",mask:"99/99/9999",modelValue:V.value,"onUpdate:modelValue":e[5]||(e[5]=o=>V.value=o)},null,8,["modelValue"]),Z])]),l("div",$,[l("span",ll,[s(y,{id:"inputnumber",modelValue:b.value,"onUpdate:modelValue":e[6]||(e[6]=o=>b.value=o)},null,8,["modelValue"]),el])]),l("div",ol,[l("div",tl,[sl,l("span",nl,[s(d,{type:"text",id:"inputgroup",modelValue:h.value,"onUpdate:modelValue":e[7]||(e[7]=o=>h.value=o)},null,8,["modelValue"]),al])])]),l("div",ul,[l("span",dl,[s(L,{id:"dropdown",options:p.value,modelValue:C.value,"onUpdate:modelValue":e[8]||(e[8]=o=>C.value=o),optionLabel:"name"},null,8,["options","modelValue"]),il])]),l("div",cl,[l("span",pl,[s(M,{id:"multiselect",options:p.value,modelValue:x.value,"onUpdate:modelValue":e[9]||(e[9]=o=>x.value=o),optionLabel:"name",filter:!1},null,8,["options","modelValue"]),ml])]),l("div",rl,[l("span",_l,[s(N,{inputId:"textarea",rows:"3",cols:"30",modelValue:I.value,"onUpdate:modelValue":e[10]||(e[10]=o=>I.value=o)},null,8,["modelValue"]),vl])])])])}}};export{hl as default};
