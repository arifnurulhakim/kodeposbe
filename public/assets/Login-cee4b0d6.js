import{_ as h,u as w,r as d,C as y,I as k,b as r,c as I,d as V,e,D as S,f as a,F as C,H as B,p as L,l as P,G as U}from"./index-7af86d08.js";const j="/demo/images/login/avatar.png";const l=n=>(L("data-v-900ab9ec"),n=n(),P(),n),E={class:"surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden"},F={class:"flex flex-column align-items-center justify-content-center"},T=["src"],D={style:{"border-radius":"56px",padding:"0.3rem",background:"linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)"}},N={class:"w-full surface-card py-8 px-5 sm:px-8",style:{"border-radius":"53px"}},R=l(()=>e("div",{class:"text-center mb-5"},[e("img",{src:j,alt:"Image",height:"50",class:"mb-3"}),e("div",{class:"text-900 text-3xl font-medium mb-3"},"Welcome, Isabel!"),e("span",{class:"text-600 font-medium"},"Sign in to continue")],-1)),$=l(()=>e("label",{for:"email1",class:"block text-900 text-xl font-medium mb-2"},"Email",-1)),G=l(()=>e("label",{for:"password1",class:"block text-900 font-medium text-xl mb-2"},"Password",-1)),H={class:"flex align-items-center justify-content-between mb-5 gap-5"},M={class:"flex align-items-center"},W=l(()=>e("label",{for:"rememberme1"},"Remember me",-1)),q=l(()=>e("a",{class:"font-medium no-underline ml-2 text-right cursor-pointer",style:{color:"var(--primary-color)"}},"Forgot password?",-1)),z={__name:"Login",setup(n){const{layoutConfig:u}=w(),c=d(""),i=d(""),m=d(!1),p=y(()=>`layout/images/${u.darkTheme.value?"logo-white":"logo-dark"}.svg`),_=k(),g=async()=>{try{const o=await U.post("api/login",{email:c.value,password:i.value});if(o.data.status==="success"){const s=o.data.token;localStorage.setItem("token",s),_.push("/Dashboard")}}catch(o){console.error(o)}};return(o,s)=>{const f=r("InputText"),x=r("Password"),b=r("Checkbox"),v=r("Button");return I(),V(C,null,[e("div",E,[e("div",F,[e("img",{src:S(p),alt:"Sakai logo",class:"mb-5 w-6rem flex-shrink-0"},null,8,T),e("div",D,[e("div",N,[R,e("div",null,[$,a(f,{id:"email1",type:"text",placeholder:"Email address",class:"w-full md:w-30rem mb-5",style:{padding:"1rem"},modelValue:c.value,"onUpdate:modelValue":s[0]||(s[0]=t=>c.value=t)},null,8,["modelValue"]),G,a(x,{id:"password1",modelValue:i.value,"onUpdate:modelValue":s[1]||(s[1]=t=>i.value=t),placeholder:"Password",toggleMask:!0,class:"w-full mb-3",inputClass:"w-full",inputStyle:"padding:1rem"},null,8,["modelValue"]),e("div",H,[e("div",M,[a(b,{modelValue:m.value,"onUpdate:modelValue":s[2]||(s[2]=t=>m.value=t),id:"rememberme1",binary:"",class:"mr-2"},null,8,["modelValue"]),W]),q]),a(v,{label:"Sign In",class:"w-full p-3 text-xl",onClick:g})])])])])]),a(B,{simple:""})],64)}}},J=h(z,[["__scopeId","data-v-900ab9ec"]]);export{J as default};
