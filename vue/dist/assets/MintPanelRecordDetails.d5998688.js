import{_ as C}from"./Field.vue_vue_type_style_index_0_lang.4e865ee0.js";import{l as V,aE as I,Z as h,aG as N,e as D,z as M,n as a,q as d,r as v,t as w,s as t,F as m,w as E,P as L,y as r,j as R,v as k,_ as U}from"./index.af03a0f5.js";const O={class:"details-panel"},P={class:"tabs-container"},$={key:1,class:"buttons"},G={class:"fields-container"},j=V({__name:"MintPanelRecordDetails",props:{data:{}},setup(y){const b=y,e=I(),c=h(),B=N(),f=D(()=>{var n,o,u;return c.label((o=(n=b.data)==null?void 0:n.title)!=null?o:"LBL_DETAILS",(u=B.currentModule)==null?void 0:u.name)}),i=M(""),p={"":{icon:"mdi-check",text:"LBL_SAVE_BUTTON_LABEL"},saving:{icon:"",text:"LBL_SAVING"},saved:{icon:"mdi-check",text:"LBL_SAVED"},error:{icon:"mdi-close",text:"LBL_MINT4_STATUS_BOX_ERROR"}},x=n=>{e.inlineEditField=n},T=()=>{e.view="edit",e.inlineEditField="",e.inlineEditFieldSaving=""},F=()=>{e.bean.dirtyFields.clear(),e.bean.attributes={...e.bean.syncAttributes},e.view="detail",e.inlineEditField="",e.inlineEditFieldSaving="",i.value=""},S=async()=>{const n=e.inlineEditField;n&&(e.inlineEditFieldSaving=n),e.inlineEditField="",i.value="saving";const o=await e.saveBean();i.value=[200,201].includes(o.status)?"saved":"error",setTimeout(()=>{i.value==="saved"?(e.view="detail",e.inlineEditField="",e.inlineEditFieldSaving=""):e.inlineEditField=n,i.value=""},2e3)};return(n,o)=>{var u;return a(),d("div",O,[v("div",P,[v("h1",null,w(f.value),1),((u=t(e).bean.acl_access)==null?void 0:u.edit)===!0?(a(),d(m,{key:0},[t(e).view==="detail"?(a(),E(L,{key:0,class:"ml-auto",icon:"mdi-pencil",text:`${t(c).label("LBL_EDIT_BUTTON_LABEL")} ${t(c).label("LBL_DETAILS")}`,onClick:T},null,8,["text"])):r("",!0),t(e).view==="edit"?(a(),d("div",$,[i.value?r("",!0):(a(),E(L,{key:0,icon:"mdi-close",text:t(c).label("LBL_CANCEL_BUTTON_LABEL"),onClick:F},null,8,["text"])),R(L,{disabled:!!i.value||!t(e).isBeanChanged,icon:p[i.value].icon,loading:i.value==="saving",variant:"primary",text:t(c).label(p[i.value].text),onClick:S},null,8,["disabled","icon","loading","text"])])):r("",!0)],64)):r("",!0)]),v("div",G,[(a(!0),d(m,null,k(b.data.fields,(s,A)=>(a(),d("div",{class:"row",key:A+t(e).view+t(e).inlineEditField},[(a(!0),d(m,null,k(t(e).columns,l=>{var g;return a(),d("div",{key:l-1},[s[l-1]&&!(s[l-1].readonly&&t(e).view==="edit")?(a(),E(C,{key:0,view:t(e).inlineEditField===s[l-1].name?"edit":t(e).view,defs:s[l-1],data:{bean:t(e).bean.attributes},label:t(c).label(s[l-1].label,(g=t(B).currentModule)==null?void 0:g.name),modelValue:t(e).bean.attributes[s[l-1].name],"onUpdate:modelValue":[_=>t(e).bean.attributes[s[l-1].name]=_,_=>t(e).updateField(s[l-1].name,_)],onInlineEditBtnClicked:x,onInlineEditSave:S,onInlineEditCancel:F},null,8,["view","defs","data","label","modelValue","onUpdate:modelValue"])):r("",!0)])}),128))]))),128))])])}}});const X=U(j,[["__scopeId","data-v-cd6eccd0"]]);export{X as default};