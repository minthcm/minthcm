import{l as E,n as t,q as l,r,F as m,v as _,t as p,j as o,_ as x,E as V,aE as S,Z as B,$ as L,aR as T,z as w,s as a,C as c,w as g,aS as M,Y as P,aT as D,x as R,y as k,P as $,aU as A,aV as N,aW as F}from"./index.af03a0f5.js";import{_ as I}from"./Field.vue_vue_type_style_index_0_lang.4e865ee0.js";const U={class:"mint-data-table"},q=E({__name:"MintDataTable",props:{columns:{},records:{}},setup(b){const e=b;return(u,y)=>(t(),l("table",U,[r("thead",null,[r("tr",null,[(t(!0),l(m,null,_(e.columns,s=>(t(),l("th",{key:s.name},p(s.label),1))),128))])]),r("tbody",null,[(t(!0),l(m,null,_(e.records,s=>(t(),l("tr",{key:s.id},[(t(!0),l(m,null,_(u.columns,d=>(t(),l("td",{key:d.name},[o(I,{view:"list",data:{bean:s},defs:d},null,8,["data","defs"])]))),128))]))),128))])]))}});const z=x(q,[["__scopeId","data-v-3eaad0c9"]]),O={class:"subpanels-panel"},j=["textContent"],Q=E({__name:"MintPanelSubpanels",setup(b){V(()=>{e.fetchLanguagesForSubpanels(),e.fetchSubpanelsData()});const e=S(),u=B();L();const y=T(),s=w([]);return(d,f)=>(t(),l("div",O,[r("h1",null,p(a(u).label("LBL_RELATED_RECORDS")),1),o(F,{modelValue:s.value,"onUpdate:modelValue":f[0]||(f[0]=n=>s.value=n),class:"subpanels-accordion",multiple:"",variant:"accordion"},{default:c(()=>[(t(!0),l(m,null,_(a(e).subpanels,n=>{var v;return t(),g(M,{key:n.key,"bg-color":"transparent",value:n.key,class:P(["mint-subpanel",!((v=n.records)!=null&&v.length)&&"mint-subpanel-disabled"])},{default:c(()=>[o(D,{class:"mint-subpanel-title","hide-actions":""},{default:c(()=>{var i,h;return[r("div",null,[o(R,{icon:s.value.includes(n.key)?"mdi-chevron-down":"mdi-chevron-right"},null,8,["icon"]),r("span",null,p(a(u).label(n.label,d.$route.params.module)),1),(i=n.records)!=null&&i.length?(t(),l("span",{key:0,class:"mint-subpanel-records-count",textContent:p(n.records.length)},null,8,j)):k("",!0)]),a(y).hasAccess(n.module,"edit",!0)&&((h=n.properties.top_buttons)==null?void 0:h.find(C=>C.widget_class==="SubPanelTopButtonQuickCreate"))?(t(),g($,{key:0,class:"mint-subpanel-create-btn",variant:s.value.includes(n.key)?"primary":"regular",text:a(u).label("LBL_CREATE_BUTTON_LABEL"),icon:"mdi-plus",onClick:A(()=>d.$router.push({name:"module-view",params:{module:n.module,action:"EditView"},query:{return_action:"DetailView",parent_id:a(e).bean.id,return_id:a(e).bean.id,return_module:a(e).bean.module_name,parent_type:a(e).bean.module_name,parent_name:a(e).bean.attributes.name,candidate_id:a(e).bean.module_name==="Candidates"?a(e).bean.id:null,candidate_name:a(e).bean.module_name==="Candidates"?a(e).bean.attributes.name:null,employee_id:a(e).bean.module_name==="Employees"?a(e).bean.id:null,employee_name:a(e).bean.module_name==="Employees"?a(e).bean.attributes.name:null,employees_name:a(e).bean.module_name==="Employees"?a(e).bean.attributes.name:null}}),["stop"])},null,8,["variant","text","onClick"])):k("",!0)]}),_:2},1024),o(N,{class:"mint-subpanel-content"},{default:c(()=>{var i;return[o(z,{columns:n.columns,records:(i=n.records)!=null?i:[]},null,8,["columns","records"])]}),_:2},1024)]),_:2},1032,["value","class"])}),128))]),_:1},8,["modelValue"])]))}});const Z=x(Q,[["__scopeId","data-v-50fcea37"]]);export{Z as default};